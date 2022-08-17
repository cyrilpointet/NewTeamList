<script setup lang="ts">
import { RouterView } from "vue-router";
import Header from "@/components/common/Header.vue";
import { eventBus } from "@/services/eventBus";
import type { SnackbarProps } from "@/components/common/Snackbar.vue";
import { ref } from "vue";

const snackbarValues = ref<SnackbarProps | null>(null);

eventBus.$on("show-snackbar", (values: SnackbarProps) => {
    snackbarValues.value = values;
    setTimeout(() => {
        snackbarValues.value = null;
    }, snackbarValues.value.values?.delay || 3000);
});
</script>

<template>
    <div class="fixed inset-0 bg-gray-100 overflow-hidden flex flex-col">
        <Header />
        <suspense>
            <RouterView />
        </suspense>
    </div>
    <Snackbar :values="snackbarValues" />
</template>
