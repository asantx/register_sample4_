// Attempts to detect the application root so AJAX calls can use a stable base path.
(function () {
    // Default to empty (root).
    window.APP_ROOT = '';

    // candidate roots to try ('' means site root, './' current folder, '../' parent, etc.)
    const candidates = ['', './', '../', '/'];

    function testCandidate(idx) {
        if (idx >= candidates.length) return finalize('');
        const base = candidates[idx].replace(/\/$/, '');
        const url = (base ? base + '/' : '') + 'actions/get_session_info.php';
        return fetch(url, { method: 'GET', credentials: 'same-origin' }).then(function (res) {
            if (res.ok) {
                // store normalized base (no trailing slash unless root '')
                window.APP_ROOT = base;
                return finalize(base);
            }
            return testCandidate(idx + 1);
        }).catch(function () {
            return testCandidate(idx + 1);
        });
    }

    function finalize(base) {
        // ensure APP_ROOT has no trailing slash, but empty string means root
        window.APP_ROOT = (base === '/' ? '' : (base || ''));
        // expose a helper to build URLs
        window.appUrl = function (path) {
            if (!path) return window.APP_ROOT || '/';
            // ensure leading slash
            path = path.replace(/^\/+/, '');
            return (window.APP_ROOT ? (window.APP_ROOT.replace(/\/$/, '') + '/') : '/') + path;
        };
        return window.APP_ROOT;
    }

    // Start detection but don't block page load; other scripts can call window.appUrl when needed.
    testCandidate(0);
})();
