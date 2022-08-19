<script setup lang="ts">
import { RouterView } from "vue-router";
import { ref } from "vue";
import Header from "@/components/common/Header.vue";
import { eventBus } from "@/services/eventBus";
import type { SnackbarProps } from "@/components/common/Snackbar.vue";
import bgMobile from "@/assets/images/backgroundImage.png";

const snackbarValues = ref<SnackbarProps | null>(null);

eventBus.$on("show-snackbar", (values: SnackbarProps) => {
    snackbarValues.value = values;
    setTimeout(() => {
        snackbarValues.value = null;
    }, snackbarValues.value.values?.delay || 3000);
});
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
</template>
