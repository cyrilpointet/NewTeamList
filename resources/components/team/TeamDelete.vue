<script setup lang="ts">
import { ref } from "vue";
import { eventBus } from "@/services/eventBus";
import { errorHelper } from "@/helpers/errorHelper";
import router from "@/router";
import { useTeamStore } from "@/stores/team";

const teamStore = useTeamStore();
const isModalOpen = ref<boolean>(false);
const loading = ref(false);

async function deleteTeam() {
    if (!teamStore.team) return;

    loading.value = true;
    try {
        await teamStore.deleteTeam();
        eventBus.$emit("show-snackbar", {
            text: "Liste supprimée",
        });
        router.push({ name: "home" });
    } catch (e) {
        eventBus.$emit("show-snackbar", {
            text: errorHelper.formatMessage(e),
            type: "error",
        });
    }
    loading.value = false;
}

function closeModale() {
    isModalOpen.value = false;
}
</script>

<template>
    <div class="flex justify-center">
        <Button @click.prevent="isModalOpen = true">Supprimer</Button>
    </div>

    <Modal :on-close="closeModale" :is-open="isModalOpen">
        <div class="w-56">
            <p>Supprimer la liste ?</p>
            <div class="mt-6 flex justify-between">
                <Button @click="closeModale">Annuler</Button>
                <Button @click="deleteTeam">Valider</Button>
            </div>
        </div>
    </Modal>
</template>
