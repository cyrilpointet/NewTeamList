<script setup lang="ts">
import { computed } from "vue";

const props = defineProps<{
    disabled?: boolean;
    icon?: string;
}>();

const classnames = computed((): string =>
    props.disabled
        ? "bg-gray-400 !cursor-default text-gray-600"
        : "bg-primary text-primary-contrast hover:bg-primary-light"
);
</script>

<template>
    <button
        class="px-4 py-2 rounded-lg transition flex items-center gap-2 overflow-hidden relative"
        :class="classnames"
        :disabled="disabled"
    >
        <slot></slot>
        <Icon v-if="icon" :type="icon" />
    </button>
</template>
