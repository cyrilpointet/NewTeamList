import { createRouter, createWebHistory } from "vue-router";
import type { RouteLocationNormalized } from "vue-router";

import { useUserStore } from "@/stores/user";
import { useTeamStore } from "@/stores/team";

import HomePage from "@/components/pages/HomePage.vue";
import AccountPage from "@/components/pages/AccountPage.vue";
import TeamPage from "@/components/pages/TeamPage.vue";

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

async function preloadTeam(
    to: RouteLocationNormalized,
    from: RouteLocationNormalized,
    // eslint-disable-next-line @typescript-eslint/ban-types
    next: Function
) {
    const teamStore = useTeamStore();
    if (teamStore.team?.id === to.params.id) {
        next();
        return;
    }
    if (typeof to.params.id === "string") {
        try {
            await teamStore.getTeam(to.params.id);
            next();
            return;
        } catch {
            next({ path: "/" });
            return;
        }
    } else {
        next({ path: "/" });
        return;
    }
}

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "/",
            name: "home",
            component: HomePage,
        },
        {
            path: "/account",
            name: "account",
            component: AccountPage,
            beforeEnter: [autoLog],
        },
        {
            path: "/team/:id",
            name: "team",
            component: () => TeamPage,
            beforeEnter: [autoLog, preloadTeam],
        },
    ],
});

export default router;
