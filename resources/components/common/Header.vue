<script setup lang="ts">
import { RouterLink } from "vue-router";
import { useUserStore } from "@/stores/user";

const userStore = useUserStore();
</script>

<template>
    <header>
        <div class="bg-primary text-primary-contrast">
            <div
                class="container px-4 md:px-0 py-2 mx-auto flex items-center justify-between gap-2"
            >
                <RouterLink to="/">
                    <ButtonIcon icon="home" class="hover:bg-primary-light" />
                </RouterLink>
                <RouterLink v-if="userStore.isLogged" to="/account">
                    <Badge :value="0">
                        <ButtonIcon
                            icon="person"
                            class="hover:bg-primary-light"
                        />
                    </Badge>
                </RouterLink>
            </div>
        </div>
    </header>
</template>
