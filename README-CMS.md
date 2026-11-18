# ITC Ratnadipa file-based CMS

This CMS is integrated into the existing PHP website. It uses PHP files for content and administrator accounts; it does not connect to MySQL and does not store CMS content in JSON.

## First-time setup (XAMPP on macOS)
1. Copy the complete `itc-ratnadipa` folder into `/Applications/XAMPP/xamppfiles/htdocs/`.
2. Start Apache in XAMPP.
3. Open `http://localhost/itc-ratnadipa/cms-admin-setup.php`.
4. Create the first CMS administrator. Use a strong password of at least 10 characters.
5. Sign in at `http://localhost/itc-ratnadipa/cms/login.php`.
6. Use the left menu to edit Header, Hero, Features, Rooms, Restaurant, Offers & Promo Codes, Reviews, Footer and Admin Users.
7. To add another administrator, open **Admin Users** while signed in.

## Image uploads
Use the **Upload an image** panel in the CMS. It accepts JPG, PNG, WEBP and GIF. Copy the resulting `uploads/filename.ext` value into the image filename field for Hero, Rooms or Restaurant. Uploaded files are saved in `assets/uploads/`.

## Editing repeatable items
For Features, Rooms, Restaurant items, Offers and Reviews, the editor uses one item per line and separates fields with ` | ` (space-pipe-space). Keep the field order shown below each editor. Example feature:

`Ocean Views | Enjoy views of the Indian Ocean | waves`

Room field order: `name | price | tag | view | guest capacity | description | image | balcony yes/no`

Restaurant item order: `name | description | image`

Offer order: `title | description | promo code | discount | valid until YYYY-MM-DD | active yes/no`

Review order: `guest name | rating 1-5 | review | approved yes/no`

## File storage and permissions
- CMS content: `config/cms-content.php` (PHP array)
- CMS administrators: `config/cms-admins.php` (PHP array with password hashes)
- Images: `assets/uploads/`

The PHP process must be allowed to write to `config/` and `assets/uploads/`. Do not make the entire website writable. `config/.htaccess` blocks direct web access to configuration files, and `assets/uploads/.htaccess` blocks PHP execution in the upload folder.

## Important notes
- The existing customer login and registration are separate from CMS administrator login.
- Creating CMS administrators does not create customer accounts.
- Offers display their promo codes on the public home page. Applying a discount to a booking/payment is a separate booking-flow feature and is not activated by this content editor.
- Keep a backup of the project folder before replacing the existing copy.
