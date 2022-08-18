<script setup lang="ts">
import { useUserStore } from "@/stores/user";
import { ref } from "vue";
import type { UserInvitation } from "@/stores/storeTypes";
import { ApiConsumer } from "@/services/ApiConsumer";
import { eventBus } from "@/services/eventBus";
import { errorHelper } from "@/helpers/errorHelper";
import router from "@/router";

const userStore = useUserStore();

const loading = ref(false);

async function manageInvitation(invitation: UserInvitation, status: boolean) {
    loading.value = true;
    try {
        await ApiConsumer.put(`user/invitation/${invitation.id}`, {
            status,
        });
        await userStore.refreshUser();
        if (status) {
            router.push(`/team/${invitation.team.id}`);
        }
    } catch (e) {
        eventBus.$emit("show-snackbar", {
            text: errorHelper.formatMessage(e),
            type: "error",
        });
        loading.value = false;
    }
}
</script>

<template>
    <div
        v-for="invitation in userStore.user.invitations"
        :key="invitation.id"
        class="p-4 flex justify-between items-center gap-4 cursor-pointer transition border-b last:border-0"
    >
        <p class="flex-1">{{ invitation.team.name }}</p>
        <ButtonIcon
            class="bg-primary text-primary-contrast"
            icon="person_add"
            :loading="loading"
            @click="manageInvitation(invitation, true)"
        />
        <ButtonIcon
            class="bg-primary text-primary-contrast"
            icon="delete"
            :loading="loading"
            @click="manageInvitation(invitation, false)"
        />
    </div>
</template>
