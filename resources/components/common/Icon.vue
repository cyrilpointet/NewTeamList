<script setup lang="ts">
const props = defineProps({
    type: String,
});
</script>

<template>
    <i class="material-icons leading-none">{{ props.type }}</i>
</template>
