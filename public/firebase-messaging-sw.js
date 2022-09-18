importScripts("https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js");
importScripts(
    "https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js"
);

self.addEventListener("activate", () => {
    console.log("plop");
});

const firebaseConfig = {
    apiKey: "AIzaSyCmquMEn8N7vRFUbYQoOc-CCoIQ1uPFNmE",
    authDomain: "newteamlist.firebaseapp.com",
    projectId: "newteamlist",
    storageBucket: "newteamlist.appspot.com",
    messagingSenderId: "33752955863",
    appId: "1:33752955863:web:f3a260bc9cfacc9bede992",
};

firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

messaging.onBackgroundMessage((payload) => {
    console.log(
        "[firebase-messaging-sw.js] Received background message ",
        payload
    );
    // Customize notification here
    const notificationTitle = "firebase sw Title";
    const notificationOptions = {
        body: "firebase sw",
        icon: "/firebase-logo.png",
    };

    //self.registration.showNotification(notificationTitle, notificationOptions);
});
