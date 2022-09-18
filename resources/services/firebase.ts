import { initializeApp } from "firebase/app";
import { getMessaging, getToken, onMessage } from "firebase/messaging";
import { ApiConsumer } from "@/services/ApiConsumer";

export const startFcm = async () => {
    const firebaseConfig = {
        apiKey: "AIzaSyCmquMEn8N7vRFUbYQoOc-CCoIQ1uPFNmE",
        authDomain: "newteamlist.firebaseapp.com",
        projectId: "newteamlist",
        storageBucket: "newteamlist.appspot.com",
        messagingSenderId: "33752955863",
        appId: "1:33752955863:web:f3a260bc9cfacc9bede992",
    };

    const firebaseApp = initializeApp(firebaseConfig);
    const messaging = getMessaging(firebaseApp);

    const fcmToken = await getToken(messaging, {
        vapidKey:
            "BLoWMmusb8bpzIZ9YJ37Jw9K_aTc-iy_ZMoAnHbjjYR01XAsP-uAIygCGuoyFn18gDnyb8E2mV4kMC1ZBKMHFdU",
    });
    console.log(fcmToken);
    await ApiConsumer.post("user/store_device_key", {
        deviceKey: fcmToken,
    });

    onMessage(messaging, (payload) => {
        const title = payload.notification.title;
        const options = {
            body: payload.notification.body,
        };
        new Notification(title, options);
    });
};
