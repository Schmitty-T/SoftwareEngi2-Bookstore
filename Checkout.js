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
    inputElementContainer.setAttribute('class', 'input-container');
    containerElement.appendChild(inputElementContainer);

    const inputElement = document.createElement('input');
    inputElement.setAttribute('type', 'text');
    inputElement.setAttribute('placeholder', options.placeholder);
    inputElementContainer.appendChild(inputElement);

    var currItems;
    let focusedItemIndex;
    let currTimeout;
    let currPromiseReject;
    

    const MIN_ADDRESS_LENGTH = 3;
    const DEBOUNCE_DELAY = 300;

    const clearButton = document.createElement("div");
  clearButton.classList.add("clear-button");
  addIcon(clearButton);
  clearButton.addEventListener("click", (e) => {
    e.stopPropagation();
    inputElement.value = '';
    callback(null);
    clearButton.classList.remove("visible");
    closeDropDownList();
  });
  inputElementContainer.appendChild(clearButton);

    inputElement.addEventListener('input', function(e) {
        const currValue = this.value;

        if(currTimeout) {
            clearTimeout(currTimeout);
        }

        if(currPromiseReject) {
            currPromiseReject({
                canceled: true
            });
        }

        if(!currValue || currValue.length < MIN_ADDRESS_LENGTH) {
            return false;
        }

        if (!currValue) {
      clearButton.classList.remove("visible");
    }

    // Show clearButton when there is a text
    clearButton.classList.add("visible");

        currTimeout = setTimeout(() => {
            currTimeout = null;



            const promise = new Promise((resolve, reject) => {
                currPromiseReject = reject;

                
                const apiKey = '9e9eb729c51d46d99fe3e19c2b17614b';

                var url = `https://api.geoapify.com/v1/geocode/autocomplete?text=${encodeURIComponent(currValue)}&format=json&limit=5&apiKey=${apiKey}`;

                fetch(url)
                    .then(response => {
                        currPromiseReject = null;

                        if(response.ok) {
                            response.json().then(data => resolve(data));
                        }
                        else{
                            response.json().then(data => reject(data));
                        }
                    });
                });

                promise.then((data) => {

                    currItems = data.results;

                    const propogationItemsElement = document.createElement('div');
                    propogationItemsElement.setAttribute('class', 'autocomplete-items');
                    inputElementContainer.appendChild(propogationItemsElement);
                    

                    data.results.forEach((result, index) => {
                        const itemElement = document.createElement('div');
                        itemElement.innerHTML = result.formatted;
                        propogationItemsElement.appendChild(itemElement);
                        itemElement.addEventListener('click', function(e) {
                            inputElement.value = currItems[index].formatted;
                            callback(currItems[index]);

                            closeDropDownList();
                        });
                    });
                    console.log(data);
                }, (err) => {
                    if(!err.canceled) {
                        console.log(err);
                    }
                });
        }, DEBOUNCE_DELAY);
    });

    inputElement.addEventListener("keydown", function(e) {
    var propogationItemsElement = containerElement.querySelector(".autocomplete-items");
    if (propogationItemsElement) {
      var itemElements = propogationItemsElement.getElementsByTagName("div");
      if (e.keyCode == 40) {
        e.preventDefault();
        /*If the arrow DOWN key is pressed, increase the focusedItemIndex variable:*/
        focusedItemIndex = focusedItemIndex !== itemElements.length - 1 ? focusedItemIndex + 1 : 0;
        /*and and make the current item more visible:*/
        setActive(itemElements, focusedItemIndex);
      } else if (e.keyCode == 38) {
        e.preventDefault();

        /*If the arrow UP key is pressed, decrease the focusedItemIndex variable:*/
        focusedItemIndex = focusedItemIndex !== 0 ? focusedItemIndex - 1 : focusedItemIndex = (itemElements.length - 1);
        /*and and make the current item more visible:*/
        setActive(itemElements, focusedItemIndex);
      } else if (e.keyCode == 13) {
        /* If the ENTER key is pressed and value as selected, close the list*/
        e.preventDefault();
        if (focusedItemIndex > -1) {
          closeDropDownList();
        }
      }
    } else {
      if (e.keyCode == 40) {
        /* Open dropdown list again */
        var event = document.createEvent('Event');
        event.initEvent('input', true, true);
        inputElement.dispatchEvent(event);
      }
    }
  });
    
    function setActive(items, index) {
    if (!items || !items.length) return false;

    for (var i = 0; i < items.length; i++) {
      items[i].classList.remove("autocomplete-active");
    }

    /* Add class "autocomplete-active" to the active element*/
    items[index].classList.add("autocomplete-active");

    // Change input value and notify
    inputElement.value = currItems[index].formatted;
    callback(currItems[index]);
  }

    function closeDropDownList() {
        var propogationItemsElement = inputElementContainer.querySelector('.autocomplete-items');
        if(propogationItemsElement) {
            inputElementContainer.removeChild(propogationItemsElement);
        }
        focusedItemIndex = -1;
    }

    function addIcon(buttonElement) {
    const svgElement = document.createElementNS("http://www.w3.org/2000/svg", 'svg');
    svgElement.setAttribute('viewBox', "0 0 24 24");
    svgElement.setAttribute('height', "24");

    const iconElement = document.createElementNS("http://www.w3.org/2000/svg", 'path');
    iconElement.setAttribute("d", "M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z");
    iconElement.setAttribute('fill', 'currentColor');
    svgElement.appendChild(iconElement);
    buttonElement.appendChild(svgElement);
    }

    document.addEventListener("click", function(e) {
    if (e.target !== inputElement) {
      closeDropDownList();
    } else if (!containerElement.querySelector(".autocomplete-items")) {
      // open dropdown list again
      var event = document.createEvent('Event');
      event.initEvent('input', true, true);
      inputElement.dispatchEvent(event);
    }
  });
}

addressPropogation(document.getElementById('deliverymethodcontainer'), (data) => {
    console.log('Selected option: ');
    console.log(data);
}, {
    placeholder:"Enter an address here"
});
