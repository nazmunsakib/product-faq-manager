/**
 * Product FAQs global script to handle API requests.
 */
const pfaqmFetch = async (endpoint = null, bodyData = {}) => {
    // Check if the endpoint is provided
    if (!endpoint) {
        return false;
    }

    try {
        // Fetch data from the API using POST method
        const response = await fetch(pfaqmObj.api_url + endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': pfaqmObj.nonce
            },
            body: JSON.stringify(bodyData)
        });
        
        // Check if the response status is not OK, throw an error
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        // Parse and return JSON data from the response
        const data = await response.json();
        return data;

    } catch (error) {
        // Log any errors that occur during the fetch request
        console.error('Error fetching:', error);
    }
}

/**
 * Function to handle accordion toggle for FAQs
 */
const pfaqmAccordion = (header, contentSelector) => {
    if( !header ){
        return false;
    }

    const content   = header.nextElementSibling;  // Get the content element after the header
    const itemWrap  = header.parentElement;
    
    // Toggle the height of the content to show/hide it
    if (content.style.height === "0px" || content.style.height === "") {
        content.style.height = content.scrollHeight + "px";  
        itemWrap.classList.add('pfaqm-open');
    } else {
        content.style.height = "0px";
        itemWrap.classList.remove('pfaqm-open');
    }
    
    // Collapse all other accordion items to ensure only one is open at a time
    const allItems = document.querySelectorAll(`.${contentSelector}`);
    allItems.forEach(item => {
      if (item !== content && item.style.height !== "0px") {
        item.style.height = "0px";
        item.parentElement.classList.remove('pfaqm-open');
      }
    });
}
