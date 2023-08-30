import { createRouter, createWebHistory, RouteRecordRaw } from "vue-router";
import { useAppStore } from "../stores/index";
import { useAuthStore } from "../stores/useAuthStore";
import { useClientStore } from "../stores/useClientStore";
import appSetting from "../app-setting";

import HomeView from "../views/index.vue";
import { computed } from "vue";

const routes: RouteRecordRaw[] = [
  // dashboard
  {
    path: "/",
    name: "dashboard",
    component: HomeView,
    meta: {
      requiresAuth: true,
    },
  },
  {
    path: "/login",
    name: "login",
    component: () =>
      import(
        /* webpackChunkName: "analytics" */ "../views/auth/Login/index.vue"
      ),
    meta: {
      layout: "auth",
      requiresGuest: true,
    },
  },
  {
    path: "/request-password",
    name: "requestPassword",
    component: () =>
      import(
        /* webpackChunkName: "analytics" */ "../views/auth/RequestPassword/index.vue"
      ),
    meta: {
      layout: "auth",
      requiresGuest: true,
    },
  },
  {
    path: "/reset-password/:token",
    name: "resetPassword",
    component: () =>
      import(
        /* webpackChunkName: "analytics" */ "../views/auth/ResetPassword/index.vue"
      ),
    meta: {
      layout: "auth",
      requiresGuest: true,
    },
  },
  {
    path: "/filiais",
    name: "branch",
    component: () =>
      import(/* webpackChunkName: "analytics" */ "../views/branch/index.vue"),
    meta: {
      requiresAuth: true,
    },
  },
  {
    path: "/usuarios",
    name: "users",
    component: () =>
      import(/* webpackChunkName: "analytics" */ "../views/user/index.vue"),
    meta: {
      requiresAuth: true,
    },
  },
  {
    path: "/planos",
    name: "plans",
    component: () =>
      import(/* webpackChunkName: "analytics" */ "../views/plan/index.vue"),
    meta: {
      requiresAuth: true,
    },
  },
  {
    path: "/roupas",
    name: "clothins",
    component: () =>
      import(/* webpackChunkName: "analytics" */ "../views/cloth/index.vue"),
    meta: {
      requiresAuth: true,
    },
  },
  {
    path: "/clientes",
    name: "clients",
    component: () =>
      import(/* webpackChunkName: "analytics" */ "../views/client/index.vue"),
    meta: {
      requiresAuth: true,
    },
  },
  {
    path: "/erol",
    name: "rol",
    component: () =>
      import(/* webpackChunkName: "analytics" */ "../views/rol/index.vue"),
    meta: {
      requiresAuth: true,
    },
  },
  {
    path: "/erol/:uuid",
    name: "clientForm",
    component: () =>
      import(/* webpackChunkName: "analytics" */ "../views/rol/clientForm.vue"),
    meta: {
      layout: "auth",
      requiresClientAuth: true,
    },
  },
  {
    path: "/clientes/login",
    name: "clientLogin",
    component: () =>
      import(
        /* webpackChunkName: "analytics" */ "../views/client/clientLogin.vue"
      ),
    meta: {
      layout: "auth",
      requiresClientForm: true,
    },
  },
  {
    path: "/:pathMatch(.*)",
    name: "notfound",
    component: () =>
      import(/* webpackChunkName: "analytics" */ "../views/404/index.vue"),
    meta: {
      layout: "auth",
    },
  },
];

const router = createRouter({
  history: createWebHistory(),
  linkExactActiveClass: "active",
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    } else {
      return { left: 0, top: 0 };
    }
  },
});

router.beforeEach((to, from, next) => {
  const store = useAppStore();
  const authStore = useAuthStore();
  const clientStore = useClientStore();

  if (to?.meta?.requiresAuth && !authStore.$state.user.token) {
    next({ name: "login" });
    return;
  } else if (to?.meta?.requiresGuest && authStore.$state.user.token) {
    next({ name: "dashboard" });
    return;
  }

  if (to?.meta?.requiresClientAuth && !clientStore.$state.client.token) {
    const routeParam = to.params.uuid;
    sessionStorage.setItem("CLIENT_LINK", routeParam);

    next({ name: "clientLogin" });
    return;
  } else if (to?.meta?.requiresClientForm && clientStore.$state.client.token) {
    next({ name: "clientForm" });
    return;
  }

  if (to?.meta?.layout == "auth") {
    store.setMainLayout("auth");
  } else {
    store.setMainLayout("app");
  }
  next(true);
});

router.afterEach((to, from, next) => {
  appSetting.changeAnimation();
});
export default router;
