<script setup lang="ts">
import { ref } from "vue";
import router from "@/router";
import { useTeamStore } from "@/stores/team";
import { eventBus } from "@/services/eventBus";
import { errorHelper } from "@/helpers/errorHelper";
import TeamEdit from "@/components/team/TeamEdit.vue";
import TeamDelete from "@/components/team/TeamDelete.vue";
import TeamMembers from "@/components/team/TeamMembers.vue";

const teamStore = useTeamStore();

const showSetting = ref(false);

const isModalOpen = ref(false);
function closeModale() {
    isModalOpen.value = false;
}
const loading = ref(false);

async function leaveTeam() {
    loading.value = true;
    if (!teamStore.userMembership?.id) return;
    try {
        await teamStore.leaveTeam();
        router.push("/");
    } catch (e) {
        eventBus.$emit("show-snackbar", {
            text: errorHelper.formatMessage(e),
            type: "error",
        });
    }
}
</script>

<template>
    <div
        class="container mx-auto pt-6 px-4 md:px-0 flex-1 flex flex-col gap-6 overflow-hidden"
    >
        <div
            class="mx-auto flex items-center justify-center gap-4 bg-white rounded shadow py-4 px-6 max-w-full overflow-hidden"
        >
            <h1
                class="overflow-hidden whitespace-nowrap text-ellipsis text-center text-2xl font-bold"
            >
                {{ teamStore.team?.name }}
            </h1>

            <Badge :value="undefined">
                <ButtonIcon
                    size="sm"
                    class="bg-primary text-primary-contrast shadow"
                    :icon="showSetting ? 'list' : 'edit'"
                    @click="showSetting = !showSetting"
                />
            </Badge>
        </div>

        <Transition name="turn" mode="out-in">
            <div v-if="!showSetting">
                <Card>
                    <p>coucou</p>
                </Card>
            </div>

            <div
                v-else-if="teamStore.team"
                class="grid gap-4 grid-cols-1 lg:grid-cols-2"
            >
                <div v-if="teamStore.isUserManager">
                    <Accordion title="Demande d'adhésion en cours" :number="0">
                        <div class="p-3">
                            <p>TeamInvitations</p>
                        </div>
                    </Accordion>
                </div>
                <div v-if="teamStore.isUserManager">
                    <Accordion title="Partager">
                        <div class="p-3">
                            <p>FindMember</p>
                        </div>
                    </Accordion>
                </div>

                <div>
                    <Accordion
                        :title="
                            teamStore.isUserManager
                                ? 'Gérer les partages'
                                : 'Liste de membres'
                        "
                    >
                        <div class="p-3">
                            <TeamMembers />
                        </div>
                    </Accordion>
                </div>

                <div v-if="teamStore.isUserManager">
                    <Accordion title="Renommer">
                        <div class="p-3">
                            <TeamEdit />
                        </div>
                    </Accordion>
                </div>

                <div v-if="teamStore.isUserManager">
                    <Accordion title="Supprimer la liste">
                        <div class="p-3">
                            <TeamDelete />
                        </div>
                    </Accordion>
                </div>

                <div v-if="!teamStore.isUserManager">
                    <Accordion title="Quitter la liste">
                        <div class="p-3 flex justify-center">
                            <Button @click="isModalOpen = true"
                                >Quitter la liste ?</Button
                            >
                        </div>
                    </Accordion>
                </div>
            </div>
        </Transition>
    </div>

    <Modal :on-close="closeModale" :is-open="isModalOpen">
        <div class="w-56">
            <p>Ne plus suivre cette liste ?</p>
            <div class="mt-6 flex justify-between">
                <Button @click="closeModale">Annuler</Button>
                <Button @click="leaveTeam">Valider</Button>
            </div>
        </div>
    </Modal>
</template>
