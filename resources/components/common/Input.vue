<script setup lang="ts">
import { computed } from "vue";

const props = defineProps<{
    label?: string;
    type?: string;
    placeholder?: string;
    value: string | number;
    error?: string;
}>();

const classname = computed(() =>
    props.error ? "border-b-error" : "focus:border-b-primary"
);
</script>

<template>
    <label class="block">
        <span v-if="props.label" class="block text-sm">{{ props.label }}</span>
        <input
            :type="props.type || 'text'"
            :placeholder="placeholder"
            :value="props.value"
            class="outline-0 border-b border-b-gray-400 transition w-full py-1"
            :class="classname"
            @input="$emit('update:value', $event.target.value)"
            @blur="$emit('blur')"
        />
        <span v-if="props.error" class="block text-sm text-error">{{
            props.error
        }}</span>
    </label>
</template>
