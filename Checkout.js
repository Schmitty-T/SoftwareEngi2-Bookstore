document.addEventListener("DOMContentLoaded", () => 
{
    const checkboxes = document.querySelectorAll('.checkbox');
    const totalElem = document.getElementById('total');
    
    checkboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            let total = 0;

            checkboxes.forEach(c => {
                if (c.checked) {
                    total += parseFloat(c.value);
                }
            });

            totalElem.textContent = total.toFixed(2);
        });
    });
});

function addressPropogation(containerElement, callback, options) {

    const inputElementContainer = document.createElement('div');
    inputElementContainer.setAttribute('class', 'input-contianer');
    containerElement.appendChild(inputElementContainer);

    const inputElement = document.createElement('input');
    inputElement.setAttribute('type', 'text');
    inputElement.setAttribute('placeholder', options.placeholder);
    inputContainerElement.appendChild(inputElement);

}

addressPropogation(document.getElementById('deliverymethodcontainer'), (data) => {
    console.log('Selected option: ');
    console.log(data);
}, {
    placeholder:"Enter an address here"
});
