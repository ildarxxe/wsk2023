const CACHE_NAME = 'cache-v1';
const urls_to_cache = [
    "/",
    "/index.html",
    "/index.js",
    "/style.css",
    "/manifest.json",
    "/images/app-icon.png",
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            console.log('Cache opened, resources added');
            return cache.addAll(urls_to_cache).catch(err => {
                console.error('Failed to add some resources to cache:', err);
            });
        })
    );
});

self.addEventListener('fetch', event => {
    if (event.request.url.includes('/api/news')) {
        event.respondWith(
            fetch(event.request).then(response => {
                if (!response || response.status !== 200 || response.type !== 'basic') {
                    return response;
                }
                const responseClone = response.clone();
                caches.open(CACHE_NAME).then(cache => {
                    cache.put(event.request, responseClone);
                });
                return response;
            }).catch(() => {
                return caches.match(event.request);
            })
        );
    } else {
        event.respondWith(
            caches.match(event.request)
                .then(response => {
                    return response || fetch(event.request);
                })
        );
    }
});

self.addEventListener('activate', event => {
    const cacheWhitelist = [CACHE_NAME];
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(cacheNames.map(cacheName => {
                if (cacheWhitelist.indexOf(cacheName) === -1) {
                    return caches.delete(cacheName);
                }
            }));
        })
    );
});