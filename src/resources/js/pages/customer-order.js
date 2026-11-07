document.addEventListener('DOMContentLoaded', () => {

    const serviceInputs = document.querySelectorAll(
        'input[name="service_type"]'
    );

    const continueButton = document.getElementById('continueOrder');
    const scheduleSection = document.getElementById('scheduleSection');

    const pickupDate = document.getElementById('pickupDate');
    const pickupTime = document.getElementById('pickupTime');

    function updateService() {

        const selected = document.querySelector(
            'input[name="service_type"]:checked'
        );

        if (!selected) {
            continueButton.disabled = true;
            scheduleSection.classList.add('d-none');
            return;
        }

        const isAfterEvent = selected.value === 'after_event';

        scheduleSection.classList.toggle(
            'd-none',
            !isAfterEvent
        );

        continueButton.disabled = isAfterEvent
            ? !pickupDate.value || !pickupTime.value
            : false;
    }

    serviceInputs.forEach(input => {
        input.addEventListener('change', updateService);
    });

    pickupDate.addEventListener('change', updateService);
    pickupTime.addEventListener('change', updateService);

    continueButton.addEventListener('click', () => {

        const selected = document.querySelector(
            'input[name="service_type"]:checked'
        );

        if (!selected) return;

        const orderData = {
            service_type: selected.value,
            pickup_date: pickupDate.value || null,
            pickup_time: pickupTime.value || null
        };

        sessionStorage.setItem(
            'kantin_order_service',
            JSON.stringify(orderData)
        );

        window.location.href = '/pesan/tenant';

    });

});