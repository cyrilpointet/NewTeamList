import { initializeApp } from "firebase/app";
import {
    getMessaging,
    getToken,
    onMessage,
    Messaging,
} from "firebase/messaging";
import { ApiConsumer } from "@/services/ApiConsumer";

export class FirebaseManager {
    static messaging?: Messaging;

    public static async start() {
        const firebaseConfig = {
            apiKey: "AIzaSyCmquMEn8N7vRFUbYQoOc-CCoIQ1uPFNmE",
            authDomain: "newteamlist.firebaseapp.com",
            projectId: "newteamlist",
            storageBucket: "newteamlist.appspot.com",
            messagingSenderId: "33752955863",
            appId: "1:33752955863:web:f3a260bc9cfacc9bede992",
        };

        const firebaseApp = initializeApp(firebaseConfig);
        FirebaseManager.messaging = getMessaging(firebaseApp);
    }

    public static async saveFcmToken() {
        if (!FirebaseManager.messaging) return;
        const fcmToken = await getToken(FirebaseManager.messaging, {
            // todo hide key
            vapidKey:
                "BLoWMmusb8bpzIZ9YJ37Jw9K_aTc-iy_ZMoAnHbjjYR01XAsP-uAIygCGuoyFn18gDnyb8E2mV4kMC1ZBKMHFdU",
        });
        console.log(fcmToken);
        await ApiConsumer.post("user/store_device_key", {
            deviceKey: fcmToken,
        });
    }

    public static async clearFcmToken() {
        await ApiConsumer.post("user/store_device_key", {
            deviceKey: null,
        });
    }

    public static listenMessage(callback?: () => void) {
        onMessage(FirebaseManager.messaging, (payload) => {
            const title = payload.data.title;
            const options = {
                body: payload.data.body,
            };
            new Notification(title, options);
            callback?.();
        });
    }
}
