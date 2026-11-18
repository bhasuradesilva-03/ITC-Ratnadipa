<?php
// config/google.php — Google sign-in settings.
// 1. Open https://console.cloud.google.com/ > APIs & Services > Credentials.
// 2. Create credentials > OAuth client ID > Application type: Web application.
// 3. Under "Authorized JavaScript origins" add every address the site runs on, for example:
//      http://localhost            (XAMPP)
//      https://sites.gdoop.us      (your live site)
//    No redirect URI is needed.
// 4. Paste the Client ID below.
define('GOOGLE_CLIENT_ID', 'PASTE_YOUR_CLIENT_ID.apps.googleusercontent.com');

// Leave empty to allow any Google account, or restrict by email domain, e.g. ['gmail.com'].
define('GOOGLE_ALLOWED_DOMAINS', []);
