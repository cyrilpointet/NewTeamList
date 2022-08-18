import { defineStore } from "pinia";
import { ApiConsumer } from "@/services/ApiConsumer";
import { useUserStore } from "@/stores/user";
import type { Team, User } from "@/stores/storeTypes";

interface TeamMember extends User {
    pivot: {
        admin: true;
    };
}

interface StoreTeam extends Team {
    members: Array<TeamMember>;
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
    },
});
