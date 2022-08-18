<script setup lang="ts">
import { ref } from "vue";
import { helpers, required } from "@vuelidate/validators";
import { ERROR_MSG } from "@/constantes/errorMsg";
import useVuelidate from "@vuelidate/core";
import { ApiConsumer } from "@/services/ApiConsumer";
import type { Team } from "@/stores/storeTypes";
import { eventBus } from "@/services/eventBus";
import { errorHelper } from "@/helpers/errorHelper";
import { useUserStore } from "@/stores/user";

const userStore = useUserStore();

const state = ref({
    name: "",
});
const teams = ref<Team[] | null>(null);
const loading = ref(false);

const rules = {
    name: {
        required: helpers.withMessage(ERROR_MSG.required, required),
    },
};
const v$ = useVuelidate(rules, state);

function isMember(team: Team) {
    return userStore.user?.teams.find((elem) => elem.id === team.id);
}

async function submit() {
    if (v$.value.$invalid) return;
    teams.value = null;
    loading.value = true;
    try {
        const allTeams = await ApiConsumer.get(`team?name=${state.value.name}`);
        teams.value = allTeams as Team[];
    } catch (e) {
        eventBus.$emit("show-snackbar", {
            text: errorHelper.formatMessage(e),
            type: "error",
        });
    }
    loading.value = false;
}

async function join(team: Team) {
    loading.value = true;
    try {
        await ApiConsumer.post("user/invitation", {
            id: team.id,
        });
        eventBus.$emit("show-snackbar", {
            text: `Une demande a été envoyée pour rejoindre ${team.name}`,
        });
        teams.value = null;
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
    <div class="flex items-center gap-4">
        <Input
            v-model:value="state.name"
            class="flex-1"
            label="Chercher une liste partagée"
            :error="v$.name.$errors[0]?.$message"
            @input="teams = null"
            @blur="v$.name.$touch()"
            @keyup.enter="submit"
        />
        <div class="flex justify-center mt-4">
            <ButtonIcon
                class="bg-primary text-primary-contrast"
                :disabled="v$.$invalid || loading"
                icon="search"
                @click="submit"
            />
        </div>
    </div>
    <div v-if="teams !== null" class="mt-4">
        <div v-if="teams.length === 0">
            <p class="flex-1">Aucune liste trouvée avec ce nom</p>
        </div>
        <div
            v-for="team in teams"
            :key="team.id"
            class="p-4 flex justify-between items-center border-gray-300 border-solid border-b last:border-0"
        >
            <p class="font-bold flex-1">{{ team.name }}</p>
            <ButtonIcon
                v-if="!isMember(team)"
                class="bg-primary text-primary-contrast"
                icon="person_add"
                :loading="loading"
                @click="join(team)"
            />
        </div>
    </div>
</template>
