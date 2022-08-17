<script setup lang="ts">
const props = defineProps<{
    isOpen: boolean;
    onClose: () => void;
    disableBgClick?: boolean;
}>();

function handleBgClick() {
    if (!props.disableBgClick) {
        props.onClose();
    }
}
</script>

<template>
    <Transition name="fade">
        <div
            v-if="isOpen"
            class="fixed inset-0 flex items-center justify-center"
            style="background-color: rgba(0, 0, 0, 0.5)"
            @click="handleBgClick"
        >
            <div
                class="bg-white p-4 relative shadow"
                style="min-width: 220px; max-width: 90vw; max-height: 90vh"
                @click.stop
            >
                <slot></slot>
            </div>
        </div>
    </Transition>
</template>
