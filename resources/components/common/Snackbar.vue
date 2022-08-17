<script setup lang="ts">
import { computed } from "vue";

export type SnackbarProps = {
    values: { text: string; type?: string; delay?: number } | null;
};

const props = defineProps<SnackbarProps>();

const classnames = computed(() => {
    switch (props.values?.type) {
        case "error":
            return "bg-error text-primary-contrast";
        default:
            return "bg-primary text-primary-contrast";
    }
});
</script>

<template>
    <div class="fixed bottom-12 left-0 right-0 flex justify-center">
        <Transition name="slide-up">
            <div
                v-if="props.values"
                class="p-4 rounded shadow-xl"
                :class="classnames"
                style="max-width: 90%"
            >
                <p>{{ props.values?.text }}</p>
            </div>
        </Transition>
    </div>
</template>
