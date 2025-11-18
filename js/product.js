$(document).ready(function() {
    // Session management and UI personalization
    function escapeHtml(s) {
        return $('<div>').text(s).html();
    }

    function updateAdminUI(data) {
        // Update admin name in sidebar
        if (data.user_name) {
            $('.admin-user').text(escapeHtml(data.user_name));
        }

        // Update menu tray
        const tray = $('.menu-tray');
        if (tray.length) {
            tray.html(`
                <span class="love-heart">❤️</span>
                <span class="me-2">Welcome back, <strong class="user-name">${escapeHtml(data.user_name || 'Admin')}</strong></span>
                <a href="#" id="logout-btn" class="btn btn-sm btn-outline-danger ms-2">
                    <i class="fa fa-sign-out-alt me-1"></i> Logout
                </a>
            `);
        }
    }

    function fetchSessionInfo() {
        $.getJSON('../actions/get_session_info.php')
            .done(function(res) {
                if (res.logged_in) {
                    updateAdminUI(res);
                }
            })
            .fail(function(err) {
                console.error('Failed to fetch session info:', err);
            });
    }

    // Load categories and brands for dropdowns
    function loadCategories() {
        $.getJSON('../actions/fetch_category_action.php')
            .done(function(response) {
                let categories = response.categories || response || [];
                if (!Array.isArray(categories)) {
                    categories = [];
                }
                const select = $('#product-category');
                select.find('option:not(:first)').remove();
                if (categories.length > 0) {
                    categories.forEach(function(category) {
                        select.append(`<option value="${category.cat_id}">${escapeHtml(category.cat_name)}</option>`);
                    });
                }
            })
            .fail(function(err) {
                console.error('Failed to load categories:', err);
            });
    }

    function loadBrands() {
        $.getJSON('../actions/fetch_brand_action.php')
            .done(function(response) {
                let brands = response.brands || response || [];
                if (!Array.isArray(brands)) {
                    brands = [];
                }
                const select = $('#product-brand');
                select.find('option:not(:first)').remove();
                if (brands.length > 0) {
                    brands.forEach(function(brand) {
                        select.append(`<option value="${brand.brand_id}">${escapeHtml(brand.brand_name)}</option>`);
                    });
                }
            })
            .fail(function(err) {
                console.error('Failed to load brands:', err);
            });
    }

    // Product image preview handling
    function handleImagePreview(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview').html(`<img src="${e.target.result}" alt="Preview" />`);
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            $('#image-preview').html('<i class="fa fa-cloud-upload-alt upload-icon"></i>');
        }
    }

    $('#product-image').change(function() {
        handleImagePreview(this);
    });

    // Load and display products
    function loadProducts() {
        $.getJSON('../actions/fetch_product_action.php')
            .done(function(products) {
                const tbody = $('#products-table tbody');
                if (products.length === 0) {
                    tbody.html('<tr><td colspan="6" class="text-center">No products found</td></tr>');
                    return;
                }

                tbody.empty();
                products.forEach(function(product) {
                    tbody.append(`
                        <tr>
                            <td class="text-center">
                                ${product.product_image 
                                    ? `<img src="${product.product_image}" alt="${escapeHtml(product.product_title)}" class="product-image-preview" />`
                                    : '<i class="fa fa-image text-muted"></i>'}
                            </td>
                            <td>${escapeHtml(product.product_title)}</td>
                            <td>${escapeHtml(product.cat_name || '')}</td>
                            <td>${escapeHtml(product.brand_name || '')}</td>
                            <td>$${parseFloat(product.product_price).toFixed(2)}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary btn-action me-1" 
                                        onclick="editProduct(${product.product_id})">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger btn-action"
                                        onclick="deleteProduct(${product.product_id})">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                });
            })
            .fail(function(err) {
                console.error('Failed to load products:', err);
                const tbody = $('#products-table tbody');
                tbody.html(`
                    <tr>
                        <td colspan="6" class="text-center text-danger">
                            Failed to load products. 
                            <button class="btn btn-link" onclick="loadProducts()">Retry</button>
                        </td>
                    </tr>
                `);
            });
    }

    // Add product
    $('#add-product-form').submit(function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '../actions/add_product_action.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false
        }).done(function(response) {
            if (response.error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.error
                });
                return;
            }
            
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Product added successfully'
            }).then(() => {
                $('#add-product-form')[0].reset();
                $('#image-preview').html('<i class="fa fa-cloud-upload-alt upload-icon"></i>');
                loadProducts();
            });
        }).fail(function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to add product. Please try again.'
            });
        });
    });

    // Edit product
    window.editProduct = function(productId) {
        $.getJSON(`../actions/fetch_product_action.php?id=${productId}`)
            .done(function(product) {
                Swal.fire({
                    title: 'Edit Product',
                    html: `
                        <form id="edit-product-form" class="text-start">
                            <input type="hidden" name="product_id" value="${product.product_id}" />
                            
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" value="${escapeHtml(product.product_title)}" required />
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label">Category</label>
                                    <select name="category_id" class="form-select" required>
                                        <option value="">Select Category</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="form-label">Brand</label>
                                    <select name="brand_id" class="form-select" required>
                                        <option value="">Select Brand</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Price</label>
                                <input type="number" name="price" class="form-control" step="0.01" value="${product.product_price}" required />
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3">${escapeHtml(product.product_desc || '')}</textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Keywords</label>
                                <textarea name="keywords" class="form-control" rows="2">${escapeHtml(product.product_keywords || '')}</textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">New Image (optional)</label>
                                <input type="file" name="image" class="form-control" accept="image/*" />
                            </div>
                            
                            ${product.product_image ? `
                                <div class="text-center mb-3">
                                    <img src="${product.product_image}" alt="Current" style="max-height:100px" class="img-thumbnail" />
                                    <div class="text-muted small">Current image</div>
                                </div>
                            ` : ''}
                        </form>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Save Changes',
                    confirmButtonColor: '#d72660',
                    cancelButtonText: 'Cancel',
                    preConfirm: () => {
                        const form = document.getElementById('edit-product-form');
                        const formData = new FormData(form);
                        
                        return $.ajax({
                            url: '../actions/update_product_action.php',
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false
                        }).then(response => {
                            if (response.error) {
                                throw new Error(response.error);
                            }
                            return response;
                        }).catch(error => {
                            Swal.showValidationMessage(error.message || 'Failed to update product');
                        });
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Product updated successfully'
                        });
                        loadProducts();
                    }
                });

                // Load categories and brands into the edit form
                $.getJSON('../actions/fetch_category_action.php')
                    .done(function(response) {
                        let categories = response.categories || response || [];
                        if (!Array.isArray(categories)) {
                            categories = [];
                        }
                        const select = $('.swal2-container select[name="category_id"]');
                        if (categories.length > 0) {
                            categories.forEach(function(category) {
                                const selected = category.cat_id == product.product_cat ? 'selected' : '';
                                select.append(`<option value="${category.cat_id}" ${selected}>${escapeHtml(category.cat_name)}</option>`);
                            });
                        }
                    });

                $.getJSON('../actions/fetch_brand_action.php')
                    .done(function(response) {
                        let brands = response.brands || response || [];
                        if (!Array.isArray(brands)) {
                            brands = [];
                        }
                        const select = $('.swal2-container select[name="brand_id"]');
                        if (brands.length > 0) {
                            brands.forEach(function(brand) {
                                const selected = brand.brand_id == product.product_brand ? 'selected' : '';
                                select.append(`<option value="${brand.brand_id}" ${selected}>${escapeHtml(brand.brand_name)}</option>`);
                            });
                        }
                    });
            })
            .fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load product details'
                });
            });
    };

    // Delete product
    window.deleteProduct = function(productId) {
        Swal.fire({
            title: 'Delete Product?',
            text: 'This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d72660',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('../actions/delete_product_action.php', { product_id: productId })
                    .done(function(response) {
                        if (response.error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.error
                            });
                            return;
                        }
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Product has been deleted'
                        });
                        loadProducts();
                    })
                    .fail(function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete product'
                        });
                    });
            }
        });
    };

    // Handle logout
    $(document).on('click', '#logout-btn', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Logout?',
            text: 'Are you sure you want to logout?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d72660',
            confirmButtonText: 'Logout',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('../login/logout.php')
                    .always(function() {
                        window.location.href = '../login/login.php';
                    });
            }
        });
    });

    // Initialize
    fetchSessionInfo();
    loadCategories();
    loadBrands();
    loadProducts();
});