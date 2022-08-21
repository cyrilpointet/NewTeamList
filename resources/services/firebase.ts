import { initializeApp } from "firebase/app";
import { getMessaging, getToken, onMessage } from "firebase/messaging";
import { ApiConsumer } from "@/services/ApiConsumer";

const firebaseConfig = {
    apiKey: "AIzaSyCmquMEn8N7vRFUbYQoOc-CCoIQ1uPFNmE",
    authDomain: "newteamlist.firebaseapp.com",
    projectId: "newteamlist",
    storageBucket: "newteamlist.appspot.com",
    messagingSenderId: "33752955863",
    appId: "1:33752955863:web:f3a260bc9cfacc9bede992",
};

const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

export const startFcm = async () => {
    console.log("start");
    if (!localStorage.getItem("fcmToken")) {
        console.log("!fcmToken");
        const permission = await Notification.requestPermission();
        if (permission !== "granted") return;
        try {
            const fcmToken = await getToken(messaging, {
                vapidKey:
                    "BLoWMmusb8bpzIZ9YJ37Jw9K_aTc-iy_ZMoAnHbjjYR01XAsP-uAIygCGuoyFn18gDnyb8E2mV4kMC1ZBKMHFdU",
            });
            await ApiConsumer.post("user/store_device_key", {
                deviceKey: fcmToken,
            });
            localStorage.setItem("fcmToken", fcmToken);
        } catch (e) {
            throw e;
        }
    }
    listenFcm();
};

export const listenFcm = () => {
    console.log("listenFcm");
    onMessage(messaging, (payload) => {
        console.log("Message received. ", payload);

        const title = payload.notification.title;
        const options = {
            body: payload.notification.body,
        };
        new Notification(title, options);
    });
};
