<script setup lang="ts">
const props = defineProps<{
    value: boolean;
    disabled?: boolean;
}>();
</script>

<template>
    <label class="switch relative inline-block w-14 h-8">
        <input
            :checked="props.value"
            :disabled="disabled"
            type="checkbox"
            class="opacity-0 h-0 w-0"
            @change="$emit('update:value', $event.target.checked)"
        />
        <span
            class="slider round absolute inset-0 rounded-full cursor-pointer before:absolute before:w-6 before:h-6 before:bottom-1 before:left-1 before:bg-white"
        ></span>
    </label>
</template>

<style scoped>
/* The slider */
.slider {
    background-color: #ccc;
    -webkit-transition: 0.4s;
    transition: 0.4s;
}

.slider:before {
    content: "";
    -webkit-transition: 0.4s;
    transition: 0.4s;
}

input:focus + .slider {
    box-shadow: 0 0 1px #2196f3;
}

input:checked + .slider:before {
    transform: translateX(24px);
}

input:checked + .slider {
    background-color: var(--color-primary);
}

input:disabled + .slider {
    background-color: #e5e5e5;
}

input:disabled + .slider:before {
    background-color: #c4c4c4;
}

.slider.round:before {
    border-radius: 50%;
}
</style>
