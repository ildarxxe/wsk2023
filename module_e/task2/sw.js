const CACHE_NAME = 'NAME-V1';
const cache_urls = [
    "./",
    "./index.html",
    "./style.css",
    "./sw.js",
    "./script.js",
    "./manifest.json",
    "https://placehold.co/192x192/000000/FFFFFF?text=AI",
    "https://placehold.co/512x512/000000/FFFFFF?text=AI"
]

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            console.log("SW: Caching app")
            return cache.addAll(cache_urls);
        }).catch(error => {
            console.error(error);
        })
    )

    self.skipWaiting();
})

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then(cacheKeys => {
            return Promise.all(
                cacheKeys.map(key => {
                    if (key !== CACHE_NAME) {
                        console.log("deleting oldest cache")
                        return caches.delete(key);
                    }
                })
            )
        })
    )

    self.clients.claim()
})

self.addEventListener('fetch', (event) => {
    const cachedResponse = caches.match(event.request);

    if (cachedResponse) {
        event.respondWith(cachedResponse);
        return;
    }

    if (cache_urls.some(url => event.request.url.includes(url)) || event.request.destination === "image") {
        event.respondWith(
            caches.match(event.request)
                .then(response => response || fetch(event.request))
                .catch(error => {
                    return new Response(null, {status: 503, message: "Offline"})
                })
        )
        return;
    }

    event.respondWith(
        caches.open(CACHE_NAME).then((cache) => {
            return fetch(event.request)
                .then(response => {
                    if (response.ok) {
                        cache.put(event.request, response.clone());
                    }
                    return response;
                })
                .catch(error => {
                    console.error(error);
                    return cache.match(event.request)
                })
        })
    )

    event.respondWith(fetch(event.request))
})