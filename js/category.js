$(document).ready(function() {
    // fetch and render categories
    function loadCategories() {
        $.ajax({
            url: '../actions/fetch_category_action.php',
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    const rows = res.categories.map(function(c) {
                        return `<tr>
                            <td>${c.cat_id}</td>
                            <td class="cat-name">${escapeHtml(c.cat_name)}</td>
                            <td>
                                <button class="btn btn-sm btn-primary btn-edit" data-id="${c.cat_id}">Edit</button>
                                <button class="btn btn-sm btn-danger btn-delete" data-id="${c.cat_id}">Delete</button>
                            </td>
                        </tr>`;
                    }).join('');
                    $('#categories-table tbody').html(rows);
                } else {
                    $('#categories-table tbody').html('<tr><td colspan="3">No categories</td></tr>');
                }
            },
            error: function() {
                $('#categories-table tbody').html('<tr><td colspan="3">Error loading categories</td></tr>');
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
        $.post('../actions/add_category_action.php', { name: name }, function(res) {
            if (res.status === 'success') {
                Swal.fire({ icon:'success', title:'Added', text:res.message });
                $('#category-name').val('');
                loadCategories();
            } else {
                Swal.fire({ icon:'error', title:'Error', text:res.message });
            }
        }, 'json');
    });

    // delegate edit click
    $('#categories-table').on('click', '.btn-edit', function() {
        const id = $(this).data('id');
        const currentName = $(this).closest('tr').find('.cat-name').text();
        Swal.fire({
            title: 'Edit category',
            input: 'text',
            inputValue: currentName,
            showCancelButton: true,
            preConfirm: (newName) => {
                if (!newName) {
                    Swal.showValidationMessage('Name required');
                }
                return newName;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('../actions/update_category_action.php', { cat_id: id, name: result.value }, function(res) {
                    if (res.status === 'success') {
                        Swal.fire({ icon:'success', title:'Updated', text:res.message });
                        loadCategories();
                    } else {
                        Swal.fire({ icon:'error', title:'Error', text:res.message });
                    }
                }, 'json');
            }
        });
    });

    // delegate delete
    $('#categories-table').on('click', '.btn-delete', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Delete category?',
            text: 'This action cannot be undone',
            showCancelButton: true,
            icon: 'warning',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('../actions/delete_category_action.php', { cat_id: id }, function(res) {
                    if (res.status === 'success') {
                        Swal.fire({ icon:'success', title:'Deleted', text:res.message });
                        loadCategories();
                    } else {
                        Swal.fire({ icon:'error', title:'Error', text:res.message });
                    }
                }, 'json');
            }
        });
    });
});
