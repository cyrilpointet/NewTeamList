importScripts("https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js");
importScripts(
    "https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js"
);

const PREFIX = "V1";

self.addEventListener("install", (event) => {
    self.skipWaiting();
    event.waitUntill(
        (async () => {
            const cache = await caches.open(PREFIX);
            cache.add(new Request("offline.html"));
        })()
    );
});

self.addEventListener("activate", () => {
    clients.claim();
});

self.addEventListener("fetch", (event) => {
    if (event.request.mode === "navigate") {
        event.respondWith(
            (async () => {
                try {
                    const preloadresponse = await event.preloadResponse;
                    if (preloadresponse) {
                        return preloadresponse;
                    }
                    return await fetch(event.request);
                } catch (e) {
                    const cache = await caches.open(PREFIX);
                    return await cache.match("/offline.html");
                }
            })()
        );
    }
});

// self.addEventListener("push", async function (e) {
//     if (!(self.Notification && self.Notification.permission === "granted")) {
//         //notifications aren't supported or permission not granted!
//         return;
//     }
//
//     if (e.data) {
//         const msg = e.data.json();
//         e.waitUntil(
//             self.registration.showNotification(msg.title, {
//                 body: "msg.body",
//                 // icon: msg.icon,
//                 // actions: msg.actions
//             })
//         );
//     }
// });
