<?php
require_once __DIR__ . "/includes/data.php";
$pageTitle = 'Packages | ITC Ratnadipa';
$active = 'packages';
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>
<main>
    <section class="page-hero">
        <p class="kicker">Hotel Packages</p>
        <h1>More ways to experience ITC Ratnadipa</h1>
    </section>
    <section class="section">
        <div class="package-grid">
            <?php foreach ($PACKAGES as $pkg): ?>
                <?php include 'includes/package-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </section>
</main>
<?php require_once 'includes/footer.php'; ?>
