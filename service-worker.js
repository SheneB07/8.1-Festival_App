const CACHE_NAME = 'festival-cache-v1';
const PRECACHE_URLS = [
  '.',
  'index.php',
  'style.css',
  'map.js',
  'manifest.json'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => cache.addAll(PRECACHE_URLS))
  );
  self.skipWaiting();
});

self.addEventListener('activate', event => {
  event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request).then(cached => cached || fetch(event.request).then(resp => {
      try {
        if (event.request.method === 'GET' && resp && resp.status === 200 && resp.type === 'basic') {
          const respClone = resp.clone();
          caches.open(CACHE_NAME).then(cache => cache.put(event.request, respClone));
        }
      } catch (e) {}
      return resp;
    })).catch(() => caches.match('/index.php'))
  );
});
