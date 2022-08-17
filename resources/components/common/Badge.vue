<script setup lang="ts">
const props = defineProps<{
    value?: number;
}>();
</script>

<template>
    <div class="relative">
        <slot />
        <div
            v-if="value !== undefined"
            class="text-xs absolute py-1 px-2 top-0 right-0 rounded-full bg-error text-primary-contrast -translate-y-1/2 translate-x-1/2"
        >
            {{ value }}
        </div>
    </div>
</template>
