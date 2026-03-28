const CACHE_VERSION = 'v4';
const CACHE_NAME = `venta-carros-${CACHE_VERSION}`;

const URLS_CACHE = [
  '/venta-carros-pwa/',
  '/venta-carros-pwa/index.html',
  '/venta-carros-pwa/manifest.json'
];

// INSTALL EVENT
self.addEventListener('install', (event) => {
  console.log('[SW] Installing...');
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      console.log('[SW] Cache created:', CACHE_NAME);
      return cache.addAll(URLS_CACHE);
    }).catch(err => {
      console.error('[SW] Install error:', err);
    })
  );
  self.skipWaiting();
});

// ACTIVATE EVENT
self.addEventListener('activate', (event) => {
  console.log('[SW] Activating...');
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== CACHE_NAME) {
            console.log('[SW] Deleting old cache:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  self.clients.claim();
});

// FETCH EVENT
self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);

  // Skip non-GET requests
  if (request.method !== 'GET') {
    return;
  }

  // Skip external requests (CDN, APIs, etc)
  if (!url.hostname.includes('localhost')) {
    return;
  }

  // Navigation requests: Network first, fallback to cache
  if (request.mode === 'navigate') {
    event.respondWith(
      fetch(request)
        .then((response) => {
          if (response.ok) {
            const cache = caches.open(CACHE_NAME);
            cache.then((c) => c.put(request, response.clone()));
          }
          return response;
        })
        .catch(() => {
          return caches.match(request).then((cachedResponse) => {
            return cachedResponse || new Response('Offline mode', { status: 503 });
          });
        })
    );
    return;
  }

  // Asset requests: Cache first, fallback to network
  event.respondWith(
    caches.match(request).then((cachedResponse) => {
      if (cachedResponse) {
        return cachedResponse;
      }
      return fetch(request).then((response) => {
        if (response.ok) {
          caches.open(CACHE_NAME).then((cache) => {
            cache.put(request, response.clone());
          });
        }
        return response;
      });
    }).catch(() => {
      return new Response('Offline mode', { status: 503 });
    })
  );
});

// MESSAGE EVENT - Skip waiting
self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});
 