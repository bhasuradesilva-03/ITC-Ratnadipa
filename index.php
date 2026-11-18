
<?php
require_once __DIR__ . "/includes/data.php";
require_once __DIR__ . "/includes/cms-content.php";

$pageTitle = 'ITC Ratnadipa | A Luxury Collection Hotel, Colombo';
$active = 'home';
$withSplash = true;

$stay = stay_from_request($_GET ?? []);

// CMS Hero content
$hero = $CMS['hero'] ?? [];

$heroImage = trim($hero['image'] ?? '');
$heroImagePath = '';

if ($heroImage !== '') {
    if (strpos($heroImage, 'uploads/') === 0) {
        $heroImagePath = 'assets/' . $heroImage;
    } else {
        $heroImagePath = 'assets/images/' . basename($heroImage);
    }
}

require_once 'includes/header.php';
require_once 'includes/navbar.php';

$highlights = [
    ['waves', 'Stunning Ocean Views'],
    ['fork', 'Fine Dining'],
    ['waves', 'Infinity Pool'],
    ['spa', 'Spa & Wellness'],
    ['dumbbell', 'Fitness Centre'],
    ['event', 'Event Spaces'],
];
?>

<main>

    <!-- Hero Section -->
    <section class="hero"
        <?php if ($heroImagePath !== ''): ?>
            style="background-image: linear-gradient(rgba(0,0,0,0.35), rgba(0,0,0,0.35)), url('<?= e($heroImagePath) ?>');"
        <?php endif; ?>
    >

        <div class="hero-content">

            <p class="kicker">
                <?= e($hero['eyebrow'] ?? '') ?>
            </p>

            <h1>
                <?= e($hero['title'] ?? '') ?>
            </h1>

            <h2>
                <?= e($hero['subtitle'] ?? '') ?>
            </h2>

            <p class="hero-copy">
                <?= e($hero['description'] ?? '') ?>
            </p>

            <a class="btn btn-primary"
               href="<?= e($hero['button_url'] ?? 'rooms.php') ?>">
                <?= e($hero['button_text'] ?? 'Explore Rooms') ?>
            </a>

        </div>

    </section>

    <!-- Search Form -->
    <div class="search-wrap">
        <?php include 'includes/search-form.php'; ?>
    </div>

    <!-- Rooms Section -->
    <section class="section" id="rooms">

        <header class="section-head">
            <p class="kicker">Our Rooms &amp; Suites</p>

            <h2>Elegant Stays, Unforgettable Views</h2>

            <p>
                Choose from our beautifully appointed rooms and suites,
                each designed for your comfort with stunning views
                of the ocean, city or Beira Lake.
            </p>
        </header>

        <div class="room-grid">

            <?php foreach ($ROOMS as $room): ?>

                <?php include 'includes/room-card.php'; ?>

            <?php endforeach; ?>

        </div>
    </section>

    <!-- Packages Section -->
    <section class="section" id="packages">

        <header class="section-head">
            <p class="kicker">Hotel Packages</p>

            <h2>More Ways to Experience ITC Ratnadipa</h2>
        </header>

        <div class="package-grid">

            <?php foreach ($PACKAGES as $pkg): ?>

                <?php include 'includes/package-card.php'; ?>

            <?php endforeach; ?>

        </div>
    </section>

    <!-- Hotel Highlights -->
    <section class="highlights" id="highlights"
             aria-label="Hotel highlights">

        <ul>

            <?php foreach ($highlights as [$ico, $label]): ?>

                <li>
                    <?= icon($ico) ?>
                    <span><?= e($label) ?></span>
                </li>

            <?php endforeach; ?>

        </ul>

    </section>

</main>

<?php require_once 'includes/footer.php'; ?>
