<script setup lang="ts">
import { useTeamStore } from "@/stores/team";
import { ref } from "vue";
import { helpers, required } from "@vuelidate/validators";
import { ERROR_MSG } from "@/constantes/errorMsg";
import useVuelidate from "@vuelidate/core";
import { errorHelper } from "@/helpers/errorHelper";
import { eventBus } from "@/services/eventBus";

const teamStore = useTeamStore();

const loading = ref(false);

const state = ref({
    name: teamStore.team?.name || "",
});
const rules = {
    name: {
        required: helpers.withMessage(ERROR_MSG.required, required),
    },
};
const v$ = useVuelidate(rules, state);

async function submit(): Promise<void> {
    if (!teamStore.team) return;

    loading.value = true;
    try {
        await teamStore.updateTeam(state.value);
        eventBus.$emit("show-snackbar", {
            text: "Liste renommée",
        });
    } catch (e) {
        eventBus.$emit("show-snackbar", {
            text: errorHelper.formatMessage(e),
            type: "error",
        });
    }
    loading.value = false;
}
</script>

<template>
    <div class="flex items-end gap-4">
        <Input
            v-model:value="state.name"
            class="flex-1"
            label="Nouveau nom"
            :error="v$.name.$errors[0]?.$message"
            @blur="v$.name.$touch()"
        />
        <ButtonIcon
            class="bg-primary text-primary-contrast"
            :disabled="v$.$invalid || loading"
            icon="done"
            size="sm"
            @click="submit"
        />
    </div>
</template>
