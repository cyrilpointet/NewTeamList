<script setup lang="ts">
import { ref } from "vue";
import type { Ref } from "vue";
import axios from "axios";
import { helpers, required, email } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import { useUserStore } from "@/stores/user";
import { ERROR_MSG } from "@/constantes/errorMsg";
const userStore = useUserStore();

const errorMsg: Ref<string | null> = ref(null);
const state = ref({
    email: "",
    password: "",
});
const rules = {
    email: {
        required: helpers.withMessage(ERROR_MSG.required, required),
        email: helpers.withMessage(ERROR_MSG.email, email),
    },
    password: { required: helpers.withMessage(ERROR_MSG.required, required) },
};
const v$ = useVuelidate(rules, state);

async function submit(): Promise<void> {
    errorMsg.value = null;
    try {
        await userStore.login(state.value.email, state.value.password);
    } catch (e) {
        if (axios.isAxiosError(e)) {
            errorMsg.value =
                e.response?.status === 401
                    ? ERROR_MSG.badId
                    : ERROR_MSG.default;
        }
    }
}
</script>

<template>
    <div class="flex flex-col gap-4">
        <p class="text-error text-center">{{ errorMsg }}</p>
        <Input
            v-model:value="state.email"
            label="Email"
            :error="v$.email.$errors[0]?.$message"
            @blur="v$.email.$touch()"
        />
        <Input
            v-model:value="state.password"
            label="password"
            :error="v$.password.$errors[0]?.$message"
            @blur="v$.password.$touch()"
        />
        <div class="flex justify-center mt-4">
            <Button :disabled="v$.$invalid" @click="submit">Connexion</Button>
        </div>
    </div>
</template>
