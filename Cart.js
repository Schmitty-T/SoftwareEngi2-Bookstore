document.addEventListener('DOMContentLoaded', () => {
    changeTotal();
});
function increase(btn) {
    let input = btn.parentElement.querySelector('.input');
    input.value = parseInt(input.value) + 1;
    changeTotal();
    console.log('increased');
}

function decrease(btn){
    let input = btn.parentElement.querySelector('.input');
    let value = parseInt(input.value);
    if(value > 1){
        input.value = value - 1;
    }
    changeTotal();
}
function removeItem(btn){
    let row = btn.closest('tr');
    row.remove();
    changeTotal();
}

function changeTotal() {
    let total = 0;
    
    document.querySelectorAll('tbody tr').forEach(row=> {
        let priceText = row.querySelector('.titlecell p:last-child').innerText;
        let price = parseFloat(priceText.replace('$', ''));
        let input = row.querySelector('.input');
        
        if(input){
            let qty = parseInt(input.value);
            total+= price * qty;
        }
    });
    
    document.getElementById('total').innerText = total.toFixed(2);
}