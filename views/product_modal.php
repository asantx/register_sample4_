<!-- Product Quick View Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content product-modal-content">
            <div class="modal-header product-modal-header">
                <h5 class="modal-title" id="productModalLabel">
                    <i class="fas fa-heart heart-icon-animated me-2"></i>Product Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body product-modal-body">
                <div class="row">
                    <!-- Product Image -->
                    <div class="col-md-6">
                        <div class="product-modal-image-container">
                            <div class="product-modal-image" id="modal-product-image">
                                <i class="fas fa-heart"></i>
                            </div>
                            <div class="product-modal-badge" id="modal-product-badge" style="display: none;">
                                New
                            </div>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="col-md-6">
                        <div class="product-modal-info">
                            <div class="product-modal-category" id="modal-product-category">
                                Category
                            </div>
                            <h3 class="product-modal-title" id="modal-product-title">
                                Product Name
                            </h3>
                            <div class="product-modal-rating" id="modal-product-rating">
                                <span class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </span>
                                <span class="rating-count">(4.5/5 - 128 reviews)</span>
                            </div>
                            <div class="product-modal-price" id="modal-product-price">
                                ₦0.00
                            </div>
                            <div class="product-modal-description" id="modal-product-description">
                                Product description goes here...
                            </div>

                            <!-- Quantity Selector -->
                            <div class="product-modal-quantity">
                                <label class="quantity-label">Quantity:</label>
                                <div class="quantity-selector">
                                    <button type="button" class="qty-btn qty-minus" onclick="decreaseQuantity()">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" class="qty-input" id="modal-quantity" value="1" min="1" max="99">
                                    <button type="button" class="qty-btn qty-plus" onclick="increaseQuantity()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Add to Cart Button -->
                            <button class="btn-add-to-cart-modal" id="modal-add-to-cart" onclick="addToCartFromModal()">
                                <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                            </button>

                            <!-- Product Features -->
                            <div class="product-features">
                                <div class="feature-item">
                                    <i class="fas fa-shipping-fast"></i>
                                    <span>Free Shipping</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-undo"></i>
                                    <span>Easy Returns</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-shield-alt"></i>
                                    <span>Secure Payment</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .product-modal-content {
        border: none;
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: var(--shadow-xl);
    }

    .product-modal-header {
        background: var(--gradient-rose);
        color: white;
        padding: 1.5rem;
        border: none;
    }

    .product-modal-body {
        padding: 2rem;
    }

    .product-modal-image-container {
        position: relative;
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-md);
    }

    .product-modal-image {
        width: 100%;
        height: 400px;
        background: var(--gradient-soft-pink);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-pink);
        font-size: 5rem;
    }

    .product-modal-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-modal-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--gradient-rose);
        color: white;
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: 600;
        box-shadow: var(--shadow-md);
    }

    .product-modal-info {
        padding: 0 1rem;
    }

    .product-modal-category {
        color: var(--primary-pink-dark);
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 0.5rem;
    }

    .product-modal-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--darker-gray);
        margin-bottom: 1rem;
        line-height: 1.3;
    }

    .product-modal-rating {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        font-size: 1rem;
    }

    .product-modal-rating .stars {
        color: var(--accent-gold);
        margin-right: 10px;
        font-size: 1.1rem;
    }

    .product-modal-rating .rating-count {
        color: var(--dark-gray);
        font-size: 0.9rem;
    }

    .product-modal-price {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-pink);
        margin-bottom: 1.5rem;
        text-shadow: 0 2px 4px rgba(215, 38, 96, 0.1);
    }

    .product-modal-description {
        color: var(--dark-gray);
        line-height: 1.7;
        margin-bottom: 1.5rem;
        font-size: 1rem;
    }

    .product-modal-quantity {
        margin-bottom: 1.5rem;
    }

    .quantity-label {
        display: block;
        font-weight: 600;
        color: var(--darker-gray);
        margin-bottom: 0.5rem;
    }

    .quantity-selector {
        display: flex;
        align-items: center;
        gap: 0;
        width: fit-content;
        border: 2px solid var(--gray);
        border-radius: var(--radius-md);
        overflow: hidden;
    }

    .qty-btn {
        background: white;
        border: none;
        width: 40px;
        height: 40px;
        cursor: pointer;
        color: var(--primary-pink);
        font-size: 1rem;
        transition: all var(--transition-fast);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .qty-btn:hover {
        background: var(--primary-pink);
        color: white;
    }

    .qty-input {
        width: 60px;
        height: 40px;
        border: none;
        border-left: 1px solid var(--gray);
        border-right: 1px solid var(--gray);
        text-align: center;
        font-weight: 600;
        color: var(--darker-gray);
        font-size: 1.1rem;
    }

    .qty-input:focus {
        outline: none;
    }

    .btn-add-to-cart-modal {
        width: 100%;
        background: var(--gradient-rose);
        color: white;
        border: none;
        padding: 15px;
        border-radius: var(--radius-md);
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all var(--transition-normal);
        box-shadow: var(--shadow-md);
        margin-bottom: 1.5rem;
    }

    .btn-add-to-cart-modal:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-hover);
    }

    .btn-add-to-cart-modal:active {
        transform: translateY(-1px);
    }

    .product-features {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        padding: 1.5rem;
        background: var(--gradient-soft-pink);
        border-radius: var(--radius-md);
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 12px;
        color: var(--primary-pink-dark);
        font-weight: 500;
    }

    .feature-item i {
        font-size: 1.3rem;
        color: var(--primary-pink);
    }

    @media (max-width: 768px) {
        .product-modal-image {
            height: 300px;
        }

        .product-modal-info {
            padding: 1rem 0 0 0;
        }

        .product-modal-title {
            font-size: 1.5rem;
        }

        .product-modal-price {
            font-size: 1.5rem;
        }
    }
