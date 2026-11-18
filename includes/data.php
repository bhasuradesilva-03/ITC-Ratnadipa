
<?php
// includes/data.php
// Website data loaded from the file-based CMS.

// Amenity labels
$AMENITY_LABELS = [
    'wifi'    => 'Wi-Fi',
    'tv'      => 'TV',
    'minibar' => 'Mini Bar',
    'ac'      => 'A/C',
    'bath'    => 'Bathtub',
    'living'  => 'Living Area',
];

// Default room data
$ROOMS = [
    1 => [
        'id' => 1,
        'name' => 'Towers',
        'price' => 32000,
        'tag' => 'City View',
        'view' => 'City / Beira Lake View',
        'balcony' => false,
        'guests_text' => '2 Adults + 1 Child (Max 3)',
        'max_guests' => 3,
        'image' => 'towers.jpg',
        'amenities' => ['wifi', 'tv', 'minibar', 'ac'],
        'description' => 'A calm, well-appointed room with sweeping views over the city skyline and Beira Lake.',
    ],
    2 => [
        'id' => 2,
        'name' => 'Towers Ocean View',
        'price' => 38000,
        'tag' => 'Ocean View',
        'view' => 'Ocean View',
        'balcony' => true,
        'guests_text' => '2 Adults + 1 Child (Max 3)',
        'max_guests' => 3,
        'image' => 'towers-ocean.jpg',
        'amenities' => ['wifi', 'tv', 'minibar', 'ac'],
        'description' => 'An ocean-facing room with a private balcony, made for slow mornings and long sunsets.',
    ],
    3 => [
        'id' => 3,
        'name' => 'ITC One',
        'price' => 52000,
        'tag' => 'Ocean View',
        'view' => 'Ocean View',
        'balcony' => true,
        'guests_text' => '2 Adults + 2 Children (Max 4)',
        'max_guests' => 4,
        'image' => 'itc-one.jpg',
        'amenities' => ['wifi', 'tv', 'minibar', 'ac', 'bath'],
        'description' => 'A premium ocean-view room with a bathtub, extra space and room for a small family.',
    ],
    4 => [
        'id' => 4,
        'name' => 'Towers Suite',
        'price' => 78000,
        'tag' => 'Ocean View',
        'view' => 'Ocean View',
        'balcony' => true,
        'guests_text' => '2 Adults + 3 Children (Max 5)',
        'max_guests' => 5,
        'image' => 'towers-suite.jpg',
        'amenities' => ['wifi', 'tv', 'minibar', 'ac', 'bath', 'living'],
        'description' => 'Our largest suite, with a separate living area, a bathtub and uninterrupted ocean views.',
    ],
];

// Default packages.
// These are used only if CMS package data is not available.
$PACKAGES = [
    1 => [
        'id' => 1,
        'name' => 'Full Board',
        'sub' => 'Breakfast + Lunch + Dinner',
        'price' => 18000,
        'unit' => 'person / day',
        'image' => 'full-board.jpg',
        'icon' => 'fork'
    ],
    2 => [
        'id' => 2,
        'name' => 'Half Board',
        'sub' => 'Breakfast + Dinner',
        'price' => 14000,
        'unit' => 'person / day',
        'image' => 'half-board.jpg',
        'icon' => 'fork'
    ],
    3 => [
        'id' => 3,
        'name' => 'Day Out',
        'sub' => 'Pool, Dining & Leisure Access',
        'price' => 8000,
        'unit' => 'person',
        'image' => 'day-out.jpg',
        'icon' => 'sun'
    ],
    4 => [
        'id' => 4,
        'name' => 'Parties & Events',
        'sub' => 'Weddings, Corporate & Special Events',
        'price' => 5000,
        'unit' => 'person',
        'image' => 'events.jpg',
        'icon' => 'event'
    ],
];

// Social media links
$SOCIALS = [
    [
        'facebook',
        'Facebook',
        'https://www.facebook.com/share/1LWfy9KaMw/?mibextid=wwXIfr'
    ],
    [
        'instagram',
        'Instagram',
        'https://www.instagram.com/itcratnadipa?stkn=MThjaXRqdGJzdjJ5bQ=='
    ],
    [
        'linkedin',
        'LinkedIn',
        'https://www.linkedin.com/company/itcratnadipa/'
    ],
    [
        'youtube',
        'YouTube',
        'https://youtube.com/@itchotels?si=lrIDPXlHD9Y4bSyv'
    ],
];

// Escape HTML output
function e($value): string {
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}

// SVG icon helper
function icon(string $name, string $class = 'i'): string {
    return '<svg class="' . e($class) . '" aria-hidden="true"><use href="#i-' . e($name) . '"/></svg>';
}

// Format money
function money($amount): string {
    return 'LKR ' . number_format((float)$amount);
}

// Validate date
function valid_date($value): ?string {
    if (!is_string($value)) {
        return null;
    }

    $d = DateTime::createFromFormat('Y-m-d', $value);

    return ($d && $d->format('Y-m-d') === $value)
        ? $value
        : null;
}

// Calculate number of nights
function count_nights(string $in, string $out): int {
    return (int)(new DateTime($in))
        ->diff(new DateTime($out))
        ->format('%r%a');
}

// Read stay details from request
function stay_from_request(array $src): array {
    $in = valid_date($src['check_in'] ?? null)
        ?? date('Y-m-d', strtotime('+1 day'));

    $out = valid_date($src['check_out'] ?? null)
        ?? date('Y-m-d', strtotime('+4 days'));

    return [
        'check_in' => $in,
        'check_out' => $out,
        'adults' => max(1, min(6, (int)($src['adults'] ?? 2))),
        'children' => max(0, min(4, (int)($src['children'] ?? 0))),
        'rooms' => max(1, min(5, (int)($src['rooms'] ?? 1))),
    ];
}

// Build stay query
function stay_query(array $stay, array $extra = []): string {
    return http_build_query(array_merge($stay, $extra));
}

// --------------------------------------------------
// LOAD CONTENT FROM CMS
// --------------------------------------------------

require_once __DIR__ . '/cms-content.php';

// Load CMS room data
if (isset($CMS['rooms']) && is_array($CMS['rooms'])) {

    $updatedRooms = [];

    foreach ($CMS['rooms'] as $i => $r) {

        $updatedRooms[] = array_merge(
            [
                'id' => $i + 1,
                'name' => '',
                'price' => 0,
                'tag' => '',
                'view' => '',
                'balcony' => false,
                'guests_text' => '',
                'max_guests' => 2,
                'image' => 'towers.jpg',
                'amenities' => ['wifi', 'tv', 'ac'],
                'description' => ''
            ],
            $r,
            [
                'id' => $i + 1
            ]
        );
    }

    $ROOMS = $updatedRooms;
}

// Load hotel packages from CMS
// If CMS has saved package data, it replaces the default packages.

if (isset($CMS['hotel_packages']) && is_array($CMS['hotel_packages'])) {

    $updatedPackages = [];

    foreach ($CMS['hotel_packages'] as $i => $pkg) {

        $updatedPackages[$i + 1] = array_merge(
            [
                'id' => $i + 1,
                'name' => '',
                'sub' => '',
                'price' => 0,
                'unit' => 'person',
                'image' => '',
                'icon' => 'fork'
            ],
            $pkg,
            [
                'id' => $i + 1
            ]
        );
    }

    $PACKAGES = $updatedPackages;
}
