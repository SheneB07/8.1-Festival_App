<footer>
        <a href="index.php"><img src="assets/home-icon.png" id="home-icon"></a> 
        <a href="information.php"><img src="assets/info-icon.png" id="info-icon"></a>
        <a href="music.php"><img src="assets/music-icon.png" id="music-icon"></a>
        <a href="location.php"><img src="assets/location-icon.png" id="location-icon"></a>
    </footer>

        <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('service-worker.js')
                    .then(function() { console.log('Service Worker registered'); })
                    .catch(function(err) { console.error('SW registration failed:', err); });
            });
        }
        </script>