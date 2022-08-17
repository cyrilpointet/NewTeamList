import { defineStore } from "pinia";
import { ApiConsumer } from "@/services/ApiConsumer";
import type { User } from "@/stores/storeTypes";

//interface User extends User {}

type UserRootState = {
    user: User | null;
};

export const useUserStore = defineStore({
    id: "user",
    state: () =>
        ({
            user: null,
        } as UserRootState),
    getters: {
        isLogged: (state) => state.user !== null,
    },
    actions: {
        async refreshUser() {
            const user = (await ApiConsumer.get("user/self")) as User;
            this.user = user;
        },
        async autoLogin() {
            if (this.user) return;
            try {
                if (localStorage.getItem("token")) {
                    ApiConsumer.setToken(localStorage.getItem("token") || "");
                    const user = (await ApiConsumer.get("user")) as User;
                    this.user = user;
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
            })) as { user: User; token: string };
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
        },
    },
});
