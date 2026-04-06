import './bootstrap';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

document.addEventListener('DOMContentLoaded', () => {
    const fulfillmentSelect = document.querySelector('[data-fulfillment-select]');
    const locationGroup = document.querySelector('[data-delivery-location-group]');
    const locationInput = document.querySelector('[data-delivery-location-input]');
    const locationHelp = document.querySelector('[data-delivery-location-help]');

    if (!fulfillmentSelect || !locationGroup || !locationInput || !locationHelp) {
        return;
    }

    const syncFulfillmentFields = () => {
        const isDelivery = fulfillmentSelect.value === 'delivery';

        locationGroup.classList.toggle('d-none', !isDelivery);
        locationInput.required = isDelivery;
        locationInput.placeholder = isDelivery
            ? 'Hostel, department, or campus meeting point'
            : 'Pickup desk at campus canteen';
        locationHelp.textContent = isDelivery
            ? 'Add the hostel, lecture block, or meeting point for delivery.'
            : 'Location is not required for pickup orders.';
    };

    syncFulfillmentFields();
    fulfillmentSelect.addEventListener('change', syncFulfillmentFields);
});
