<?php
// includes/search-form.php — expects $stay (see stay_from_request()).
$today = date('Y-m-d');
?>
<form action="rooms.php" method="GET" class="search-form" novalidate>
    <label class="field">
        <?= icon('calendar', 'i field-icon') ?>
        <span class="field-body">
            <span class="field-label">Check-in</span>
            <input type="date" name="check_in" value="<?= e($stay['check_in']) ?>" min="<?= e($today) ?>" required>
        </span>
    </label>

    <label class="field">
        <?= icon('calendar', 'i field-icon') ?>
        <span class="field-body">
            <span class="field-label">Check-out</span>
            <input type="date" name="check_out" value="<?= e($stay['check_out']) ?>" min="<?= e($today) ?>" required>
        </span>
    </label>

    <label class="field">
        <?= icon('user', 'i field-icon') ?>
        <span class="field-body">
            <span class="field-label">Adults</span>
            <select name="adults">
                <?php for ($n = 1; $n <= 4; $n++): ?>
                    <option value="<?= $n ?>"<?= $stay['adults'] === $n ? ' selected' : '' ?>><?= $n ?></option>
                <?php endfor; ?>
            </select>
        </span>
    </label>

    <label class="field">
        <?= icon('users', 'i field-icon') ?>
        <span class="field-body">
            <span class="field-label">Children</span>
            <select name="children">
                <?php for ($n = 0; $n <= 3; $n++): ?>
                    <option value="<?= $n ?>"<?= $stay['children'] === $n ? ' selected' : '' ?>><?= $n ?></option>
                <?php endfor; ?>
            </select>
        </span>
    </label>

    <label class="field">
        <?= icon('bed', 'i field-icon') ?>
        <span class="field-body">
            <span class="field-label">Rooms</span>
            <select name="rooms">
                <?php for ($n = 1; $n <= 4; $n++): ?>
                    <option value="<?= $n ?>"<?= $stay['rooms'] === $n ? ' selected' : '' ?>><?= $n ?></option>
                <?php endfor; ?>
            </select>
        </span>
    </label>

    <button type="submit" class="btn btn-navy search-btn"><?= icon('search') ?> Search Rooms</button>
    <p class="form-error" role="alert" hidden></p>
</form>
