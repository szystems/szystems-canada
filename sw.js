/**
 * Szystems Service Worker
 * Cache strategy para mejorar rendimiento
 */

const CACHE_NAME = 'szystems-v1.2';
const STATIC_CACHE = 'szystems-static-v1.2';
const DYNAMIC_CACHE = 'szystems-dynamic-v1.2';

// Recursos estáticos para cachear
const STATIC_ASSETS = [
    '/',
    '/index.html',
    '/paginasweb.html',
    '/appsweb.html',
    '/software.html',
    '/hostingydominio.html',
    '/contacto.html',
    '/assets/css/style.css',
    '/assets/css/optimizations.css',
    '/assets/js/main.js',
    '/assets/js/szystems.js',
    '/assets/img/logo.png',
    '/assets/img/logoblanco.png',
    '/assets/img/why-us.svg',
    '/assets/vendor/bootstrap/css/bootstrap.min.css',
    '/assets/vendor/bootstrap/js/bootstrap.bundle.min.js',
    '/assets/vendor/aos/aos.css',
    '/assets/vendor/aos/aos.js'
];

// Instalar Service Worker
self.addEventListener('install', event => {
    console.log('Service Worker: Installing...');
    
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then(cache => {
                console.log('Service Worker: Caching static assets');
                return cache.addAll(STATIC_ASSETS);
            })
            .then(() => {
                console.log('Service Worker: Static assets cached');
                return self.skipWaiting();
            })
            .catch(error => {
                console.error('Service Worker: Error caching static assets', error);
            })
    );
});

// Activar Service Worker
self.addEventListener('activate', event => {
    console.log('Service Worker: Activating...');
    
    event.waitUntil(
        caches.keys()
            .then(cacheNames => {
                return Promise.all(
                    cacheNames.map(cacheName => {
                        if (cacheName !== STATIC_CACHE && cacheName !== DYNAMIC_CACHE) {
                            console.log('Service Worker: Deleting old cache', cacheName);
                            return caches.delete(cacheName);
                        }
                    })
                );
            })
            .then(() => {
                console.log('Service Worker: Activated');
                return self.clients.claim();
            })
    );
});

// Interceptar requests
self.addEventListener('fetch', event => {
    const request = event.request;
    const url = new URL(request.url);
    
    // Ignorar requests no HTTP
    if (!request.url.startsWith('http')) return;
    
    // Ignorar requests de analytics y tracking
    if (url.hostname.includes('google-analytics.com') || 
        url.hostname.includes('googletagmanager.com') ||
        url.hostname.includes('facebook.net') ||
        url.hostname.includes('fontawesome.com')) {
        return;
    }
    
    event.respondWith(
        caches.match(request)
            .then(response => {
                // Retornar del cache si existe
                if (response) {
                    console.log('Service Worker: Serving from cache', request.url);
                    return response;
                }
                
                // Fetch desde la red
                return fetch(request)
                    .then(fetchResponse => {
                        // Verificar si es una respuesta válida
                        if (!fetchResponse || fetchResponse.status !== 200 || fetchResponse.type !== 'basic') {
                            return fetchResponse;
                        }
                        
                        // Clonar la respuesta
                        const responseToCache = fetchResponse.clone();
                        
                        // Determinar el cache apropiado
                        const cacheType = STATIC_ASSETS.includes(url.pathname) ? STATIC_CACHE : DYNAMIC_CACHE;
                        
                        // Cachear recursos dinámicos (imágenes, CSS, JS)
                        if (request.destination === 'image' || 
                            request.destination === 'script' || 
                            request.destination === 'style' ||
                            request.url.includes('.css') ||
                            request.url.includes('.js') ||
                            request.url.includes('.png') ||
                            request.url.includes('.jpg') ||
                            request.url.includes('.jpeg') ||
                            request.url.includes('.svg') ||
                            request.url.includes('.webp')) {
                            
                            caches.open(cacheType)
                                .then(cache => {
                                    cache.put(request, responseToCache);
                                })
                                .catch(error => {
                                    console.error('Service Worker: Error caching resource', error);
                                });
                        }
                        
                        return fetchResponse;
                    })
                    .catch(error => {
                        console.log('Service Worker: Fetch failed, trying cache', error);
                        
                        // Fallback para páginas HTML
                        if (request.destination === 'document') {
                            return caches.match('/index.html');
                        }
                        
                        // Fallback para imágenes
                        if (request.destination === 'image') {
                            return caches.match('/assets/img/logo.png');
                        }
                    });
            })
    );
});

// Manejar mensajes del cliente
self.addEventListener('message', event => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
    
    if (event.data && event.data.type === 'GET_VERSION') {
        event.ports[0].postMessage({ version: CACHE_NAME });
    }
});

// Sincronización en background
self.addEventListener('sync', event => {
    if (event.tag === 'background-sync') {
        console.log('Service Worker: Background sync triggered');
        event.waitUntil(
            // Aquí podrías manejar envío de formularios offline, etc.
            Promise.resolve()
        );
    }
});

// Notificaciones push (si se implementan en el futuro)
self.addEventListener('push', event => {
    console.log('Service Worker: Push message received');
    
    if (event.data) {
        const data = event.data.json();
        const options = {
            body: data.body || 'Nueva notificación de Szystems',
            icon: '/assets/img/logo.png',
            badge: '/assets/img/favicon.png',
            tag: 'szystems-notification',
            requireInteraction: true,
            actions: [
                {
                    action: 'view',
                    title: 'Ver sitio'
                },
                {
                    action: 'close',
                    title: 'Cerrar'
                }
            ]
        };
        
        event.waitUntil(
            self.registration.showNotification(data.title || 'Szystems', options)
        );
    }
});

// Manejar clicks en notificaciones
self.addEventListener('notificationclick', event => {
    event.notification.close();
    
    if (event.action === 'view') {
        event.waitUntil(
            clients.openWindow('/')
        );
    }
});
