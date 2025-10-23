$(document).ready(function() {
    // session personalization (reused pattern)
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
                    $('.admin-user').text(name);
                    $('.admin-info').html('Logged in as: <span class="admin-user">' + $('<div>').text(name).html() + '</span>');
                    if ($('.menu-tray').length) {
                        $('.menu-tray').html('\n                            <span class="love-heart">&#10084;&#65039;</span>\n                            <span class="me-2">Welcome back, <strong class="user-name">' + $('<div>').text(name).html() + '</strong></span>\n                            <a href="#" id="logout-btn" class="btn btn-sm btn-outline-danger ms-2">\n                                <i class="fa fa-sign-out-alt me-1"></i> Logout\n                            </a>\n                        ');
                    }
                }
            }).fail(function() { tryNext(); });
        }
        tryNext();
    }
    updateAdminFromSession();

    function loadBrands() {
        $('#brands-table tbody').html('<tr><td colspan="3" class="text-center"><span class="spinner-border text-danger"></span> Loading...</td></tr>');
        $.ajax({
            url: '../actions/fetch_brand_action.php',
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success' && res.brands && res.brands.length > 0) {
                    const rows = res.brands.map(function(b) {
                        return `<tr>
                            <td>${b.brand_id}</td>
                            <td class="brand-name">${escapeHtml(b.brand_name)}</td>
                            <td>
                                <button class="btn btn-sm btn-primary btn-edit btn-action" data-id="${b.brand_id}"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger btn-delete btn-action" data-id="${b.brand_id}"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>`;
                    }).join('');
                    $('#brands-table tbody').html(rows);
                } else {
                    $('#brands-table tbody').html('<tr><td colspan="3" class="text-center text-muted">No brands found. <span class="love-heart">&#10084;&#65039;</span></td></tr>');
                }
            },
            error: function(xhr, status, err) {
                console.error('Brands fetch error:', status, err);
                var loginUrl = '../login/login.php';
                var retryRow = '\n                    <tr>\n                        <td colspan="3" class="text-center">\n                            <div class="py-3">\n                                <div class="mb-2">\n                                    <strong class="text-danger">Unable to load brands</strong>\n                                </div>\n                                <div class="mb-2 text-muted">There was a problem fetching brands. Retry or sign in again.</div>\n                                <div>\n                                    <button id="brands-retry" class="btn btn-sm btn-outline-danger me-2">Retry</button>\n                                    <a href="' + loginUrl + '" class="btn btn-sm btn-outline-secondary">Login</a>\n                                </div>\n                            </div>\n                        </td>\n                    </tr>';
                $('#brands-table tbody').html(retryRow);
            }
        });
    }

    function escapeHtml(s) { return $('<div>').text(s).html(); }

    loadBrands();

    // add brand
    $('#add-brand-form').submit(function(e) {
        e.preventDefault();
        const name = $('#brand-name').val().trim();
        if (!name) { Swal.fire({ icon:'error', title:'Required', text:'Brand name is required' }); return; }
        const btn = $('#add-brand-btn');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Adding...');
        $.post('../actions/add_brand_action.php', { name: name }, function(res) {
            if (res.status === 'success') {
                Swal.fire({ icon:'success', title:'Brand Added', text:res.message, showConfirmButton:false, timer:1200 });
                $('#brand-name').val('');
                loadBrands();
            } else {
                Swal.fire({ icon:'error', title:'Error', text:res.message });
            }
        }, 'json').fail(function() { Swal.fire({ icon:'error', title:'Error', text:'Could not add brand. Please try again.' }); }).always(function() { btn.prop('disabled', false).html('<i class="fa fa-plus"></i> Add Brand'); });
    });

    // edit
    $('#brands-table').on('click', '.btn-edit', function() {
        const id = $(this).data('id');
        const currentName = $(this).closest('tr').find('.brand-name').text();
        Swal.fire({ title:'Edit Brand', input:'text', inputValue: currentName, showCancelButton:true, confirmButtonColor:'#d72660', cancelButtonColor:'#aaa', confirmButtonText:'Save', preConfirm:(newName)=>{ if(!newName) Swal.showValidationMessage('Name required'); return newName; } }).then((result)=>{
            if (result.isConfirmed) {
                Swal.showLoading();
                $.post('../actions/update_brand_action.php', { brand_id: id, name: result.value }, function(res) {
                    Swal.close();
                    if (res.status === 'success') { Swal.fire({ icon:'success', title:'Brand Updated', text:res.message, showConfirmButton:false, timer:1200 }); loadBrands(); }
                    else Swal.fire({ icon:'error', title:'Error', text:res.message });
                }, 'json').fail(function(){ Swal.fire({ icon:'error', title:'Error', text:'Could not update brand. Please try again.' }); });
            }
        });
    });

    // delete
    $('#brands-table').on('click', '.btn-delete', function() {
        const id = $(this).data('id');
        Swal.fire({ title:'Delete Brand?', text:'This action cannot be undone', showCancelButton:true, icon:'warning', confirmButtonColor:'#d72660', cancelButtonColor:'#aaa', confirmButtonText:'Delete', cancelButtonText:'Cancel' }).then((result)=>{
            if (result.isConfirmed) {
                Swal.showLoading();
                $.post('../actions/delete_brand_action.php', { brand_id: id }, function(res) {
                    Swal.close();
                    if (res.status === 'success') { Swal.fire({ icon:'success', title:'Brand Deleted', text:res.message, showConfirmButton:false, timer:1200 }); loadBrands(); }
                    else Swal.fire({ icon:'error', title:'Error', text:res.message });
                }, 'json').fail(function(){ Swal.fire({ icon:'error', title:'Error', text:'Could not delete brand. Please try again.' }); });
            }
        });
    });

    // retry
    $('#brands-table').on('click', '#brands-retry', function(){ loadBrands(); });

    // logout handler is injected by tray creation; keep delegated handler in global pages (dashboard/category)
});