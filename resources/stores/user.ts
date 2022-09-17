import { defineStore } from "pinia";
import { ApiConsumer } from "@/services/ApiConsumer";
import type { Invitation, User } from "@/stores/storeTypes";
import { Team } from "@/stores/storeTypes";

interface UserTeam extends Team {
    pivot: {
        admin: boolean;
    };
}

interface UserInvitation extends Invitation {
    team: Team;
}

interface UserStore extends User {
    teams: UserTeam[];
    invitations: UserInvitation[];
}

type UserRootState = {
    user: UserStore | null;
};

export const useUserStore = defineStore({
    id: "user",
    state: () =>
        ({
            user: null,
        } as UserRootState),
    getters: {
        isLogged: (state) => state.user !== null,
        hasInvitations: (state) =>
            state.user && state.user.invitations.length > 0,
    },
    actions: {
        async refreshUser() {
            const data = (await ApiConsumer.get("user")) as {
                user: UserStore;
                token: string;
            };
            this.user = data.user;
            ApiConsumer.setToken(data.token);
        },
        async autoLogin() {
            if (this.user) return;
            try {
                if (localStorage.getItem("token")) {
                    ApiConsumer.setToken(localStorage.getItem("token") || "");
                    const data = (await ApiConsumer.get("user")) as {
                        user: UserStore;
                        token: string;
                    };
                    this.user = data.user;
                    ApiConsumer.setToken(data.token);
                }
            } catch (e) {
                this.user = null;
                ApiConsumer.removeToken();
                throw e;
            }
        },
        async login(email: string, password: string) {
            const data = (await ApiConsumer.post("user/login", {
                email,
                password,
            })) as { user: UserStore; token: string };
            this.user = data.user;
            ApiConsumer.setToken(data.token);
        },
        async register(name: string, email: string, password: string) {
            const data = (await ApiConsumer.post("user/register", {
                name,
                email,
                password,
            })) as { user: User; token: string };
            this.user = data.user;
            ApiConsumer.setToken(data.token);
        },
        logout() {
            this.user = null;
            ApiConsumer.removeToken();
            localStorage.removeItem("fcmToken");
        },
    },
});
