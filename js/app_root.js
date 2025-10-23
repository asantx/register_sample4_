// Attempts to detect the application root so AJAX calls can use a stable base path.
(function () {
    // Default to empty (root).
    window.APP_ROOT = '';

    // Expose a promise so callers can wait until detection finishes
    var resolveReady;
    window.APP_ROOT_READY = new Promise(function (res) { resolveReady = res; });

    // candidate roots to try.
    // Start with '', then try path-derived prefixes (e.g. /register_sample4_, /register_sample4_/admin),
    // then relative attempts ('./','../','../../'), then final '/'
    var candidates = [''];

    // derive path prefixes from current location.pathname (useful when site is served from a subfolder)
    try {
        var parts = (window.location && window.location.pathname) ? window.location.pathname.split('/').filter(Boolean) : [];
        // build prefixes like '/part1', '/part1/part2', ... in descending order (longer first)
        for (var i = parts.length; i >= 1; i--) {
            var p = '/' + parts.slice(0, i).join('/');
            if (candidates.indexOf(p) === -1) candidates.push(p);
        }
    } catch (e) { }

    // add some relative attempts
    candidates = candidates.concat(['./', '../', '../../', '../../../', '/']);

    function testCandidate(idx) {
        if (idx >= candidates.length) return finalize('');
        var base = candidates[idx];
        if (typeof base === 'string') base = base.replace(/\/$/, '');
        // build url: if base starts with '/', use absolute path; else build relative
        var url = '';
        if (base && base.indexOf('/') === 0) {
            url = base.replace(/\/$/, '') + '/actions/get_session_info.php';
        } else if (base) {
            url = base.replace(/\/$/, '') + '/actions/get_session_info.php';
        } else {
            url = 'actions/get_session_info.php';
        }
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
        // resolve the ready promise so other scripts can proceed
        try { resolveReady(window.APP_ROOT); } catch (e) { }
        return window.APP_ROOT;
    }

    // Start detection but don't block page load; other scripts can wait on APP_ROOT_READY
    testCandidate(0);
})();
