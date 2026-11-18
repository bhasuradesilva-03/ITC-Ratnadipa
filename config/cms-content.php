<?php
// ITC Ratnadipa CMS content. Edit through /cms/.
return array (
  'header' => 
  array (
    'brand' => 'ITC RATNADIPA',
    'subtitle' => 'A Luxury Collection Hotel | Colombo',
    'offer_label' => 'Special Offers',
    'nav_links' => 
    array (
      0 => 
      array (
        'label' => 'Home',
        'url' => 'index.php',
      ),
      1 => 
      array (
        'label' => 'Rooms & Suites',
        'url' => 'rooms.php',
      ),
      2 => 
      array (
        'label' => 'Dining',
        'url' => 'index.php#restaurant',
      ),
      3 => 
      array (
        'label' => 'Offers',
        'url' => 'index.php#offers',
      ),
      4 => 
      array (
        'label' => 'Contact',
        'url' => 'contact.php',
      ),
    ),
  ),
  'hero' => 
  array (
    'eyebrow' => 'Welcome to',
    'title' => 'ITC Ratnadipa',
    'subtitle' => 'A Luxury Collection Hotel, Colombo',
    'description' => 'Where the ocean meets the city, experience unparalleled luxury, breathtaking views and world-class hospitality.',
    'button_text' => 'Explore Rooms',
    'button_url' => 'rooms.php',
    'image' => 'hero.jpg',
  ),
  'features' => 
  array (
    0 => 
    array (
      'title' => 'Stunning Ocean Views',
      'description' => 'Enjoy beautiful views of the Indian Ocean.',
      'icon' => 'waves',
    ),
    1 => 
    array (
      'title' => 'Fine Dining',
      'description' => 'Discover memorable dining experiences.',
      'icon' => 'fork',
    ),
    2 => 
    array (
      'title' => 'Infinity Pool',
      'description' => 'Relax beside the pool overlooking the ocean.',
      'icon' => 'waves',
    ),
    3 => 
    array (
      'title' => 'Spa & Wellness',
      'description' => 'Restore and recharge in comfort.',
      'icon' => 'spa',
    ),
    4 => 
    array (
      'title' => 'Fitness Centre',
      'description' => 'Stay active during your stay.',
      'icon' => 'dumbbell',
    ),
    5 => 
    array (
      'title' => 'Event Spaces',
      'description' => 'Celebrate special moments with us.',
      'icon' => 'event',
    ),
  ),
  'rooms' => 
  array (
    0 => 
    array (
      'name' => 'Towers',
      'price' => 32000.0,
      'tag' => 'City View',
      'view' => 'City / Beira Lake View',
      'guests_text' => '2 Adults + 1 Child (Max 3)',
      'description' => 'A calm, well-appointed room with sweeping views over the city skyline and Beira Lake.',
      'image' => 'towers.jpg',
      'balcony' => false,
    ),
    1 => 
    array (
      'name' => 'Towers Ocean View',
      'price' => 38000.0,
      'tag' => 'Ocean View',
      'view' => 'Ocean View',
      'guests_text' => '2 Adults + 1 Child (Max 3)',
      'description' => 'An ocean-facing room with a private balcony, made for slow mornings and long sunsets.',
      'image' => 'towers-ocean.jpg',
      'balcony' => false,
    ),
    2 => 
    array (
      'name' => 'ITC One',
      'price' => 52000.0,
      'tag' => 'Ocean View',
      'view' => 'Ocean View',
      'guests_text' => '2 Adults + 2 Children (Max 4)',
      'description' => 'A premium ocean-view room with a bathtub, extra space and room for a small family.',
      'image' => 'itc-one.jpg',
      'balcony' => false,
    ),
    3 => 
    array (
      'name' => 'Towers Suite',
      'price' => 78000.0,
      'tag' => 'Ocean View',
      'view' => 'Ocean View',
      'guests_text' => '2 Adults + 3 Children (Max 5)',
      'description' => 'Our largest suite, with a separate living area, a bathtub and uninterrupted ocean views.',
      'image' => 'towers-suite.jpg',
      'balcony' => false,
    ),
  ),
  'restaurant' => 
  array (
    'title' => 'Dining at ITC Ratnadipa',
    'description' => 'Discover exceptional flavours and memorable dining experiences.',
    'items' => 
    array (
      0 => 
      array (
        'name' => 'Indian Cuisine',
        'description' => 'Authentic flavours and signature dishes.',
        'image' => 'full-board.jpg',
      ),
      1 => 
      array (
        'name' => 'All-day Dining',
        'description' => 'A selection of international favourites.',
        'image' => 'half-board.jpg',
      ),
    ),
  ),
  'offers' => 
  array (
    0 => 
    array (
      'title' => 'Stay & Dine',
      'description' => 'Enjoy a memorable stay with dining benefits.',
      'code' => 'STAYDINE',
      'discount' => '10%',
      'valid_until' => '2027-12-31',
      'active' => true,
    ),
  ),
  'reviews' => 
  array (
    0 => 
    array (
      'name' => 'Guest Review',
      'rating' => 5,
      'message' => 'A wonderful stay with warm hospitality.',
      'approved' => true,
    ),
  ),
  'footer' => 
  array (
    'address' => 'ITC Ratnadipa, 2 Galle Face Terrace, Colombo 00300, Sri Lanka',
    'phone' => '+94 11 2 100 100',
    'email' => 'reservations@itcratnadipa.com',
    'tagline' => 'Luxury Redefined',
    'copyright' => 'ITC Ratnadipa. All Rights Reserved.',
  ),
  'hotel_packages' => 
  array (
    0 => 
    array (
      'name' => 'Full Board',
      'sub' => 'Breakfast + Lunch + Dinner',
      'price' => 18000.0,
      'unit' => 'person / day',
      'image' => 'full-board.jpg',
      'icon' => 'fork',
    ),
    1 => 
    array (
      'name' => 'Half Board',
      'sub' => 'Breakfast + Dinner',
      'price' => 14000.0,
      'unit' => 'person / day',
      'image' => 'half-board.jpg',
      'icon' => 'fork',
    ),
    2 => 
    array (
      'name' => 'Day Out',
      'sub' => 'Pool, Dining & Leisure Access',
      'price' => 8000.0,
      'unit' => 'person',
      'image' => 'day-out.jpg',
      'icon' => 'sun',
    ),
    3 => 
    array (
      'name' => 'Parties & Events',
      'sub' => 'Weddings, Corporate & Special Events',
      'price' => 5000.0,
      'unit' => 'person',
      'image' => 'events.jpg',
      'icon' => 'event',
    ),
  ),
);
