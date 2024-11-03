;(() => {

    /**
     * Sends a request to the REST API to add or remove an FAQ from a product.
     * 
     * @param {Object} data - Data to be sent in the request, including action, product_id, and faq_id.
     * @returns {Promise<void>} 
     */
    const pfaqmSetRequest = async (data) => {
        const pfaqmTabFaqWrapper = document.getElementById('pfaqm-tab-faq-list');
        const loaderWrap = document.querySelector('.pfaqm-product-loader');

        // Ensure target wrapper exists before proceeding
        if (!pfaqmTabFaqWrapper) return false;

        loaderWrap.style.display = 'block'; // Show loader while fetching data

        const endpoint = `product-faq-manager/v1/set-faq`; // API endpoint for setting FAQ
        const response = await pfaqmFetch(endpoint, data); // Send data to the endpoint

        // Check for a valid response and populate the FAQ list
        if (response) {
            pfaqmTabFaqWrapper.innerHTML = ''; // Clear existing content
            let faqMarkup = '';

            // Check if FAQs are returned and construct HTML markup for each
            const faqs = response.faq_posts;
            if (faqs.length > 0) {
                faqs.forEach((faq) => {
                    faqMarkup += `<div class="pfaqm-tab-faq-item">
                        <div class="pfaqm-tab-faq">
                            ${faq.title ? `<h3 class="pfaqm-tab-faq-title">${faq.title}</h3>` : ''}
                        </div>
                    </div>`;
                });
            }

            // Insert the FAQ markup and hide the loader
            pfaqmTabFaqWrapper.innerHTML = faqMarkup;
            loaderWrap.style.display = 'none';
        }
    };

    /**
     * Prepares and sends data to add or remove an FAQ based on user selection.
     * 
     * @param {string} action - Action type, either 'add' or 'remove'.
     * @param {number} faqId - ID of the FAQ to be added or removed.
     * @param {number} productId - ID of the product associated with the FAQ.
     * @returns {boolean}
     */
    const pfaqmGetSet = (action = 'add', faqId, productId) => {
        // Validate required parameters
        if (!action || !faqId || !productId) {
            return false;
        }

        // Data payload for the API request
        let data = {
            action: action,
            product_id: Number(productId),
            faq_id: Number(faqId)
        };

        // Call the API request function with the prepared data
        pfaqmSetRequest(data);
    };

    /**
     * Main function to initialize the MultiSelect component and set up event listeners.
     */
    const pfaqmMain = () => {
        const pfaqmFaqsSelect = document.getElementById('pfaqm-faq-select');

        if (pfaqmFaqsSelect) {
            // Retrieve product ID from the data attribute of the select element
            let productId = pfaqmFaqsSelect.getAttribute('data-product-id') ?? 0;

            // Initialize the MultiSelect dropdown with options and event handlers
            new MultiSelect(pfaqmFaqsSelect, {
                placeholder: 'Select FAQs',
                search: true,
                selectAll: false,
                onSelect: function(value, text, element) {
                    // Trigger add action when an FAQ is selected
                    pfaqmGetSet('add', value, productId);
                },
                onUnselect: function(value, text, element) {
                    // Trigger remove action when an FAQ is unselected
                    pfaqmGetSet('remove', value, productId);
                }
            });
        }
    };

    // Execute main function after the DOM is fully loaded
    document.addEventListener('DOMContentLoaded', () => {
        pfaqmMain();
    });
})();
