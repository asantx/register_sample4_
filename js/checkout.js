$(document).ready(function () {
    loadCart();

    function loadCart() {
        $.ajax({
            url: '../actions/get_cart_action.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                renderCart(response);
            },
            error: function () {
                $('#cart-items').html('<div class="empty-cart"><i class="fas fa-exclamation-circle"></i> Failed to load cart</div>');
            }
        });
    }

    function renderCart(cartData) {
        if (!cartData.items || cartData.items.length === 0) {
            $('#cart-items').html(`
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart" style="font-size: 3rem; opacity: 0.3;"></i>
                    <p style="margin-top: 15px;">Your cart is empty</p>
                    <a href="shop.php" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; margin-top: 15px;">
                        <i class="fas fa-store"></i> Continue Shopping
                    </a>
                </div>
            `);
            $('#checkout-btn').prop('disabled', true);
            updateSummary(0, 0, 0);
            return;
        }

        let html = '';
        let subtotal = 0;

        cartData.items.forEach(function (item) {
            let imageUrl = item.product_image;

            let itemTotal = parseFloat(item.product_price) * parseInt(item.quantity);
            subtotal += itemTotal;

            html += `
                <div class="cart-item" data-product-id="${item.product_id}">
                    <div class="cart-item-image">
                        ${imageUrl ? `<img src="${imageUrl}" alt="${escapeHtml(item.product_title)}">` : '<i class="fas fa-image"></i>'}
                    </div>
                    <div class="cart-item-details">
                        <div class="cart-item-title">${escapeHtml(item.product_title)}</div>
                        <div class="cart-item-price">₦${parseFloat(item.product_price).toFixed(2)}</div>
                        <div class="quantity-control">
                            <button onclick="decreaseQuantity(${item.product_id})"><i class="fas fa-minus"></i></button>
                            <input type="number" value="${item.quantity}" min="1" onchange="updateQuantity(${item.product_id}, this.value)" class="qty-input">
                            <button onclick="increaseQuantity(${item.product_id})"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: 700; color: #667eea; margin-bottom: 10px;">₦${itemTotal.toFixed(2)}</div>
                        <button class="remove-btn" onclick="removeFromCart(${item.product_id})">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            `;
        });

        $('#cart-items').html(html);

        let tax = subtotal * 0.00;
        let total = subtotal + tax;
        updateSummary(subtotal, tax, total);
        $('#checkout-btn').prop('disabled', false);
    }

    function updateSummary(subtotal, tax, total) {
        $('#subtotal').text('₦' + parseFloat(subtotal).toFixed(2));
        $('#tax').text('₦' + parseFloat(tax).toFixed(2));
        $('#total').text('₦' + parseFloat(total).toFixed(2));
        $('#checkout-total').text('₦' + parseFloat(total).toFixed(2));
    }

    window.updateQuantity = function (productId, quantity) {
        quantity = parseInt(quantity);
        if (quantity < 1) quantity = 1;

        $.ajax({
            url: '../actions/update_quantity_action.php',
            type: 'POST',
            data: {
                product_id: productId,
                quantity: quantity
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    loadCart();
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated',
                        text: 'Quantity updated successfully!',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Error', response.error || 'Failed to update quantity', 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'Failed to update quantity', 'error');
            }
        });
    };

    window.increaseQuantity = function (productId) {
        let input = $(`input[value="${$('[data-product-id="' + productId + '"] .qty-input').val()}"]`).closest('.quantity-control').find('.qty-input');
        let currentQty = parseInt(input.val()) || 1;
        updateQuantity(productId, currentQty + 1);
    };

    window.decreaseQuantity = function (productId) {
        let input = $(`input[value="${$('[data-product-id="' + productId + '"] .qty-input').val()}"]`).closest('.quantity-control').find('.qty-input');
        let currentQty = parseInt(input.val()) || 1;
        if (currentQty > 1) {
            updateQuantity(productId, currentQty - 1);
        }
    };

    window.removeFromCart = function (productId) {
        Swal.fire({
            title: 'Remove Item?',
            text: 'Are you sure you want to remove this item from your cart?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#667eea',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../actions/remove_from_cart_action.php',
                    type: 'POST',
                    data: { product_id: productId },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            loadCart();
                            Swal.fire({
                                icon: 'success',
                                title: 'Removed',
                                text: 'Item removed from cart!',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire('Error', response.error || 'Failed to remove item', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'Failed to remove item', 'error');
                    }
                });
            }
        });
    };
});

function proceedToCheckout() {
    let checkoutModal = new bootstrap.Modal(document.getElementById('checkoutModal'));
    checkoutModal.show();
}

function confirmPayment() {
    let customerName = $('#customer-name').val().trim();
    let customerEmail = $('#customer-email').val().trim();
    let shippingAddress = $('#shipping-address').val().trim();

    if (!customerName || !customerEmail || !shippingAddress) {
        Swal.fire('Validation Error', 'Please fill in all fields', 'error');
        return;
    }

    if (!isValidEmail(customerEmail)) {
        Swal.fire('Validation Error', 'Please enter a valid email address', 'error');
        return;
    }

    $.ajax({
        url: '../actions/process_checkout_action.php',
        type: 'POST',
        data: {
            customer_name: customerName,
            customer_email: customerEmail,
            shipping_address: shippingAddress
        },
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Order Placed Successfully!',
                    html: `
                        <div style="text-align: left; margin-top: 15px;">
                            <p><strong>Order Reference:</strong> ${response.order_reference}</p>
                            <p><strong>Transaction ID:</strong> ${response.transaction_id}</p>
                            <p><strong>Total Amount:</strong> ₦${response.total_amount}</p>
                        </div>
                    `,
                    confirmButtonColor: '#667eea',
                    confirmButtonText: 'View Orders'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'orders.php';
                    }
                });

                // Close modal and reset form
                let modal = bootstrap.Modal.getInstance(document.getElementById('checkoutModal'));
                modal.hide();
                $('#checkout-form')[0].reset();
            } else {
                Swal.fire('Error', response.message || 'Failed to process order', 'error');
            }
        },
        error: function (xhr) {
            let errorMsg = 'Failed to process order';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMsg = xhr.responseJSON.error;
            }
            Swal.fire('Error', errorMsg, 'error');
        }
    });
}

function isValidEmail(email) {
    let re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function escapeHtml(text) {
    let map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function (m) { return map[m]; });
}