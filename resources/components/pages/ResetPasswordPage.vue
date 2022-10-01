<script setup lang="ts">
import { ref, Ref } from "vue";
import { email, helpers, required } from "@vuelidate/validators";
import { ERROR_MSG } from "@/constantes/errorMsg";
import useVuelidate from "@vuelidate/core";
import { ApiConsumer } from "@/services/ApiConsumer";
import { eventBus } from "@/services/eventBus";
import router from "@/router";

const errorMsg: Ref<string | null> = ref(null);
const state = ref({
    email: "",
});
const rules = {
    email: {
        required: helpers.withMessage(ERROR_MSG.required, required),
        email: helpers.withMessage(ERROR_MSG.email, email),
    },
};
const v$ = useVuelidate(rules, state);

async function askResetPassword() {
    try {
        await ApiConsumer.post("user/askResetPassword", {
            email: state.value.email,
        });
        eventBus.$emit("show-snackbar", {
            text: "Un email contenant un lien de renouvellement de mot de passe a été envoyé à votre adresse",
        });
        router.push("/");
    } catch (e) {
        eventBus.$emit("show-snackbar", {
            text: "Une erreur est survenue",
            type: "error",
        });
    }
}
</script>

<template>
    <div class="flex justify-center">
        <Card class="mt-6 flex-1 max-w-md">
            <h2 class="mb-4 text-xl text-center">
                Renouveler mon mot de passe :
            </h2>
            <p class="text-error text-center">{{ errorMsg }}</p>
            <Input
                v-model:value="state.email"
                label="Email"
                :error="v$.email.$errors[0]?.$message"
                @blur="v$.email.$touch()"
            />
            <div class="flex justify-center mt-4">
                <Button :disabled="v$.$invalid" @click="askResetPassword">
                    Envoyer
                </Button>
            </div>
        </Card>
    </div>
</template>
