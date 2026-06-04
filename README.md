# Festival App

A progressive web app for festival visitors that provides:

- festival schedule and artist details
- an interactive map with stage information
- festival news and general information
- support for Dutch and English content
- an install prompt for mobile devices

## Installation

1. Put the project folder in your web server root (for example, `htdocs` in XAMPP).
2. Open the app in a browser on your phone using the site URL.
   - For local development, use a network-accessible address such as `http://<your-pc-ip>/8.1-Festival_App`.
3. When the page loads, the browser should show a prompt to install or add the app to your home screen.
4. If the prompt does not appear automatically, use the browser menu and choose:
   - `Add to home screen` (Chrome/Android)
   - `Install app` or `Add to Home Screen` (other mobile browsers)

Once installed, the Festival App opens like a native app from your home screen.

## Notes

- The app is designed as a PWA (Progressive Web App) and uses `manifest.json` and a service worker.
- This means it can be installed on mobile devices and cached for faster loading.
- The app includes a download prompt experience, so visitors can install it directly from their browser.

## Running locally with XAMPP

1. Start Apache in XAMPP.
2. Place this project folder in `C:\xampp\htdocs\`.
3. Open a browser and visit:
   - `http://localhost/8.1-Festival_App`
4. To access it from your phone, use your computer's local IP address instead of `localhost`.

## Pages

- `index.php` — home page
- `location.php` — festival map and stage info
- `music.php` — artist schedule and info modal
- `information.php` — festival details and FAQs

## Author

This app is a festival web app with mobile install support and multilingual content.