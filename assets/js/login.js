// assets/js/login.js — Google sign-in callback (the button itself is drawn by Google).
window.handleGoogleCredential = async function (response) {
    const errorEl = document.getElementById('login-error');
    const statusEl = document.getElementById('login-status');
    const cfg = window.ITC_LOGIN || {};

    errorEl.hidden = true;
    statusEl.hidden = false;

    try {
        const res = await fetch(cfg.endpoint, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': cfg.csrf },
            body: JSON.stringify({ credential: response.credential }),
        });
        const data = await res.json().catch(() => ({}));
        if (!res.ok || !data.ok) throw new Error(data.error || 'Sign-in failed. Please try again.');
        window.location.href = data.redirect || 'index.php';
    } catch (err) {
        statusEl.hidden = true;
        errorEl.textContent = err.message || 'Sign-in failed. Please try again.';
        errorEl.hidden = false;
    }
};
