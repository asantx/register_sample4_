$(document).ready(function () {
    let allProducts = [];
    let searchTimeout;

    // Load categories, brands, and products on page load
    loadCategoriesBrands();
    loadProducts();
    updateCartBadge();

    // Setup event listeners for filters
    $('#category, #brand').on('change', filterProducts);
    $('#search').on('keyup', function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(filterProducts, 300);
    });

    function loadCategoriesBrands() {
        // Load categories
        $.ajax({
            url: '../actions/fetch_category_action.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data && data.status === 'success' && Array.isArray(data.categories)) {
                    let catOptions = '<option value="">All Categories</option>';
                    data.categories.forEach(function (cat) {
                        catOptions += `<option value="${cat.cat_id}">${cat.cat_name}</option>`;
                    });
                    $('#category').html(catOptions);
                }
            }
        });
        // Load brands
        $.ajax({
            url: '../actions/fetch_brand_action.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data && data.status === 'success' && Array.isArray(data.brands)) {
                    let brandOptions = '<option value="">All Brands</option>';
                    data.brands.forEach(function (brand) {
                        brandOptions += `<option value="${brand.brand_id}">${brand.brand_name}</option>`;
                    });
                    $('#brand').html(brandOptions);
                }
            }
        });
    }

    function loadProducts() {
        $.ajax({
            url: '../actions/fetch_product_action.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data && Array.isArray(data)) {
                    allProducts = data;
                    renderProducts(allProducts);
                }
            },
            error: function () {
                $('#product-grid').html('<div class="no-products col-12"><i class="fas fa-exclamation-circle"></i> Failed to load products</div>');
            }
        });
    }

    function filterProducts() {
        let search = $('#search').val().toLowerCase();
        let category = $('#category').val();
        let brand = $('#brand').val();

        let filtered = allProducts.filter(function (product) {
            let matchSearch = !search ||
                (product.product_title && product.product_title.toLowerCase().includes(search)) ||
                (product.product_keywords && product.product_keywords.toLowerCase().includes(search)) ||
                (product.product_desc && product.product_desc.toLowerCase().includes(search));

            let matchCategory = !category || String(product.product_cat) === String(category);
            let matchBrand = !brand || String(product.product_brand) === String(brand);

            return matchSearch && matchCategory && matchBrand;
        });

        renderProducts(filtered);
    }

    function renderProducts(products) {
        if (products.length === 0) {
            $('#product-grid').html('<div class="no-products col-12"><i class="fas fa-search"></i> No products found</div>');
            return;
        }

        let html = '';
        products.forEach(function (product) {
            let imageUrl = product.product_image ?
                (product.product_image.startsWith('http') ? product.product_image : 'http://' + window.location.host + '/' + product.product_image) :
                '';

            html += `
                <div class="product-card">
                    <div class="product-image">
                        ${imageUrl ? `<img src="${imageUrl}" alt="${escapeHtml(product.product_title)}">` : '<i class="fas fa-image"></i>'}
                    </div>
                    <div class="product-info">
                        <div class="product-title">${escapeHtml(product.product_title)}</div>
                        <div class="product-price">₦${parseFloat(product.product_price).toFixed(2)}</div>
                        <button class="add-to-cart-btn" onclick="addToCart(${product.product_id})">
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                    </div>
                </div>
            `;
        });

        $('#product-grid').html(html);
    }

    window.addToCart = function (productId) {
        $.ajax({
            url: '../actions/add_to_cart_action.php',
            type: 'POST',
            data: {
                product_id: productId,
                quantity: 1
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    updateCartBadge();
                    Swal.fire({
                        icon: 'success',
                        title: 'Added to Cart',
                        text: 'Product added to your cart successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Error', response.error || 'Failed to add to cart', 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'Failed to add to cart', 'error');
            }
        });
    };

    function updateCartBadge() {
        $.ajax({
            url: '../actions/get_cart_action.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.count) {
                    $('#cart-count').text(response.count).show();
                } else {
                    $('#cart-count').text('0').hide();
                }
            }
        });
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
});
