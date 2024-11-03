/**
 * Product FAQ WooCommerce - Accordion Functionality
 *
 * Initializes accordion functionality for FAQ sections on the WooCommerce product page.
 * Listens for clicks on FAQ headers to toggle visibility of corresponding FAQ content.
 */

(() => {
  // Wait for the DOM to fully load before executing the code.
  document.addEventListener('DOMContentLoaded', () => {
    // Select all elements with the class 'pfaqm-faq-header' for accordion functionality.
    const pfaqmAccordions = document.querySelectorAll('.pfaqm-faq-header');

    // Check if there are any FAQ headers to apply accordion behavior.
    if (pfaqmAccordions.length > 0) {
      // Iterate over each accordion header element.
      pfaqmAccordions.forEach((accordion) => {
        // Add a click event listener to each FAQ header.
        accordion.addEventListener('click', function() {
          // Call the pfaqmAccordion function, passing the clicked header and target content class.
          pfaqmAccordion(this, 'pfaqm-faq-content');
        });
      });
    }
  });
})();
