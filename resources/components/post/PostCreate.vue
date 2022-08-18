<script setup lang="ts">
import { useTeamStore } from "@/stores/team";
import { ref } from "vue";
import { helpers, required } from "@vuelidate/validators";
import { ERROR_MSG } from "@/constantes/errorMsg";
import useVuelidate from "@vuelidate/core";
import { errorHelper } from "@/helpers/errorHelper";
import { eventBus } from "@/services/eventBus";

const teamStore = useTeamStore();

const state = ref({
    content: "",
});
const rules = {
    content: {
        required: helpers.withMessage(ERROR_MSG.required, required),
    },
};
const v$ = useVuelidate(rules, state);

async function submit(): Promise<void> {
    if (v$.value.$invalid) return;
    if (!teamStore.team) return;
    try {
        await teamStore.addPost(state.value.content);
        state.value.content = "";
    } catch (e) {
        eventBus.$emit("show-snackbar", {
            text: errorHelper.formatMessage(e),
            type: "error",
        });
    }
}
</script>

<template>
    <div class="flex items-center gap-4">
        <Input
            v-model:value="state.content"
            label="Ajouter un article"
            class="flex-1"
            @blur="v$.content.$touch()"
            @keyup.enter="submit"
        />
        <ButtonIcon
            class="bg-primary text-primary-contrast"
            :disabled="v$.$invalid"
            icon="add"
            @click="submit"
        />
    </div>
</template>
