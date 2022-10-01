<script setup lang="ts">
import { ref, Ref } from "vue";
import { email, helpers, required } from "@vuelidate/validators";
import { ERROR_MSG } from "@/constantes/errorMsg";
import useVuelidate from "@vuelidate/core";
import { ApiConsumer } from "@/services/ApiConsumer";

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
        const toto = await ApiConsumer.post("user/askResetPassword", {
            email: state.value.email,
        });
        console.log(toto);
    } catch (e) {
        console.log(e);
    }
}
</script>

<template>
    <div class="flex justify-center">
        <p class="text-error text-center">{{ errorMsg }}</p>
        <Input
            v-model:value="state.email"
            label="Email"
            :error="v$.email.$errors[0]?.$message"
            @blur="v$.email.$touch()"
        />
        <div class="flex justify-center mt-4">
            <Button :disabled="v$.$invalid" @click="askResetPassword"
                >Valider</Button
            >
        </div>
    </div>
</template>
