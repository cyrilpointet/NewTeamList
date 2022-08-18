<script setup lang="ts">
import { useUserStore } from "@/stores/user";
import router from "@/router";
import UserInvitations from "@/components/user/UserInvitations.vue";

const userStore = useUserStore();

function logout() {
    router.push("/");
    userStore.logout();
}
</script>

<template>
    <div v-if="userStore.isLogged" class="container mx-auto px-4 md:px-0">
        <div class="mt-6 flex justify-center">
            <Card>
                <h1 class="text-3xl font-bold text-center">
                    {{ userStore.user.name }}
                </h1>
            </Card>
        </div>

        <div class="mt-6 grid gap-4 grid-cols-1 lg:grid-cols-2">
            <div v-if="userStore.hasInvitations">
                <Accordion
                    title="Mes invitations en attente"
                    :number="userStore.user.invitations.length"
                >
                    <UserInvitations />
                </Accordion>
            </div>

            <div>
                <Accordion title="Déconnexion">
                    <div class="p-3 flex justify-center">
                        <Button icon="logout" @click="logout"
                            >Déconnexion</Button
                        >
                    </div>
                </Accordion>
            </div>
        </div>
    </div>
</template>
