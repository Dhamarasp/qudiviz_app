self.addEventListener('install', function (event) {
  event.waitUntil(
    caches.open('qudiviz-cache-v1').then(function (cache) {
      return cache.addAll([
        '/',
        '/manifest.json',
        '/css/app.css',
        '/js/app.js',
        // tambahkan asset lain jika perlu
      ]);
    })
  );
});

self.addEventListener('fetch', function (event) {
  event.respondWith(
    caches.match(event.request).then(function (response) {
      return response || fetch(event.request);
    })
  );
});
