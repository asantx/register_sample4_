$(document).ready(function () {
    function renderMenu(data) {
        const tray = $('.menu-tray');
        if (!tray.length) return;

        if (data.logged_in) {
            let adminBtn = '';
            if (String(data.user_role) === '2') {
                var adminHref = (window && typeof window.appUrl === 'function') ? window.appUrl('admin/dashboard.php') : 'admin/dashboard.php';
                adminBtn = '<a href="' + adminHref + '" class="btn btn-sm btn-outline-info ms-2">Admin</a>';
            }
            tray.html(`
                <span class="me-2">Welcome, <strong class="user-name">${escapeHtml(data.user_name || 'User')}</strong>!</span>
                ${adminBtn}
                <a href="#" id="logout-btn" class="btn btn-sm btn-outline-danger ms-2">Logout</a>
            `);
        } else {
            tray.html(`
                <a href="../login/register.php" class="btn btn-sm btn-outline-primary">Register</a>
                <a href="../login/login.php" class="btn btn-sm btn-outline-secondary ms-2">Login</a>
            `);
        }
    }

    function escapeHtml(s) {
        return $('<div>').text(s).html();
    }

    function fetchSession() {
        var url = (window && typeof window.appUrl === 'function') ? window.appUrl('actions/get_session_info.php') : null;
        var attempts = [];
        if (url) attempts.push(url);
    attempts.push('../actions/get_session_info.php');

        function tryNext() {
            if (!attempts.length) { renderMenu({ logged_in: false }); return; }
            var u = attempts.shift();
            $.getJSON(u).done(function (res) { renderMenu(res); }).fail(function () { tryNext(); });
        }
        tryNext();
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
                var logoutUrl = (window && typeof window.appUrl === 'function') ? window.appUrl('login/logout.php') : '../login/logout.php';
                $.post(logoutUrl).always(function () { fetchSession(); location.reload(); });
            }
        });
    });

    if (window && window.APP_ROOT_READY && typeof window.APP_ROOT_READY.then === 'function') {
        window.APP_ROOT_READY.then(function () { fetchSession(); }).catch(function () { fetchSession(); });
    } else {
        fetchSession();
    }
});
