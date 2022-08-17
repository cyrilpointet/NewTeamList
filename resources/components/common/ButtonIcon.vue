<script setup lang="ts">
import { computed } from "vue";

const props = defineProps<{
    disabled?: boolean;
    icon: string;
    size?: "xs" | "sm" | "base" | "lg" | "xl";
    loading?: boolean;
}>();

const buttonClassnames = computed((): string =>
    props.disabled ? "!text-gray-400 !bg-gray-200" : ""
);
const iconClassnames = computed(
    (): string =>
        `${props.loading ? "animate-spin" : ""} ${
            props.size ? `text-${props.size}` : ""
        }`
);
</script>

<template>
    <button
        class="flex items-center justify-center rounded-full p-2 hover:shadow-lg transition aspect-square"
        :class="buttonClassnames"
        :disabled="disabled || loading"
    >
        <Icon :type="loading ? 'autorenew' : icon" :class="iconClassnames" />
    </button>
</template>
