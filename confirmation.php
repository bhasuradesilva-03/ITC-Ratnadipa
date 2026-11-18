<?php
// Frontend-stage confirmation: validates the POST on the server and shows a summary.
// Stage 9 of the guide: replace the "demo reference" with an INSERT into `bookings`
// (prepared statement) and redirect to confirmation.php?id=<insert_id>.
require_once 'includes/data.php';

$errors = [];
$in  = valid_date($_POST['check_in']  ?? null);
$out = valid_date($_POST['check_out'] ?? null);
$roomId   = filter_var($_POST['room_id'] ?? null, FILTER_VALIDATE_INT);
$adults   = filter_var($_POST['adults'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 6]]);
$children = filter_var($_POST['children'] ?? 0, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 4]]);
$name  = trim($_POST['guest_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') $errors[] = 'Start a booking from the rooms page.';
if (!$roomId || !isset($ROOMS[$roomId])) $errors[] = 'Choose a room.';
if (!$in || !$out) $errors[] = 'Enter valid check-in and check-out dates.';
elseif ($in < date('Y-m-d')) $errors[] = 'Check-in cannot be in the past.';
elseif (count_nights($in, $out) < 1) $errors[] = 'Check-out must be after check-in.';
if ($adults === false || $adults === null) $errors[] = 'Enter the number of adults (1 to 6).';
if ($children === false || $children === null) $errors[] = 'Enter a valid number of children (0 to 4).';
if ($name === '') $errors[] = 'Enter the guest name.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
if ($phone === '' || !preg_match('/^[0-9+()\-\s]{7,20}$/', $phone)) $errors[] = 'Enter a valid phone number.';
if (!$errors && $adults + $children > $ROOMS[$roomId]['max_guests']) {
    $errors[] = 'This room sleeps at most ' . $ROOMS[$roomId]['max_guests'] . ' guests.';
}

$pageTitle = $errors ? 'Check your details | ITC Ratnadipa' : 'Booking confirmed | ITC Ratnadipa';
$active = 'rooms';
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>
<main class="section narrow">
<?php if ($errors): ?>
    <h1>Check your details</h1>
    <ul class="notice notice-error">
        <?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?>
    </ul>
    <p><a class="btn btn-outline" href="javascript:history.back()">Go back and fix</a></p>
<?php else:
    $room = $ROOMS[$roomId];
    $nights = count_nights($in, $out);
    $total = $room['price'] * $nights;          // Guide section 16
    $reference = 'ITC-' . strtoupper(substr(md5($email . $in . $roomId . microtime()), 0, 8)); // demo only
?>
    <div class="confirm-mark"><?= icon('check') ?></div>
    <h1>Booking confirmed</h1>
    <p class="lead">Thank you, <?= e($name) ?>. A confirmation will be sent to <?= e($email) ?>.</p>

    <dl class="confirm-list">
        <div><dt>Reference</dt><dd><?= e($reference) ?></dd></div>
        <div><dt>Room</dt><dd><?= e($room['name']) ?></dd></div>
        <div><dt>Check-in</dt><dd><?= e(date('D j M Y', strtotime($in))) ?></dd></div>
        <div><dt>Check-out</dt><dd><?= e(date('D j M Y', strtotime($out))) ?></dd></div>
        <div><dt>Nights</dt><dd><?= (int)$nights ?></dd></div>
        <div><dt>Guests</dt><dd><?= (int)$adults ?> adult<?= $adults > 1 ? 's' : '' ?><?= $children ? ', ' . (int)$children . ' child' . ($children > 1 ? 'ren' : '') : '' ?></dd></div>
        <div class="total"><dt>Total</dt><dd>LKR <?= number_format($total, 2) ?></dd></div>
    </dl>
    <p class="summary-note">Demo mode: this booking is not stored yet. It will be saved to MySQL in the database stage.</p>
    <p><a class="btn btn-navy" href="index.php">Back to home</a></p>
<?php endif; ?>
</main>
<?php require_once 'includes/footer.php'; ?>
