//le code javascript pour formulaire

const quantityOptions = document.querySelectorAll('.quantity-option');
const quantityBadge = document.getElementById('quantity-badge');
const quantityText = document.getElementById('quantity-text');
const totalPrice = document.getElementById('total-price');
const productsContainer = document.getElementById('products-container');

function createProductFields(index) {
    return `
        <div class="product-container">
            <div class="product-header">منتج ${index + 1}</div>
            <div class="form-group">
                <div class="form-control">
                    <label for="product_color_${index}">لون المنتج</label>
                    <select id="product_color_${index}" name="product_color_${index}" required>
                        <option value="">المرجو إختيار لون المنتج</option>
                        <option value="Black">أسود</option>
                        <option value="LightGrey">رمادي</option>
                        <option value="SteelBlue">أزرق</option>
                        <option value="Green">اخضر</option>
                    </select>
                </div>
                <div class="form-control">
                    <label for="product_size_${index}">الحجم</label>
                    <select id="product_size_${index}" name="product_size_${index}" required>
                        <option value="">المرجو إختيار الحجم</option>
                        <option value="S">S (28-30)</option>
                        <option value="M">M (32)</option>
                        <option value="L">L (34)</option>
                        <option value="XL">XL (36)</option>
                        <option value="XXL">XXL (38-40)</option>
                        <option value="XXXL">XXXL (42-46)</option>
                    </select>
                </div>
            </div>
        </div>
    `;
}

function updateProductFields(quantity) {
    productsContainer.innerHTML = '';
    for (let i = 0; i < quantity; i++) {
        productsContainer.innerHTML += createProductFields(i);
    }
}

quantityOptions.forEach(option => {
    option.addEventListener('click', function() {
        quantityOptions.forEach(opt => opt.classList.remove('selected'));
        this.classList.add('selected');
        const quantity = parseInt(this.dataset.quantity);
        const price = parseInt(this.dataset.price);
        quantityBadge.textContent = `x${quantity}`;
        quantityText.textContent = quantity;
        totalPrice.textContent = `${price.toFixed(2)} درهم`;
        updateProductFields(quantity);
        document.querySelector('input[name="quantity"]').value = quantity;
    });
});

// Initialize with one product
updateProductFields(1);


// end of form code

var price_tiers = document.getElementById("price_tiers");
var selectElement = document.getElementById("tier_variante");
// var hiddenInput = document.getElementById("quantity");
var currencyElement = document.getElementById("price_displayed");


selectElement.addEventListener("change", function () {

    var selectedValue = selectElement.value;

    if (selectedValue === "1") {
        price_tiers.value = "179";
        // hiddenInput.value = "1";
        currencyElement.innerText = "179";
    } else if (selectedValue === "2") {
        price_tiers.value = "249";
        // hiddenInput.value = "2";
        currencyElement.innerText = "249";
    } else if (selectedValue === "3") {
        price_tiers.value = "349";
        // hiddenInput.value = "3";
        currencyElement.innerText = "349";
    } else {
        price_tiers.value = "179";
        // hiddenInput.value = "1";
        currencyElement.innerText = "179";
    }
});
