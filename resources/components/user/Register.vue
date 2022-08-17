<script setup lang="ts">
import { ref } from "vue";
import { required, email, helpers } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import { useUserStore } from "@/stores/user";
import { ERROR_MSG } from "@/constantes/errorMsg";
import { errorHelper } from "@/helpers/errorHelper";
import { eventBus } from "@/services/eventBus";

const userStore = useUserStore();

const state = ref({
    name: "",
    email: "",
    password: "",
});
const rules = {
    name: { required: helpers.withMessage(ERROR_MSG.required, required) },
    email: {
        required: helpers.withMessage(ERROR_MSG.required, required),
        email: helpers.withMessage(ERROR_MSG.email, email),
    },
    password: { required: helpers.withMessage(ERROR_MSG.required, required) },
};
const v$ = useVuelidate(rules, state);

async function submit(): Promise<void> {
    try {
        await userStore.register(
            state.value.name,
            state.value.email,
            state.value.password
        );
    } catch (e) {
        eventBus.$emit("show-snackbar", {
            text: errorHelper.formatMessage(e),
            type: "error",
        });
    }
}
</script>

<template>
    <div class="flex flex-col gap-4">
        <Input
            v-model:value="state.name"
            label="Nom"
            :error="v$.name.$errors[0]?.$message"
            @blur="v$.name.$touch()"
        />
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
            <Button :disabled="v$.$invalid" @click="submit"
                >Créer un compte</Button
            >
        </div>
    </div>
</template>
