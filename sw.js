const CACHE_NAME = 'carros-pro-v3';
const APP_SHELL = ['/', '/index.html', '/manifest.json'];

self.addEventListener('install', event => {
  console.log('[SW] Installing...', CACHE_NAME);
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => {
      console.log('[SW] App shell cached');
      return cache.addAll(APP_SHELL).catch(err => {
        console.warn('[SW] Cache addAll error:', err);
        return cache.addAll(['/index.html']);
      });
    })
  );
  self.skipWaiting();
});

self.addEventListener('activate', event => {
  console.log('[SW] Activating...', CACHE_NAME);
  event.waitUntil(
    caches.keys().then(keys => {
      console.log('[SW] Old caches:', keys);
      return Promise.all(
        keys
          .filter(key => key !== CACHE_NAME && key.startsWith('carros-pro'))
          .map(key => {
            console.log('[SW] Deleting old cache:', key);
            return caches.delete(key);
          })
      );
    })
  );
  self.clients.claim();
});

self.addEventListener('fetch', event => {
  const { request } = event;
  
  if (request.method !== 'GET') {
    return;
  }

  const isNavigationRequest = request.mode === 'navigate';
  const isExternal = !request.url.includes(self.location.origin);

  if (isExternal && isNavigationRequest) {
    return;
  }

  if (isNavigationRequest) {
    event.respondWith(
      fetch(request)
        .then(response => {
          if (!response || response.status !== 200 || response.type === 'error') {
            return response;
          }
          
          const clone = response.clone();
          caches.open(CACHE_NAME).then(cache => {
            cache.put(request, clone);
          });
          return response;
        })
        .catch(() => {
          return caches.match('/index.html') 
            .then(response => response || new Response('No disponible offline', { status: 503 }));
        })
    );
    return;
  }

  // Para recursos (css, js, etc)
  event.respondWith(
    caches.match(request).then(cached => {
      if (cached) {
        // En background, intenta actualizar
        fetch(request)
          .then(response => {
            if (response && response.status === 200) {
              caches.open(CACHE_NAME).then(cache => {
                cache.put(request, response);
              });
            }
          })
          .catch(() => {});
        
        return cached;
      }

      return fetch(request)
        .then(response => {
          if (!response || response.status !== 200) {
            return response;
          }

          const clone = response.clone();
          caches.open(CACHE_NAME).then(cache => {
            cache.put(request, clone);
          });
          return response;
        })
        .catch(() => {
          // Fallback offline
          if (request.destination === 'document') {
            return caches.match('/index.html');
          }
          return new Response('Recurso no disponible', { status: 404 });
        });
    })
  );
});

// Detectar actualizaciones
self.addEventListener('message', event => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});