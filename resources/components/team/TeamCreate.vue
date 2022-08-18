<script setup lang="ts">
import { ref } from "vue";
import type { Ref } from "vue";
import { helpers, required } from "@vuelidate/validators";
import { ERROR_MSG } from "@/constantes/errorMsg";
import useVuelidate from "@vuelidate/core";
import axios from "axios";
import { useTeamStore } from "@/stores/team";
import router from "@/router";

const teamStore = useTeamStore();

const loading = ref(false);
const errorMsg: Ref<string | null> = ref(null);
const state = ref({
    name: "",
});
const rules = {
    name: {
        required: helpers.withMessage(ERROR_MSG.required, required),
    },
};
const v$ = useVuelidate(rules, state);

async function submit(): Promise<void> {
    loading.value = true;
    errorMsg.value = null;
    try {
        const teamId = await teamStore.createTeam(state.value.name);
        router.push(`/team/${teamId}`);
    } catch (e) {
        if (axios.isAxiosError(e)) {
            errorMsg.value =
                e.response?.status === 401
                    ? ERROR_MSG.badId
                    : ERROR_MSG.default;
        }
    }
    loading.value = false;
}
</script>

<template>
    <p class="text-error text-center">{{ errorMsg }}</p>
    <div class="flex items-center gap-4">
        <Input
            v-model:value="state.name"
            class="flex-1"
            label="Nouvelle liste"
            :error="v$.name.$errors[0]?.$message"
            @blur="v$.name.$touch()"
        />
        <div class="flex justify-center mt-4">
            <ButtonIcon
                class="bg-primary text-primary-contrast"
                :disabled="v$.$invalid"
                icon="add"
                :loading="loading"
                @click="submit"
            />
        </div>
    </div>
</template>
