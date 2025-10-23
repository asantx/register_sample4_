$(document).ready(function () {
    function renderMenu(data) {
        const tray = $('.menu-tray');
        if (!tray.length) return;

        if (data.logged_in) {
            let adminBtn = '';
            if (String(data.user_role) === '2') {
                adminBtn = '<a href="admin/dashboard.php" class="btn btn-sm btn-outline-info ms-2">Admin</a>';
            }
            tray.html(`
                <span class="me-2">Welcome, <strong class="user-name">${escapeHtml(data.user_name || 'User')}</strong>!</span>
                ${adminBtn}
                <a href="#" id="logout-btn" class="btn btn-sm btn-outline-danger ms-2">Logout</a>
            `);
        } else {
            tray.html(`
                <a href="login/register.php" class="btn btn-sm btn-outline-primary">Register</a>
                <a href="login/login.php" class="btn btn-sm btn-outline-secondary ms-2">Login</a>
            `);
        }
    }

    function escapeHtml(s) {
        return $('<div>').text(s).html();
    }

    function fetchSession() {
        // Fetch session info for the index page and render menu + page info
        var sessionUrl = 'actions/get_session_info.php';
        $.getJSON(sessionUrl).done(function (res) {
            renderMenu(res);
            // also render an info card on the index page if present
            var header = $('.love-header');
            // remove any existing info box
            $('#site-user-info').remove();
            if (!header.length) return;
            var infoHtml = '';
            if (res && res.logged_in) {
                var name = $('<div>').text(res.user_name || 'User').html();
                infoHtml += '<div id="site-user-info" class="mt-4 p-3" style="background:rgba(255,255,255,0.95);border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,0.06);text-align:center;">';
                infoHtml += '<strong>Welcome back, ' + name + '.</strong>';
                if (String(res.user_role) === '2') {
                    infoHtml += ' <span class="badge bg-danger ms-2">Admin</span>';
                    infoHtml += '<div class="mt-2"><a href="admin/dashboard.php" class="btn btn-sm btn-outline-danger">Go to Admin Dashboard</a></div>';
                } else {
                    infoHtml += '<div class="mt-2 text-muted">Thanks for visiting DistantLove.</div>';
                }
                infoHtml += '</div>';
            } else {
                infoHtml += '<div id="site-user-info" class="mt-4 p-3 text-center" style="background:rgba(255,255,255,0.9);border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,0.04);">';
                infoHtml += '<strong>Welcome to DistantLove</strong><div class="mt-2 text-muted">Create an account or login to access more features.</div>';
                infoHtml += '<div class="mt-3"><a href="login/register.php" class="btn btn-sm btn-outline-primary me-2">Register</a><a href="login/login.php" class="btn btn-sm btn-outline-secondary">Login</a></div>';
                infoHtml += '</div>';
            }
            header.after(infoHtml);
        }).fail(function () {
            // On failure, render logged-out menu and no info
            renderMenu({ logged_in: false });
            $('#site-user-info').remove();
        });
    }

    // Delegate logout click
    $(document).on('click', '#logout-btn', function (e) {
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
                var logoutUrl = 'login/logout.php';
                $.post(logoutUrl).always(function () {
                    fetchSession();
                    location.reload();
                });
            }
        });
    });

    // Session fetching is handled by page-specific scripts now.
    fetchSession();
});
