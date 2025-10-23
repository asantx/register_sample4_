$(document).ready(function() {
    // fetch session info and update admin UI (small resilient helper)
    function updateAdminFromSession() {
        var attempts = [];
        if (window && typeof window.appUrl === 'function') attempts.push(window.appUrl('actions/get_session_info.php'));
        attempts.push('../actions/get_session_info.php');

        function tryNext() {
            if (!attempts.length) return;
            var u = attempts.shift();
            $.getJSON(u).done(function(res) {
                if (res && res.logged_in) {
                    var name = res.user_name || 'Admin';
                    // set admin name in sidebar and header
                    $('.admin-user').text(name);
                    $('.admin-info').html('Logged in as: <span class="admin-user">' + $('<div>').text(name).html() + '</span>');

                    // Update top tray if present
                    if ($('.menu-tray').length) {
                        $('.menu-tray').html('\n                            <span class="love-heart">&#10084;&#65039;</span>\n                            <span class="me-2">Welcome back, <strong class="user-name">' + $('<div>').text(name).html() + '</strong></span>\n                            <a href="#" id="logout-btn" class="btn btn-sm btn-outline-danger ms-2">\n                                <i class="fa fa-sign-out-alt me-1"></i> Logout\n                            </a>\n                        ');
                    }
                }
            }).fail(function() {
                tryNext();
            });
        }

        tryNext();
    }

    updateAdminFromSession();

    // fetch and render categories
    function loadCategories() {
        $('#categories-table tbody').html('<tr><td colspan="3" class="text-center"><span class="spinner-border text-danger"></span> Loading...</td></tr>');
        $.ajax({
            url: '../actions/fetch_category_action.php',
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success' && res.categories && res.categories.length > 0) {
                    const rows = res.categories.map(function(c) {
                        return `<tr>
                            <td>${c.cat_id}</td>
                            <td class="cat-name">${escapeHtml(c.cat_name)}</td>
                            <td>
                                <button class="btn btn-sm btn-primary btn-edit btn-action" data-id="${c.cat_id}"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger btn-delete btn-action" data-id="${c.cat_id}"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>`;
                    }).join('');
                    $('#categories-table tbody').html(rows);
                } else {
                    $('#categories-table tbody').html('<tr><td colspan="3" class="text-center text-muted">No categories found. <span class="love-heart">&#10084;&#65039;</span></td></tr>');
                }
            },
            error: function(xhr, status, err) {
                console.error('Categories fetch error:', status, err);
                // Friendly inline error with Retry and Login options
                var loginUrl = '../login/login.php';
                var retryRow = '\n                    <tr>\n                        <td colspan="3" class="text-center">\n                            <div class="py-3">\n                                <div class="mb-2">\n                                    <strong class="text-danger">Unable to load categories</strong>\n                                </div>\n                                <div class="mb-2 text-muted">There was a problem fetching your categories. You can retry or sign in again if your session expired.</div>\n                                <div>\n                                    <button id="categories-retry" class="btn btn-sm btn-outline-danger me-2">Retry</button>\n                                    <a href="' + loginUrl + '" class="btn btn-sm btn-outline-secondary">Login</a>\n                                </div>\n                            </div>\n                        </td>\n                    </tr>';
                $('#categories-table tbody').html(retryRow);
            }
        });
    }

    function escapeHtml(s) {
        return $('<div>').text(s).html();
    }

    loadCategories();

    // add category
    $('#add-category-form').submit(function(e) {
        e.preventDefault();
        const name = $('#category-name').val().trim();
        if (!name) {
            Swal.fire({ icon:'error', title:'Required', text:'Category name is required' });
            return;
        }
        const btn = $('#add-category-btn');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Adding...');
        $.post('../actions/add_category_action.php', { name: name }, function(res) {
            if (res.status === 'success') {
                Swal.fire({
                    icon:'success',
                    title:'Category Added',
                    text:res.message,
                    showConfirmButton: false,
                    timer: 1200
                });
                $('#category-name').val('');
                loadCategories();
            } else {
                Swal.fire({ icon:'error', title:'Error', text:res.message });
            }
        }, 'json').fail(function() {
            Swal.fire({ icon:'error', title:'Error', text:'Could not add category. Please try again.' });
        }).always(function() {
            btn.prop('disabled', false).html('<i class="fa fa-plus"></i> Add Category');
        });
    });

    // delegate edit click
    $('#categories-table').on('click', '.btn-edit', function() {
        const id = $(this).data('id');
        const currentName = $(this).closest('tr').find('.cat-name').text();
        Swal.fire({
            title: 'Edit Category',
            input: 'text',
            inputValue: currentName,
            showCancelButton: true,
            confirmButtonColor: '#d72660',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Save',
            preConfirm: (newName) => {
                if (!newName) {
                    Swal.showValidationMessage('Name required');
                }
                return newName;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.showLoading();
                $.post('../actions/update_category_action.php', { cat_id: id, name: result.value }, function(res) {
                    Swal.close();
                    if (res.status === 'success') {
                        Swal.fire({
                            icon:'success',
                            title:'Category Updated',
                            text:res.message,
                            showConfirmButton: false,
                            timer: 1200
                        });
                        loadCategories();
                    } else {
                        Swal.fire({ icon:'error', title:'Error', text:res.message });
                    }
                }, 'json').fail(function() {
                    Swal.fire({ icon:'error', title:'Error', text:'Could not update category. Please try again.' });
                });
            }
        });
    });

    // delegate delete
    $('#categories-table').on('click', '.btn-delete', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Delete Category?',
            text: 'This action cannot be undone',
            showCancelButton: true,
            icon: 'warning',
            confirmButtonColor: '#d72660',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.showLoading();
                $.post('../actions/delete_category_action.php', { cat_id: id }, function(res) {
                    Swal.close();
                    if (res.status === 'success') {
                        Swal.fire({
                            icon:'success',
                            title:'Category Deleted',
                            text:res.message,
                            showConfirmButton: false,
                            timer: 1200
                        });
                        loadCategories();
                    } else {
                        Swal.fire({ icon:'error', title:'Error', text:res.message });
                    }
                }, 'json').fail(function() {
                    Swal.fire({ icon:'error', title:'Error', text:'Could not delete category. Please try again.' });
                });
            }
        });
    });

    // Retry handler (delegated because retry row is injected)
    $('#categories-table').on('click', '#categories-retry', function() {
        loadCategories();
    });
});
