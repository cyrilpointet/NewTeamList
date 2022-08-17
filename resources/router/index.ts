import { createRouter, createWebHistory } from "vue-router";
import type { RouteLocationNormalized } from "vue-router";

import { useUserStore } from "@/stores/user";
//import { useTeamStore } from "@/stores/team";

import HomeView from "@/components/pages/HomeView.vue";
import AccountView from "@/components/pages/AccountView.vue";

async function autoLog(
    to: RouteLocationNormalized,
    from: RouteLocationNormalized,
    // eslint-disable-next-line @typescript-eslint/ban-types
    next: Function
) {
    const userStore = useUserStore();
    if (!userStore.isLogged) {
        try {
            await userStore.autoLogin();
            next();
            return;
        } catch {
            userStore.logout();
            next({ path: "/" });
            return;
        }
    } else {
        next();
    }
}

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "/",
            name: "home",
            component: HomeView,
        },
        {
            path: "/account",
            name: "account",
            // route level code-splitting
            // this generates a separate chunk (About.[hash].js) for this route
            // which is lazy-loaded when the route is visited.
            component: AccountView,
            beforeEnter: [autoLog],
        },
    ],
});

export default router;
