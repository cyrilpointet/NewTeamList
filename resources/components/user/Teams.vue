<script setup lang="ts">
import { useUserStore } from "@/stores/user";
import router from "@/router";

const userStore = useUserStore();

function goToTeamPage(teamId: string) {
    router.push(`/team/${teamId}`);
}
</script>

<template>
    <div>
        <div v-if="userStore.user.teams.length < 1" class="p-4">
            Vous n'avez pas liste en cours
        </div>
        <div
            v-for="team in userStore.user.teams"
            :key="team.id"
            class="p-4 flex justify-between items-center cursor-pointer hover:bg-gray-100 hover:shadow transition border-b last:border-0"
            @click="goToTeamPage(team.id)"
        >
            <p class="font-bold flex-1">{{ team.name }}</p>
            <Icon type="chevron_right" />
        </div>
    </div>
</template>
