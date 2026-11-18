
<?php
require_once __DIR__.'/../includes/data.php';
require_once __DIR__.'/../includes/cms-content.php';
require_once __DIR__.'/../includes/cms-admin.php';

cms_require_admin();

$sections = [
    'header' => 'Header',
    'hero' => 'Hero Section',
    'features' => 'Features',
    'rooms' => 'Rooms',
    'hotel_packages' => 'Hotel Packages',
    'footer' => 'Footer',
    'admins' => 'Admin Users'
];

$active = $_GET['section'] ?? 'header';

if (!isset($sections[$active])) {
    $active = 'header';
}

$message = '';
$error = '';

$fields = [
    'header' => [
        'brand' => 'Brand name',
        'subtitle' => 'Brand subtitle',
        'offer_label' => 'Offer button label'
    ],
    'hero' => [
        'eyebrow' => 'Small heading',
        'title' => 'Main heading',
        'subtitle' => 'Subtitle',
        'description' => 'Description',
        'button_text' => 'Button text',
        'button_url' => 'Button link',
        'image' => 'Background image filename (assets/images or assets/uploads)'
    ],
    'footer' => [
        'address' => 'Address',
        'phone' => 'Telephone',
        'email' => 'Email',
        'tagline' => 'Footer tagline',
        'copyright' => 'Copyright text'
    ]
];

$listFields = [
    'features' => [
        'title', 'description', 'icon'
    ],
    'rooms' => [
        'name', 'price', 'tag', 'view',
        'guests_text', 'description', 'image', 'balcony'
    ],
    'hotel_packages' => [
        'name', 'sub', 'price', 'unit', 'image', 'icon'
    ]
];

$listLabels = [
    'features' => 'Features (one per line: title | description | icon)',

    'rooms' => 'Rooms (name | price | tag | view | guest capacity | description | image | balcony yes/no)',

    'hotel_packages' => 'Hotel Packages (name | description | price | unit | image | icon)'
];

// Image upload
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    ($_POST['action'] ?? '') === 'upload_image'
) {
    if (!hash_equals(csrf_token(), $_POST['csrf'] ?? '')) {
        $error = 'Invalid upload request.';
    } elseif (
        empty($_FILES['image']['tmp_name']) ||
        $_FILES['image']['error'] !== UPLOAD_ERR_OK
    ) {
        $error = 'Choose an image to upload.';
    } else {
        $info = @getimagesize($_FILES['image']['tmp_name']);
        $mime = $info['mime'] ?? '';

        $map = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif'
        ];

        if (!isset($map[$mime])) {
            $error = 'Only JPG, PNG, WEBP or GIF images are allowed.';
        } else {
            $uploadDir = __DIR__ . '/../assets/uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $name = 'cms-' . bin2hex(random_bytes(6)) . '.' . $map[$mime];
            $dest = $uploadDir . $name;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $message = 'Image uploaded. Use this path: uploads/' . $name;
            } else {
                $error = 'Upload failed. Check assets/uploads folder permissions.';
            }
        }
    }
}

// Save form data
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    ($_POST['action'] ?? '') !== 'upload_image'
) {
    if (!hash_equals(csrf_token(), $_POST['csrf'] ?? '')) {
        $error = 'Invalid request. Refresh and try again.';
    } elseif ($active === 'admins') {

        $admins = cms_admins();
        $action = $_POST['action'] ?? '';

        if ($action === 'add') {
            $name = trim($_POST['name'] ?? '');
            $email = strtolower(trim($_POST['email'] ?? ''));
            $pw = $_POST['password'] ?? '';

            $exists = false;

            foreach ($admins as $a) {
                if (strtolower($a['email']) === $email) {
                    $exists = true;
                }
            }

            if (
                $name === '' ||
                !filter_var($email, FILTER_VALIDATE_EMAIL) ||
                strlen($pw) < 10 ||
                $exists
            ) {
                $error = 'Use a valid name/email, a unique email and a password of at least 10 characters.';
            } else {
                $id = 1;

                foreach ($admins as $a) {
                    $id = max($id, (int)$a['id'] + 1);
                }

                $admins[] = [
                    'id' => $id,
                    'name' => $name,
                    'email' => $email,
                    'password' => password_hash($pw, PASSWORD_DEFAULT)
                ];

                if (cms_admin_save($admins)) {
                    $message = 'New administrator added.';
                } else {
                    $error = 'Could not save admin accounts.';
                }
            }

        } elseif ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);

            if ($id === (int)$_SESSION['cms_admin']['id']) {
                $error = 'You cannot remove your own active account.';
            } elseif (count($admins) <= 1) {
                $error = 'At least one administrator must remain.';
            } else {
                $admins = array_values(array_filter(
                    $admins,
                    fn($a) => (int)$a['id'] !== $id
                ));

                if (cms_admin_save($admins)) {
                    $message = 'Administrator removed.';
                } else {
                    $error = 'Could not update admin accounts.';
                }
            }
        }

    } else {
        $data = $CMS;

        if (isset($fields[$active])) {
            foreach ($fields[$active] as $key => $label) {
                $data[$active][$key] = trim($_POST[$key] ?? '');
            }
        }

        if ($active === 'header') {
            $data['header']['nav_links'] = cms_rows(
                $_POST['nav_links'] ?? '',
                ['label', 'url']
            );
        }

        if ($active === 'features') {
            $data['features'] = cms_rows(
                $_POST['rows'] ?? '',
                $listFields['features']
            );
        }

        if ($active === 'rooms') {
            $data['rooms'] = cms_rows(
                $_POST['rows'] ?? '',
                $listFields['rooms']
            );

            foreach ($data['rooms'] as &$r) {
                $r['price'] = (float)$r['price'];
                $r['balcony'] = strtolower(trim($r['balcony'])) === 'yes';
            }

            unset($r);
        }

        if ($active === 'hotel_packages') {
            $data['hotel_packages'] = cms_rows(
                $_POST['rows'] ?? '',
                $listFields['hotel_packages']
            );

            foreach ($data['hotel_packages'] as &$pkg) {
                $pkg['price'] = (float)$pkg['price'];
            }

            unset($pkg);
        }

        if (cms_write_content($data)) {
            $message = 'Changes saved successfully. Open the website to view updates.';
            $CMS = $data;
        } else {
            $error = 'Could not save CMS content. Check config folder permissions.';
        }
    }
}

