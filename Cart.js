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
function removeItem(btn, productId){
    fetch('RemoveFromCart.php', 
    {
        method: 'POST',
        headers: {
            'Content-Type': "application/x-www-form-urlencoded",
        },
        body:'productId=' + encodeURIComponent(productId)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) 
        {
            let row = btn.closest('tr');
            row.remove();
            changeTotal();
        }
        else {
            alert("Failed to remove item!");
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });    
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