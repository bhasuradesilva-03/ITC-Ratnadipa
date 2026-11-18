<?php
// includes/splash.php — full-screen intro. Shown only when <html> has the class "has-splash"
// (set in header.php on the first visit of a browser session). Behaviour lives in assets/js/script.js.
$title = 'ITC RATNADIPA';
?>
<div class="splash" id="splash" role="dialog" aria-label="Welcome to ITC Ratnadipa">
    <div class="splash-bg" aria-hidden="true"></div>
    <div class="splash-shade" aria-hidden="true"></div>

    <div class="splash-center">
        <svg class="splash-emblem" viewBox="0 0 28 34" width="84" height="102" fill="none" stroke="#D9A441" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path pathLength="1" d="M4 30V13l10-9 10 9v17M4 30h20M9 30V15h10v15M14 4v26M11 18h6M11 23h6"/>
        </svg>

        <h1 class="splash-title" aria-label="ITC Ratnadipa">
            <?php foreach (str_split($title) as $i => $ch): ?>
                <span class="splash-letter" aria-hidden="true" style="--i: <?= (int)$i ?>"><?= $ch === ' ' ? '&nbsp;' : e($ch) ?></span>
            <?php endforeach; ?>
        </h1>

        <p class="splash-sub">A Luxury Collection Hotel, Colombo</p>
        <p class="splash-tag">Luxury Redefined</p>
    </div>

    <div class="splash-foot">
        <div class="splash-bar" role="progressbar" aria-label="Loading" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" id="splash-bar">
            <span id="splash-fill"></span>
        </div>
        <p class="splash-status"><span id="splash-msg">Preparing your stay</span><span id="splash-pct">0%</span></p>
    </div>

    <button type="button" class="splash-skip" id="splash-skip">Skip intro</button>

    <script>
        // Failsafe: never leave the intro stuck if the main script fails to load.
        setTimeout(function () { document.documentElement.classList.remove('has-splash'); }, 10000);
    </script>
</div>
