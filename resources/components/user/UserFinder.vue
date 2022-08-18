<script setup lang="ts">
import { ref, watch } from "vue";
import { helpers, required } from "@vuelidate/validators";
import { ERROR_MSG } from "@/constantes/errorMsg";
import useVuelidate from "@vuelidate/core";
import { ApiConsumer } from "@/services/ApiConsumer";
import { errorHelper } from "@/helpers/errorHelper";
import type { User } from "@/stores/storeTypes";
import { eventBus } from "@/services/eventBus";
import { useTeamStore } from "@/stores/team";

const loading = ref(false);
const users = ref<null | User[]>(null);

const teamStore = useTeamStore();

const state = ref({
    name: "",
});
const rules = {
    name: {
        required: helpers.withMessage(ERROR_MSG.required, required),
    },
};
const v$ = useVuelidate(rules, state);

const userToAdd = ref<null | User>(null);

function belongsToTeam(user: User) {
    return !!teamStore.team?.members.find((elem) => elem.id === user.id);
}

function isEmail(text: string) {
    const emailRegex = /^[a-zA-Z0-9_.-]+@[a-zA-Z0-9_-]+\.[a-zA-Z0-9_]{2,4}$/;
    return emailRegex.test(text);
}

function closeModal() {
    userToAdd.value = null;
}

watch(state.value, () => {
    users.value = null;
});

async function submit() {
    if (v$.value.$invalid) return;
    loading.value = true;
    users.value = null;
    try {
        const usersFound = (await ApiConsumer.get(
            `user/name/${state.value.name}`
        )) as User[];
        users.value = usersFound;
        console.log(usersFound);
        eventBus.$emit("update-accordion");
    } catch (e) {
        eventBus.$emit("show-snackbar", {
            text: errorHelper.formatMessage(e),
            type: "error",
        });
    }
    loading.value = false;
}

async function inviteUser(email: string) {
    if (!teamStore.team) return;
    loading.value = true;
    try {
        await ApiConsumer.post(`team/${teamStore.team.id}/invitation`, {
            email: email,
        });
        eventBus.$emit("update-accordion");
        eventBus.$emit("show-snackbar", {
            text: `Une invitation a été envoyée à ${
                userToAdd.value?.name ? userToAdd.value.name : email
            }`,
        });
    } catch (e) {
        eventBus.$emit("show-snackbar", {
            text: errorHelper.formatMessage(e),
            type: "error",
        });
    }
    closeModal();
    loading.value = false;
}
</script>

<template>
    <div class="flex items-center gap-4">
        <Input
            v-model:value="state.name"
            label="Chercher un membre"
            class="flex-1"
            placeholder="Nom ou email"
            @blur="v$.name.$touch()"
            @keyup.enter="submit"
        />
        <ButtonIcon
            class="bg-primary text-primary-contrast"
            :disabled="v$.$invalid || loading"
            icon="search"
            @click="submit"
        />
    </div>
    <div v-if="users !== null" class="mt-4">
        <div v-if="users.length === 0" class="px-4 py-2">
            <p class="flex-1">Aucun membre trouvé</p>
            <div v-if="isEmail(state.name)">
                <p>
                    Voulez-vous envoyer un email d'invitation à
                    <strong>{{ state.name }}</strong> ?
                </p>
            </div>
        </div>
        <div
            v-for="user in users"
            :key="user.id"
            class="px-4 py-2 flex items-center border-b last:border-0"
        >
            <p class="flex-1">
                {{ user.name }}
            </p>
            <ButtonIcon
                v-if="!belongsToTeam(user)"
                class="bg-primary text-primary-contrast"
                :disabled="loading"
                icon="person_add"
                @click="userToAdd = user"
            />
        </div>
    </div>

    <Modal :on-close="closeModal" :is-open="userToAdd">
        <div class="w-64">
            <p>Inviter {{ userToAdd?.name }} à partager cette liste ?</p>
            <div class="mt-6 flex justify-between">
                <Button @click="closeModal">Annuler</Button>
                <Button @click="inviteUser(userToAdd?.email)">Valider</Button>
            </div>
        </div>
    </Modal>
</template>
