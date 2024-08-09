import { createRouter, createWebHistory } from "vue-router";
import MainLayout from "layouts/MainLayout.vue";
import IndexPage from "pages/IndexPage.vue";
import LoginPage from "pages/LoginPage.vue";
import ErrorNotFound from "pages/ErrorNotFound.vue";

const routes = [
  {
    path: "/",
    component: MainLayout,
    children: [
      {
        path: "patients",
        component: IndexPage,
      },
      {
        path: "login",
        component: LoginPage,
      },
    ],
  },
  {
    path: "/:catchAll(.*)*",
    component: ErrorNotFound,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
