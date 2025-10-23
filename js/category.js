$(document).ready(function() {
    // fetch session info and update admin UI
    function updateAdminFromSession() {
        var buildUrl = function(p) {
            if (window && typeof window.appUrl === 'function') return window.appUrl(p);
            return '../' + p; // fallback relative from admin folder
        };

        function doFetch() {
            var attempts = [];
            var url = null;
            if (window && typeof window.appUrl === 'function') url = window.appUrl('actions/get_session_info.php');
            if (url) attempts.push(url);
            attempts.push('../actions/get_session_info.php');

            function tryNext() {
                if (!attempts.length) return;
                var u = attempts.shift();
                $.getJSON(u).done(function(res) {
                    if (res && res.logged_in) {
                        var name = res.user_name || 'Admin';
                        $('.admin-user, .admin-name').each(function() {
                            // set only the span text if present
                            if ($(this).find('.admin-name').length) $(this).find('.admin-name').text(name);
                            else $(this).text('Logged in as: ' + name);
                        });
                    }
                }).fail(function() {
                    tryNext();
                });
            }

            tryNext();
        }

        if (window && window.APP_ROOT_READY && typeof window.APP_ROOT_READY.then === 'function') {
            window.APP_ROOT_READY.then(doFetch).catch(doFetch);
        } else {
            doFetch();
        }
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
                    $('#categories-table tbody').html('<tr><td colspan="3" class="text-center text-danger">No categories found. <span class="love-heart">&#10084;&#65039;</span></td></tr>');
                }
            },
            error: function() {
                $('#categories-table tbody').html('<tr><td colspan="3" class="text-center text-danger">Error loading categories <span class="love-heart">&#10084;&#65039;</span></td></tr>');
                Swal.fire({ icon:'error', title:'Error', text:'Could not load categories. Please try again.' });
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
});
