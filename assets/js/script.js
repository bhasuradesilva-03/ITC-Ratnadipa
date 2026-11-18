// assets/js/script.js
document.addEventListener('DOMContentLoaded', () => {
    const fmt = (n) => 'LKR ' + Math.round(n).toLocaleString('en-US');
    const isoToday = new Date().toISOString().slice(0, 10);
    const addDays = (iso, d) => {
        const dt = new Date(iso + 'T00:00:00');
        dt.setDate(dt.getDate() + d);
        return dt.toISOString().slice(0, 10);
    };

    try {
    /* Splash screen: progress tied to real page load, skip button, once per session */
    const splash = document.getElementById('splash');
    if (splash) {
        const root = document.documentElement;
        const fill = document.getElementById('splash-fill');
        const pct = document.getElementById('splash-pct');
        const msg = document.getElementById('splash-msg');
        const bar = document.getElementById('splash-bar');
        const skip = document.getElementById('splash-skip');
        const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const minTime = reduce ? 700 : 3300;
        const messages = [[0, 'Preparing your stay'], [38, 'Opening the ocean view'], [74, 'Welcome to ITC Ratnadipa']];
        const start = performance.now();
        let pageLoaded = document.readyState === 'complete';
        let finished = false;
        let value = 0;

        if (!pageLoaded) window.addEventListener('load', () => { pageLoaded = true; });

        const render = (p) => {
            const rounded = Math.round(p);
            fill.style.width = p + '%';
            pct.textContent = rounded + '%';
            bar.setAttribute('aria-valuenow', String(rounded));
            const current = messages.filter((m) => rounded >= m[0]).pop();
            if (current && msg.textContent !== current[1]) msg.textContent = current[1];
        };

        const leave = () => {
            if (finished) return;
            finished = true;
            try { sessionStorage.setItem('itc_splash', '1'); } catch (err) { /* private mode */ }
            splash.classList.add('is-leaving');
            setTimeout(() => {
                root.classList.remove('has-splash');
                splash.remove();
            }, reduce ? 350 : 1150);
        };

        const tick = (now) => {
            if (finished) return;
            const t = Math.min((now - start) / minTime, 1);
            const eased = 1 - Math.pow(1 - t, 2.2);
            // Hold at 92% until the page has really finished loading.
            const target = pageLoaded ? eased * 100 : Math.min(eased * 100, 92);
            value = Math.max(value, target);
            render(value);
            if (value >= 99.9 && pageLoaded) {
                render(100);
                setTimeout(leave, reduce ? 0 : 450);
                return;
            }
            requestAnimationFrame(tick);
        };
        requestAnimationFrame(tick);

        skip.addEventListener('click', leave);
        document.addEventListener('keydown', (ev) => {
            if (ev.key === 'Escape' || ev.key === 'Enter') leave();
        });
        skip.focus({ preventScroll: true });
    }
    } catch (err) { console.error(err); }

    try {
    /* Mobile navigation */
    const toggle = document.querySelector('.nav-toggle');
    const links = document.getElementById('nav-links');
    if (toggle && links) {
        toggle.addEventListener('click', () => {
            const open = links.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', String(open));
        });
    }
    } catch (err) { console.error(err); }

    try {
    /* Header shadow once the page scrolls */
    const header = document.querySelector('.site-header');
    const onScroll = () => header && header.classList.toggle('is-scrolled', window.scrollY > 8);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
    } catch (err) { console.error(err); }

    try {
    /* Date pairs: keep check-out after check-in, validate on submit (server still validates) */
    document.querySelectorAll('form').forEach((form) => {
        const inEl = form.querySelector('[name="check_in"]');
        const outEl = form.querySelector('[name="check_out"]');
        if (!inEl || !outEl) return;
        const errorEl = form.querySelector('.form-error');

        const showError = (msg) => {
            if (!errorEl) return;
            errorEl.textContent = msg;
            errorEl.hidden = !msg;
        };

        inEl.addEventListener('change', () => {
            if (!inEl.value) return;
            outEl.min = addDays(inEl.value, 1);
            if (!outEl.value || outEl.value <= inEl.value) outEl.value = addDays(inEl.value, 1);
            showError('');
        });
        outEl.addEventListener('change', () => showError(''));

        form.addEventListener('submit', (event) => {
            let msg = '';
            if (!inEl.value || !outEl.value) msg = 'Choose both check-in and check-out dates.';
            else if (inEl.value < isoToday) msg = 'Check-in cannot be in the past.';
            else if (outEl.value <= inEl.value) msg = 'Check-out must be after check-in.';

            if (!msg && form.id === 'booking-form') {
                const required = form.querySelectorAll('[required]');
                for (const el of required) {
                    if (!el.value.trim()) { msg = 'Fill in all required fields.'; el.focus(); break; }
                }
                const email = form.querySelector('[name="email"]');
                if (!msg && email && !/^\S+@\S+\.\S+$/.test(email.value)) { msg = 'Enter a valid email address.'; email.focus(); }
            }
            if (msg) { event.preventDefault(); showError(msg); }
        });
    });
    } catch (err) { console.error(err); }

    try {
    /* Booking page: live price summary */
    const booking = document.getElementById('booking-form');
    if (booking) {
        const room = booking.querySelector('#room_id');
        const inEl = booking.querySelector('#check_in');
        const outEl = booking.querySelector('#check_out');
        const adults = booking.querySelector('#adults');
        const children = booking.querySelector('#children');
        const hint = document.getElementById('capacity-hint');
        const set = (id, v) => { document.getElementById(id).textContent = v; };

        const update = () => {
            const opt = room.options[room.selectedIndex];
            const price = Number(opt.dataset.price);
            const max = Number(opt.dataset.max);
            const nights = inEl.value && outEl.value
                ? Math.round((new Date(outEl.value) - new Date(inEl.value)) / 86400000) : 0;
            const guests = (Number(adults.value) || 0) + (Number(children.value) || 0);

            set('sum-room', opt.dataset.name);
            set('sum-rate', fmt(price) + ' / night');
            set('sum-nights', nights > 0 ? String(nights) : 'Check your dates');
            set('sum-guests', guests + (guests === 1 ? ' guest' : ' guests'));
            set('sum-total', nights > 0 ? fmt(price * nights) : '—');

            const over = guests > max;
            hint.hidden = !over;
            hint.textContent = over ? `${opt.dataset.name} sleeps at most ${max} guests. Reduce guests or pick a larger room.` : '';
        };

        [room, inEl, outEl, adults, children].forEach((el) => {
            el.addEventListener('input', update);
            el.addEventListener('change', update);
        });
        update();
    }
    } catch (err) { console.error(err); }

    try {
    /* Share Your Experience: WhatsApp, Facebook, Instagram, copy link */
    const shareBtn = document.getElementById('share-btn');
    const shareMenu = document.getElementById('share-menu');
    if (shareBtn && shareMenu) {
        const pageUrl = window.location.origin + window.location.pathname.replace(/[^/]*$/, '');
        const text = 'Take a look at ITC Ratnadipa, A Luxury Collection Hotel in Colombo.';
        const u = encodeURIComponent(pageUrl);
        const t = encodeURIComponent(text);

        shareMenu.querySelector('[data-share="whatsapp"]').href = 'https://wa.me/?text=' + t + '%20' + u;
        shareMenu.querySelector('[data-share="facebook"]').href = 'https://www.facebook.com/sharer/sharer.php?u=' + u;

        const setOpen = (open) => {
            shareMenu.hidden = !open;
            shareBtn.setAttribute('aria-expanded', String(open));
        };

        const copyText = async (value) => {
            try {
                await navigator.clipboard.writeText(value);
            } catch (err) {
                const tmp = document.createElement('input');
                tmp.value = value;
                document.body.appendChild(tmp);
                tmp.select();
                document.execCommand('copy');
                tmp.remove();
            }
        };

        const flash = (button, message, original) => {
            const label = button.querySelector('span');
            label.textContent = message;
            setTimeout(() => { label.textContent = original; }, 2500);
        };

        shareBtn.addEventListener('click', () => setOpen(shareMenu.hidden));

        // Instagram has no web share link: copy the link, then open Instagram so it can be pasted.
        const igBtn = shareMenu.querySelector('[data-share="instagram"]');
        igBtn.addEventListener('click', async () => {
            await copyText(pageUrl);
            flash(igBtn, 'Link copied, paste it', 'Instagram');
            window.open('https://www.instagram.com/', '_blank', 'noopener');
        });

        const copyBtn = shareMenu.querySelector('[data-share="copy"]');
        copyBtn.addEventListener('click', async () => {
            await copyText(pageUrl);
            flash(copyBtn, 'Link copied', 'Copy link');
        });

        document.addEventListener('click', (ev) => {
            if (!shareMenu.hidden && !shareMenu.contains(ev.target) && !shareBtn.contains(ev.target)) setOpen(false);
        });
        document.addEventListener('keydown', (ev) => {
            if (ev.key === 'Escape' && !shareMenu.hidden) { setOpen(false); shareBtn.focus(); }
        });
    }
    } catch (err) { console.error(err); }

});
