<script setup lang="ts">
const props = defineProps<{
    value?: number;
}>();
</script>

<template>
    <div v-if="value !== undefined" class="relative">
        <slot />
        <div
            v-if="value !== undefined"
            class="text-xs absolute py-1 px-2 top-0 right-0 rounded-full bg-error text-primary-contrast -translate-y-1/4 translate-x-1/4"
        >
            {{ value }}
        </div>
    </div>
    <template v-else>
        <slot />
    </template>
</template>