function field_value($a, $k) {
    return e($a[$k] ?? '');
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>ITC Ratnadipa CMS</title>
    <link rel="stylesheet" href="../assets/css/cms.css">
</head>

<body class="cms-layout">

<aside class="sidebar">
    <h2>ITC RATNADIPA</h2>
    <p>CONTENT MANAGEMENT</p>

    <?php foreach ($sections as $key => $label): ?>
        <a
            class="<?= $active === $key ? 'active' : '' ?>"
            href="?section=<?= e($key) ?>"
        >
            <?= e($label) ?>
        </a>
    <?php endforeach; ?>

    <a href="logout.php" class="logout">Sign out</a>
</aside>

<main class="cms-main">

    <div class="topbar">
        <div>
            <small>CONTENT MANAGEMENT SYSTEM</small>
            <h1><?= e($sections[$active]) ?></h1>
        </div>

        <div>
            <?= e($_SESSION['cms_admin']['name']) ?>
            ·
            <a href="../index.php" target="_blank">View website ↗</a>
        </div>
    </div>

    <?php if ($message): ?>
        <p class="success"><?= e($message) ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p class="error"><?= e($error) ?></p>
    <?php endif; ?>

    <?php if ($active === 'admins'): ?>

        <section class="panel">
            <h2>Add another administrator</h2>

            <form method="post" class="grid-form">
                <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="action" value="add">

                <label>
                    Name
                    <input name="name" required>
                </label>

                <label>
                    Email
                    <input name="email" type="email" required>
                </label>

                <label>
                    Password (10+ characters)
                    <input name="password" type="password" minlength="10" required>
                </label>

                <button>Add administrator</button>
            </form>
        </section>

        <section class="panel">
            <h2>Current administrators</h2>

            <?php foreach (cms_admins() as $a): ?>
                <div class="admin-row">
                    <span>
                        <strong><?= e($a['name']) ?></strong>
                        <small><?= e($a['email']) ?></small>
                    </span>

                    <?php if ((int)$a['id'] !== (int)$_SESSION['cms_admin']['id']): ?>
                        <form method="post" onsubmit="return confirm('Remove this administrator?')">
                            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
                            <button class="danger">Remove</button>
                        </form>
                    <?php else: ?>
                        <small>Current account</small>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </section>

    <?php else: ?>

        <section class="panel">
            <h2>Upload an image</h2>

            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="action" value="upload_image">

                <label>
                    Choose image (JPG, PNG, WEBP, GIF)
                    <input
                        type="file"
                        name="image"
                        accept="image/jpeg,image/png,image/webp,image/gif"
                        required
                    >
                </label>

                <button>Upload image</button>
            </form>

            <small>
                After upload, copy the displayed uploads/filename path
                into the relevant image filename field.
            </small>
        </section>

        <form method="post" class="panel">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

            <?php if (isset($fields[$active])): ?>
                <?php foreach ($fields[$active] as $key => $label): ?>
                    <label>
                        <?= e($label) ?>
                        <input
                            name="<?= e($key) ?>"
                            value="<?= field_value($CMS[$active], $key) ?>"
                        >
                    </label>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if ($active === 'header'): ?>
                <label class="wide">
                    Navigation links (one per line: label | URL)
                    <textarea name="nav_links" rows="6"><?= e(
                        cms_rows_text(
                            $CMS['header']['nav_links'] ?? [],
                            ['label', 'url']
                        )
                    ) ?></textarea>
                </label>
            <?php endif; ?>

            <?php if (isset($listFields[$active])):
                $rows = $CMS[$active] ?? [];

                if ($active === 'hotel_packages' && empty($rows)) {
                    $rows = $PACKAGES;
                }
            ?>
                <label class="wide">
                    <?= e($listLabels[$active]) ?>

                    <textarea name="rows" rows="12" spellcheck="false"><?= e(
                        cms_rows_text($rows, $listFields[$active])
                    ) ?></textarea>

                    <small>
                        Each line creates one item. Use the | symbol
                        between fields. Keep the field order shown above.
                    </small>
                </label>
            <?php endif; ?>

            <div class="actions">
                <button>Save <?= e($sections[$active]) ?></button>
                <a href="../index.php" target="_blank">Preview website</a>
            </div>
        </form>

    <?php endif; ?>

    <footer>
        ITC Ratnadipa · File-based CMS · No database required
    </footer>

</main>

</body>
</html>
