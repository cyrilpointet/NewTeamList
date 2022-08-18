<script setup lang="ts">
import { useTeamStore } from "@/stores/team";
import { ref } from "vue";
import { errorHelper } from "@/helpers/errorHelper";
import { eventBus } from "@/services/eventBus";

const teamStore = useTeamStore();

const loading = ref(false);

async function deletePost(postId: string) {
    loading.value = true;
    try {
        await teamStore.deletePost(postId);
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
    <div class="overflow-x-hidden">
        <div v-if="teamStore.team.posts.length < 1" class="px-4 py-2">
            Liste vide
        </div>
        <TransitionGroup name="swipe">
            <div
                v-for="post in teamStore.team.posts"
                :key="post.id"
                class="px-4 py-2 flex justify-between items-center border-solid border-gray-300 border-b last:border-0 transition"
            >
                <p>{{ post.title }}</p>
                <ButtonIcon
                    icon="delete"
                    class="text-error hover:bg-gray-100"
                    :disabled="loading"
                    @click.stop="deletePost(post.id)"
                />
            </div>
        </TransitionGroup>
    </div>
</template>
