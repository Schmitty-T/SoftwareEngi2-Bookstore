document.addEventListener("DOMContentLoaded", () => {
    const checkboxes = document.querySelectorAll('.checkbox');
    const totalElement = document.getElementById('total');

    checkboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            let total = 0;

            checkboxes.forEach(c => {
                if (c.checked) {
                    total += parseFloat(c.value);
                }
            });

            totalElement.textContent = total.toFixed(2);
        });
    });
});