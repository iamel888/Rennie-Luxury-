// Initialize cart from localStorage or as an empty array
let cart = JSON.parse(localStorage.getItem('cart')) || [];

// Function to add a product to the cart
function addToCart(product) {
    const existingProductIndex = cart.findIndex(item => item.name === product.name);

    if (existingProductIndex > -1) {
        // If the product is already in the cart, increase its quantity
        cart[existingProductIndex].quantity += 1;
    } else {
        // Otherwise, add it as a new item
        cart.push(product);
    }

    // Save the updated cart to localStorage
    localStorage.setItem('cart', JSON.stringify(cart));

    // Show a notification
    showNotification(`${product.name} has been added to your cart.`);

    // Update cart display if on cart page
    if (document.getElementById('cart-items')) {
        updateCartDisplay();
    }
}

// Function to display a notification
function showNotification(message) {
    const notification = document.getElementById('notification');
    if (notification) {
        notification.textContent = message;
        notification.style.display = 'block';

        setTimeout(() => {
            notification.style.display = 'none';
        }, 3000); // Hide after 3 seconds
    }
}

// Function to update the cart display
function updateCartDisplay() {
    const cartItemsContainer = document.getElementById('cart-items');
    const subtotalElement = document.getElementById('subtotal');
    const totalElement = document.getElementById('total');

    // Clear existing items
    cartItemsContainer.innerHTML = '';

    if (cart.length === 0) {
        cartItemsContainer.innerHTML = '<tr><td colspan="5">Your cart is empty. Start shopping!</td></tr>';
        subtotalElement.textContent = '0.00';
        totalElement.textContent = '0.00';
        return;
    }

    let subtotal = 0;

    cart.forEach((item, index) => {
        const totalPrice = item.price * item.quantity;
        subtotal += totalPrice;

        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <div class="cart-item">
                    <img src="${item.image}" alt="${item.name}" width="50">
                    <p>${item.name}</p>
                </div>
            </td>
            <td>$${item.price.toFixed(2)}</td>
            <td>
                <input type="number" value="${item.quantity}" min="1" class="cart-quantity" data-index="${index}">
            </td>
            <td>$${totalPrice.toFixed(2)}</td>
            <td><button class="btn remove-item" data-index="${index}">Remove</button></td>
        `;

        cartItemsContainer.appendChild(row);
    });

    subtotalElement.textContent = subtotal.toFixed(2);
    totalElement.textContent = subtotal.toFixed(2);
}

// Attach event listeners to "Add to Cart" buttons
if (document.querySelectorAll('.btn-add-to-cart')) {
    document.querySelectorAll('.btn-add-to-cart').forEach(button => {
        button.addEventListener('click', function () {
            const productCard = this.closest('.product-card');
            const product = {
                name: productCard.querySelector('h3').textContent,
                price: parseFloat(productCard.querySelector('p').textContent.replace('$', '')),
                image: productCard.querySelector('img').src,
                quantity: 1,
            };

            addToCart(product);
        });
    });
}

// For product details page
const addToCartButton = document.querySelector('.btn-add-to-cart-single');
if (addToCartButton) {
    addToCartButton.addEventListener('click', function () {
        const productDetail = document.querySelector('#product-detail');
        const product = {
            name: productDetail.querySelector('h1').textContent,
            price: parseFloat(productDetail.querySelector('.price').textContent.replace('$', '')),
            image: productDetail.querySelector('.product-image img').src,
            quantity: 1,
        };

        addToCart(product);
    });
}

// Event listeners for cart actions
if (document.getElementById('cart-items')) {
    document.getElementById('cart-items').addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-item')) {
            const index = e.target.dataset.index;
            cart.splice(index, 1); // Remove the item from the cart
            localStorage.setItem('cart', JSON.stringify(cart)); // Update localStorage
            updateCartDisplay(); // Re-render cart
            showNotification('Item removed from cart!');
        }
    });

    document.getElementById('cart-items').addEventListener('input', function (e) {
        if (e.target.classList.contains('cart-quantity')) {
            const index = e.target.dataset.index;
            const newQuantity = parseInt(e.target.value);

            if (newQuantity > 0 && Number.isInteger(newQuantity)) {
                cart[index].quantity = newQuantity; // Update the quantity
                localStorage.setItem('cart', JSON.stringify(cart)); // Update localStorage
                updateCartDisplay(); // Re-render cart
            } else {
                alert('Please enter a valid quantity!');
                e.target.value = cart[index].quantity; // Reset to the previous valid value
            }
        }
    });
}

// Checkout button functionality
const checkoutButton = document.getElementById('checkout-btn');
if (checkoutButton) {
    checkoutButton.addEventListener('click', () => {
        if (cart.length > 0) {
            const confirmCheckout = confirm('Are you ready to proceed to checkout?');
            if (confirmCheckout) {
                alert('Redirecting to checkout...');
                window.location.href = 'checkout.html';
            }
        } else {
            alert('Your cart is empty!');
        }
    });
}

// Initial cart display update
if (document.getElementById('cart-items')) {
    updateCartDisplay();
}


