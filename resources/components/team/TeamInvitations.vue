<script setup lang="ts">
import { useUserStore } from "@/stores/user";
import { ref } from "vue";
import type { TeamInvitation } from "@/stores/storeTypes";
import { ApiConsumer } from "@/services/ApiConsumer";
import { eventBus } from "@/services/eventBus";
import { errorHelper } from "@/helpers/errorHelper";
import { useTeamStore } from "@/stores/team";

const userStore = useUserStore();
const teamStore = useTeamStore();

const loading = ref(false);

async function manageInvitation(invitation: TeamInvitation, status: boolean) {
    loading.value = true;
    try {
        await ApiConsumer.put(`team/${teamStore.team.id}/invitation`, {
            id: invitation.id,
            status,
        });
        eventBus.$emit("show-snackbar", {
            text: status
                ? `la demande de ${invitation.user.name} a bien été acceptée`
                : `la demande de ${invitation.user.name} a bien été rejetée`,
        });
        await teamStore.refreshTeam();
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
        v-for="invitation in teamStore.team.invitations"
        :key="invitation.id"
        class="p-4 flex justify-between items-center gap-4 border-gray-300 border-solid border-b last:border-0"
    >
        <p class="flex-1">{{ invitation.user.name }}</p>
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
    <div
        v-if="!teamStore.hasInvitations"
        class="p-4 flex justify-between items-center gap-4 cursor-pointer transition border-b last:border-0"
    >
        <p>Aucune demande en cours</p>
    </div>
</template>
