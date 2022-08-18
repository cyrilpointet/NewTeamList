import { defineStore } from "pinia";
import { ApiConsumer } from "@/services/ApiConsumer";
import { useUserStore } from "@/stores/user";
import type { Invitation, Team, User, Post } from "@/stores/storeTypes";

interface TeamMember extends User {
    pivot: {
        admin: true;
    };
}

interface StoreTeam extends Team {
    members: TeamMember[];
    invitations: Invitation[];
    posts: Post[];
}

type TeamRootState = {
    team: StoreTeam | null;
};

export const useTeamStore = defineStore({
    id: "team",
    state: () =>
        ({
            team: null,
        } as TeamRootState),
    getters: {
        hasInvitations: (state) =>
            state.team && state.team.invitations.length > 0,
        isUserManager: (state) => {
            const userStore = useUserStore();
            const user = state.team?.members.find(
                (elem) => elem.id === userStore.user?.id
            );
            return !!user?.pivot.admin;
        },
        userMembership: (state) => {
            const userStore = useUserStore();
            return state.team?.members.find(
                (elem) => elem.id === userStore.user?.id
            );
        },
    },
    actions: {
        async createTeam(name: string) {
            const team = (await ApiConsumer.post("team", {
                name,
            })) as StoreTeam;
            this.team = team;
            const userStore = useUserStore();
            await userStore.refreshUser();
            return team.id;
        },
        async getTeam(id: string) {
            const team = (await ApiConsumer.get(`team/${id}`)) as StoreTeam;
            this.team = team;
        },
        async refreshTeam() {
            if (!this.team?.id) return;
            const team = (await ApiConsumer.get(
                `team/${this.team.id}`
            )) as StoreTeam;
            this.team = team;
        },
        async updateTeam(values: { name: string }) {
            if (!this.team) return;
            const newTeam = (await ApiConsumer.put(`team/${this.team.id}`, {
                name: values.name,
            })) as StoreTeam;
            this.team = newTeam;
            const userStore = useUserStore();
            await userStore.refreshUser();
        },
        async deleteTeam() {
            if (!this.team) return;
            await ApiConsumer.delete(`team/${this.team.id}`);
            this.team = null;
            const userStore = useUserStore();
            await userStore.refreshUser();
        },
        async setMemberManager(member: TeamMember) {
            if (!this.team) return;
            const updatedTeam = (await ApiConsumer.put(
                `team/${this.team.id}/admin`,
                {
                    id: member.id,
                    admin: !member.pivot.admin,
                }
            )) as StoreTeam;
            this.team = updatedTeam;
            const userStore = useUserStore();
            await userStore.refreshUser();
        },
        async deleteMember(memberId: string) {
            if (!this.team) return;
            const updatedTeam = (await ApiConsumer.delete(
                `team/${this.team.id}/member`,
                {
                    id: memberId,
                }
            )) as StoreTeam;
            this.team = updatedTeam;
            const userStore = useUserStore();
            await userStore.refreshUser();
        },
        async leaveTeam() {
            if (!this.team) return;
            const userStore = useUserStore();

            await ApiConsumer.delete(`user/team/${this.team.id}`);
            await userStore.refreshUser();
            this.team = null;
        },
        async addPost(title: string) {
            if (!this.team) return;
            await ApiConsumer.post(`team/${this.team.id}/post`, {
                title,
            });
            await this.refreshTeam();
        },
        async deletePost(postId: string) {
            await ApiConsumer.delete(`post/${postId}`);
            await this.refreshTeam();
        },
    },
});
