<?php
// File-based CMS content. The data is stored as executable PHP arrays, not a database.
$defaults = [
 'header'=>['brand'=>'ITC RATNADIPA','subtitle'=>'A Luxury Collection Hotel | Colombo','offer_label'=>'Special Offers','nav_links'=>[['label'=>'Home','url'=>'index.php'],['label'=>'Rooms & Suites','url'=>'rooms.php'],['label'=>'Dining','url'=>'index.php#restaurant'],['label'=>'Offers','url'=>'index.php#offers'],['label'=>'Contact','url'=>'contact.php']]],
 'hero'=>['eyebrow'=>'Welcome to','title'=>'ITC Ratnadipa','subtitle'=>'A Luxury Collection Hotel, Colombo','description'=>'Where the ocean meets the city, experience unparalleled luxury, breathtaking views and world-class hospitality.','button_text'=>'Explore Rooms','button_url'=>'rooms.php','image'=>'hero.jpg'],
 'features'=>[
  ['title'=>'Stunning Ocean Views','description'=>'Enjoy beautiful views of the Indian Ocean.','icon'=>'waves'],['title'=>'Fine Dining','description'=>'Discover memorable dining experiences.','icon'=>'fork'],['title'=>'Infinity Pool','description'=>'Relax beside the pool overlooking the ocean.','icon'=>'waves'],['title'=>'Spa & Wellness','description'=>'Restore and recharge in comfort.','icon'=>'spa'],['title'=>'Fitness Centre','description'=>'Stay active during your stay.','icon'=>'dumbbell'],['title'=>'Event Spaces','description'=>'Celebrate special moments with us.','icon'=>'event']],
 'rooms'=>[],
 'restaurant'=>['title'=>'Dining at ITC Ratnadipa','description'=>'Discover exceptional flavours and memorable dining experiences.','items'=>[['name'=>'Indian Cuisine','description'=>'Authentic flavours and signature dishes.','image'=>'full-board.jpg'],['name'=>'All-day Dining','description'=>'A selection of international favourites.','image'=>'half-board.jpg']]],
 'offers'=>[['title'=>'Stay & Dine','description'=>'Enjoy a memorable stay with dining benefits.','code'=>'STAYDINE','discount'=>'10%','valid_until'=>'2027-12-31','active'=>true]],
 'reviews'=>[['name'=>'Guest Review','rating'=>5,'message'=>'A wonderful stay with warm hospitality.','approved'=>true]],
 'footer'=>['address'=>'ITC Ratnadipa, 2 Galle Face Terrace, Colombo 00300, Sri Lanka','phone'=>'+94 11 2 100 100','email'=>'reservations@itcratnadipa.com','tagline'=>'Luxury Redefined','copyright'=>'ITC Ratnadipa. All Rights Reserved.']
];
$cmsFile = __DIR__ . '/../config/cms-content.php';
$cmsSaved = is_file($cmsFile) ? require $cmsFile : [];
$CMS = array_replace_recursive($defaults, is_array($cmsSaved) ? $cmsSaved : []);
if (!$CMS['rooms']) {
 foreach (($ROOMS ?? []) as $r) $CMS['rooms'][] = ['name'=>$r['name'],'price'=>$r['price'],'tag'=>$r['tag'],'view'=>$r['view'],'guests_text'=>$r['guests_text'],'description'=>$r['description'],'image'=>$r['image'],'balcony'=>$r['balcony']];
}
