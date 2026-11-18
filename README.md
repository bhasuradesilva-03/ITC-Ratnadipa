# ITC Ratnadipa — Frontend (Stages 1-3 of the development guide)

## Run it
1. Install XAMPP and start **Apache** (MySQL is not needed yet).
2. Copy this folder to `C:\xampp\htdocs\itc-ratnadipa`.
3. Open http://localhost/itc-ratnadipa/

## Pages
index, rooms (filters + search), room-details, packages, booking (live price), confirmation (server-side validation, demo only), contact.

## Notes
- Room and package data lives in `includes/data.php`. In the database stage, replace those arrays with MySQL queries (`database/hotel.sql` is ready).
- Images in `assets/images` were cropped from your mock-up and are low resolution. Replace them with real photos using the same filenames.
- Dining, Experiences and Gallery in the menu link to sections of the home page. Give them their own pages later.

## Google sign-in (login.php)
The whole site now requires a Google login; only "Continue with Google" is offered.
1. Google Cloud Console > APIs & Services > Credentials > Create credentials > OAuth client ID > Web application.
2. Add Authorized JavaScript origins: `http://localhost` (XAMPP) and your live address. No redirect URI is needed.
3. Paste the Client ID into `config/google.php`.
4. Open the site: splash screen, then login, then the home page.
Pages: `login.php`, `google-login.php` (verifies the token), `logout.php`. Protection comes from `includes/auth.php`.
