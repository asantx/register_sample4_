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
        // Session fetch removed from index.js per design — handled in page-specific scripts.
        // This function intentionally left blank.
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
});