</style>

<script>
    let currentProductId = null;

    function openProductModal(productId, productName, productPrice, productCategory, productDescription, productImage) {
        currentProductId = productId;

        // Set modal content
        document.getElementById('modal-product-title').textContent = productName;
        document.getElementById('modal-product-price').textContent = '₦' + parseFloat(productPrice).toFixed(2);
        document.getElementById('modal-product-category').textContent = productCategory || 'Product';
        document.getElementById('modal-product-description').textContent = productDescription || 'No description available.';

        // Set product image
        const imageContainer = document.getElementById('modal-product-image');
        if (productImage && productImage !== '') {
            imageContainer.innerHTML = `<img src="${productImage}" alt="${productName}">`;
        } else {
            imageContainer.innerHTML = '<i class="fas fa-heart"></i>';
        }

        // Reset quantity
        document.getElementById('modal-quantity').value = 1;

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('productModal'));
        modal.show();
    }

    function increaseQuantity() {
        const input = document.getElementById('modal-quantity');
        const currentValue = parseInt(input.value) || 1;
        if (currentValue < 99) {
            input.value = currentValue + 1;
        }
    }

    function decreaseQuantity() {
        const input = document.getElementById('modal-quantity');
        const currentValue = parseInt(input.value) || 1;
        if (currentValue > 1) {
            input.value = currentValue - 1;
        }
    }

    function addToCartFromModal() {
        if (!currentProductId) return;

        const quantity = parseInt(document.getElementById('modal-quantity').value) || 1;
        const productName = document.getElementById('modal-product-title').textContent;

        // Add to cart via AJAX (reuse existing add to cart functionality)
        $.ajax({
            url: '../actions/add_to_cart_action.php',
            method: 'POST',
            data: {
                product_id: currentProductId,
                quantity: quantity
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Added to Cart!',
                        text: `${productName} has been added to your cart.`,
                        confirmButtonColor: '#d72660',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();

                    // Update cart count if function exists
                    if (typeof updateCartCount === 'function') {
                        updateCartCount();
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to add product to cart.',
                        confirmButtonColor: '#d72660'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred. Please try again.',
                    confirmButtonColor: '#d72660'
                });
            }
        });
    }
</script>
