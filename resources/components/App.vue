<script setup lang="ts">
import { RouterView } from "vue-router";
import { ref } from "vue";
import Header from "@/components/common/Header.vue";
import { eventBus } from "@/services/eventBus";
import type { SnackbarProps } from "@/components/common/Snackbar.vue";
import bgMobile from "@/assets/images/backgroundImage.png";
import { startFcm } from "@/services/firebase";

const snackbarValues = ref<SnackbarProps | null>(null);
const deferredPrompt = ref<Event | null>(null);

eventBus.$on("show-snackbar", (values: SnackbarProps) => {
    snackbarValues.value = values;
    setTimeout(() => {
        snackbarValues.value = null;
    }, snackbarValues.value.values?.delay || 3000);
});

if ("serviceWorker" in navigator) {
    window.addEventListener("load", function () {
        navigator.serviceWorker.register("/sw.js").then(async () => {
            startFcm();
        });
    });
}

window.addEventListener("beforeinstallprompt", (e) => {
    // Prevent Chrome 67 and earlier from automatically showing the prompt
    e.preventDefault();
    // If in mobile mode, stash the event so it can be triggered later.
    if (window.innerHeight > window.innerWidth) {
        deferredPrompt.value = e;
    }
});

const installPwa = () => {
    if (!deferredPrompt.value) return;
    deferredPrompt.value.prompt(); // eslint-disable-line
    // Wait for the user to respond to the prompt
    deferredPrompt.value.userChoice.then((choiceResult) => { // eslint-disable-line
        if (deferredPrompt.value) {
            eventBus.$emit("show-snackbar", {
                text: "TeamList a été installé sur cet appareil",
            });
        }
        deferredPrompt.value = null;
    });
};

const declineInstallPwa = () => {
    deferredPrompt.value = null;
};
</script>

<template>
    <div class="fixed inset-0 overflow-hidden flex flex-col">
        <Header />
        <div
            class="flex-1 bg-gray-100 overflow-y-auto relative bg-center bg-no-repeat bg-cover"
            :style="{ backgroundImage: `url(${bgMobile})` }"
        >
            <router-view v-slot="{ Component, route }">
                <Transition name="scale-slide">
                    <div :key="route.name">
                        <component :is="Component"></component>
                    </div>
                </Transition>
            </router-view>
        </div>
    </div>
    <Snackbar :values="snackbarValues" />

    <Modal :on-close="declineInstallPwa" :is-open="deferredPrompt">
        <div class="w-64">
            <p class="text-center font-bold">
                Voulez-vous installer l'application TeamList sur cet appareil ?
            </p>
            <p class="text-center italic">(Recommandé)</p>
            <div class="mt-6 flex justify-between">
                <Button @click="declineInstallPwa">Plus tard</Button>
                <Button @click="installPwa">Installer</Button>
            </div>
        </div>
    </Modal>
</template>
