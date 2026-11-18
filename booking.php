<?php
require_once __DIR__ . "/includes/data.php";
$pageTitle = 'Book Your Stay | ITC Ratnadipa';
$active = 'rooms';
$stay = stay_from_request($_GET ?? []);

$roomId = filter_input(INPUT_GET, 'room_id', FILTER_VALIDATE_INT);
if (!$roomId || !isset($ROOMS[$roomId])) {
    $roomId = array_key_first($ROOMS);
}
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>
<main>
    <section class="page-hero">
        <p class="kicker">Book your stay</p>
        <h1>Guest details</h1>
    </section>

    <section class="section booking-layout">
        <form action="confirmation.php" method="POST" class="booking-form" id="booking-form" novalidate>
            <fieldset>
                <legend>Your stay</legend>
                <div class="form-row">
                    <label>Room
                        <select name="room_id" id="room_id" required>
                            <?php foreach ($ROOMS as $r): ?>
                                <option value="<?= (int)$r['id'] ?>" data-price="<?= (int)$r['price'] ?>" data-name="<?= e($r['name']) ?>" data-max="<?= (int)$r['max_guests'] ?>" <?= $r['id'] === $roomId ? 'selected' : '' ?>>
                                    <?= e($r['name']) ?> — <?= money($r['price']) ?> / night
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </div>
                <div class="form-row two">
                    <label>Check-in
                        <input type="date" name="check_in" id="check_in" value="<?= e($stay['check_in']) ?>" min="<?= e(date('Y-m-d')) ?>" required>
                    </label>
                    <label>Check-out
                        <input type="date" name="check_out" id="check_out" value="<?= e($stay['check_out']) ?>" min="<?= e(date('Y-m-d')) ?>" required>
                    </label>
                </div>
                <div class="form-row two">
                    <label>Adults
                        <input type="number" name="adults" id="adults" min="1" max="6" value="<?= (int)$stay['adults'] ?>" required>
                    </label>
                    <label>Children
                        <input type="number" name="children" id="children" min="0" max="4" value="<?= (int)$stay['children'] ?>">
                    </label>
                </div>
                <p class="field-hint" id="capacity-hint" hidden></p>
            </fieldset>

            <fieldset>
                <legend>Who is staying</legend>
                <div class="form-row">
                    <label>Full name
                        <input type="text" name="guest_name" autocomplete="name" required>
                    </label>
                </div>
                <div class="form-row two">
                    <label>Email
                        <input type="email" name="email" autocomplete="email" required>
                    </label>
                    <label>Phone
                        <input type="tel" name="phone" autocomplete="tel" required>
                    </label>
                </div>
            </fieldset>

            <p class="form-error" role="alert" hidden></p>
            <button type="submit" name="confirm_booking" class="btn btn-gold btn-lg">Confirm Booking</button>
        </form>

        <aside class="summary" aria-live="polite">
            <h2>Booking summary</h2>
            <dl>
                <div><dt>Room</dt><dd id="sum-room">—</dd></div>
                <div><dt>Rate</dt><dd id="sum-rate">—</dd></div>
                <div><dt>Nights</dt><dd id="sum-nights">—</dd></div>
                <div><dt>Guests</dt><dd id="sum-guests">—</dd></div>
            </dl>
            <p class="summary-total"><span>Total</span> <strong id="sum-total">—</strong></p>
            <p class="summary-note">Final price is confirmed on the server when you submit.</p>
        </aside>
    </section>
</main>
<?php require_once 'includes/footer.php'; ?>
