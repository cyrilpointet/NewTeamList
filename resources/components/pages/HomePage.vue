<script setup lang="ts">
import { ref, onBeforeMount } from "vue";
import { useUserStore } from "@/stores/user";
import Login from "@/components/user/Login.vue";
import Register from "@/components/user/Register.vue";
import Teams from "@/components/user/Teams.vue";
import TeamCreate from "@/components/team/TeamCreate.vue";
import TeamFinder from "@/components/team/TeamFinder.vue";

const userStore = useUserStore();
const hasAccount = ref(false);

onBeforeMount(async () => {
    await userStore.autoLogin();
});
</script>

<template>
    <div class="container mx-auto">
        <div
            v-if="!userStore.isLogged"
            class="flex justify-center px-4 md:px-0"
        >
            <Card class="mt-6 flex-1 max-w-md">
                <Login v-if="hasAccount" />
                <Register v-else />
                <div class="flex justify-center mt-6">
                    <button class="font-bold" @click="hasAccount = !hasAccount">
                        {{
                            hasAccount
                                ? "Créer un compte"
                                : "J'ai déjà un compte"
                        }}
                    </button>
                </div>
            </Card>
        </div>

        <div v-else>
            <Card class="mt-6">
                <Teams class="-m-4" />
            </Card>
            <Card class="mt-4">
                <TeamCreate />
            </Card>
            <Card class="mt-4">
                <TeamFinder />
            </Card>
        </div>
    </div>
</template>
