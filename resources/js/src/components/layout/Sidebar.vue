<template>
  <div :class="{ 'dark text-white-dark': store.semidark }">
    <nav
      class="sidebar fixed min-h-screen h-full top-0 bottom-0 w-[260px] shadow-[5px_0_25px_0_rgba(94,92,154,0.1)] z-50 transition-all duration-300"
    >
      <div class="bg-white dark:bg-[#0e1726] h-full">
        <div class="flex justify-between items-center px-4 py-3">
          <router-link to="/" class="main-logo flex items-center shrink-0">
            <img
              class="w-8 ml-[5px] flex-none"
              src="/assets/images/logo.svg"
              alt=""
            />
            <span
              class="text-2xl ltr:ml-1.5 font-semibold align-middle lg:inline dark:text-white-light"
              >Mania - CRM</span
            >
          </router-link>
          <a
            href="javascript:;"
            class="collapse-icon w-8 h-8 rounded-full flex items-center hover:bg-gray-500/10 dark:hover:bg-dark-light/10 dark:text-white-light transition duration-300 hover:text-primary"
            @click="store.toggleSidebar()"
          >
            <svg
              class="w-5 h-5 m-auto"
              width="20"
              height="20"
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M13 19L7 12L13 5"
                stroke="currentColor"
                stroke-width="1.5"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
              <path
                opacity="0.5"
                d="M16.9998 19L10.9998 12L16.9998 5"
                stroke="currentColor"
                stroke-width="1.5"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
            </svg>
          </a>
        </div>
        <perfect-scrollbar
          :options="{
            swipeEasing: true,
            wheelPropagation: false,
          }"
          class="h-[calc(100vh-80px)] relative"
        >
          <ul class="relative font-semibold space-y-0.5 p-4 py-0">
            <h2
              class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1"
            >
              <svg
                class="w-4 h-5 flex-none hidden"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="1.5"
                fill="none"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <line x1="5" y1="12" x2="19" y2="12"></line>
              </svg>
              <span>Menu</span>
            </h2>
            <li class="nav-item">
              <ul>
                <li class="nav-item">
                  <router-link to="/" class="group" @click="toggleMobileMenu">
                    <div class="flex items-center">
                      <v-icon name="md-dashboard-round" />
                      <span
                        class="ltr:pl-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark"
                        >Dashboard</span
                      >
                    </div>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link
                    to="/rol"
                    class="group"
                    @click="toggleMobileMenu"
                  >
                    <div class="flex items-center">
                      <v-icon name="bi-clipboard2-plus-fill" />
                      <span
                        class="ltr:pl-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark"
                        >Gerar ROL</span
                      >
                    </div>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link
                    to="/clientes"
                    class="group"
                    @click="toggleMobileMenu"
                  >
                    <div class="flex items-center">
                      <v-icon name="fa-hands-helping" />
                      <span
                        class="ltr:pl-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark"
                        >Clientes</span
                      >
                    </div>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link
                    to="/apps/mailbox"
                    class="group"
                    @click="toggleMobileMenu"
                  >
                    <div class="flex items-center">
                      <v-icon name="gi-box-unpacking" />
                      <span
                        class="ltr:pl-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark"
                        >Operação</span
                      >
                    </div>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link
                    to="/apps/todolist"
                    class="group"
                    @click="toggleMobileMenu"
                  >
                    <div class="flex items-center">
                      <v-icon name="fa-truck" />
                      <span
                        class="ltr:pl-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark"
                        >Entregas/Coleta</span
                      >
                    </div>
                  </router-link>
                </li>

                <li class="menu nav-item">
                  <button
                    type="button"
                    class="nav-link group w-full"
                    :class="{ active: activeDropdown === 'invoice' }"
                    @click="
                      activeDropdown === 'invoice'
                        ? (activeDropdown = null)
                        : (activeDropdown = 'invoice')
                    "
                  >
                    <div class="flex items-center">
                      <v-icon name="ri-money-dollar-box-fill" />
                      <span
                        class="ltr:pl-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark"
                        >Financeiro</span
                      >
                    </div>
                    <div
                      class=""
                      :class="{ '!rotate-90': activeDropdown === 'invoice' }"
                    >
                      <svg
                        width="16"
                        height="16"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          d="M9 5L15 12L9 19"
                          stroke="currentColor"
                          stroke-width="1.5"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        />
                      </svg>
                    </div>
                  </button>
                  <vue-collapsible :isOpen="activeDropdown === 'invoice'">
                    <ul class="sub-menu text-gray-500">
                      <li>
                        <router-link
                          to="/apps/invoice/list"
                          @click="toggleMobileMenu"
                          >Gerar Fechamento</router-link
                        >
                      </li>
                      <li>
                        <router-link
                          to="/apps/invoice/preview"
                          @click="toggleMobileMenu"
                          >Extrato de Fechamento</router-link
                        >
                      </li>
                      <li>
                        <router-link
                          to="/apps/invoice/add"
                          @click="toggleMobileMenu"
                          >Lista de Fechamento</router-link
                        >
                      </li>
                      <li>
                        <router-link
                          to="/apps/invoice/edit"
                          @click="toggleMobileMenu"
                          >Extrato de Produção</router-link
                        >
                      </li>
                    </ul>
                  </vue-collapsible>
                </li>

                <li class="nav-item">
                  <router-link
                    to="/planos"
                    class="group"
                    @click="toggleMobileMenu"
                  >
                    <div class="flex items-center">
                      <v-icon name="fa-calendar-alt" />

                      <span
                        class="ltr:pl-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark"
                        >Planos</span
                      >
                    </div>
                  </router-link>
                </li>

                <li class="nav-item">
                  <router-link
                    to="/usuarios"
                    class="group"
                    @click="toggleMobileMenu"
                  >
                    <div class="flex items-center">
                      <v-icon name="fa-users" />
                      <span
                        class="ltr:pl-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark"
                        >Usuários</span
                      >
                    </div>
                  </router-link>
                </li>

                <li class="nav-item">
                  <router-link
                    to="/filiais"
                    class="group"
                    @click="toggleMobileMenu"
                  >
                    <div class="flex items-center">
                      <v-icon name="fa-building" />
                      <span
                        class="pl-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark"
                        >Filiais</span
                      >
                    </div>
                  </router-link>
                </li>

                <li class="menu nav-item">
                  <button
                    type="button"
                    class="nav-link group w-full"
                    :class="{ active: activeDropdown === 'setup' }"
                    @click="
                      activeDropdown === 'setup'
                        ? (activeDropdown = null)
                        : (activeDropdown = 'setup')
                    "
                  >
                    <div class="flex items-center">
                      <v-icon name="bi-gear-fill" />
                      <span
                        class="ltr:pl-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark"
                        >Configurações</span
                      >
                    </div>
                    <div
                      class=""
                      :class="{ '!rotate-90': activeDropdown === 'setup' }"
                    >
                      <svg
                        width="16"
                        height="16"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          d="M9 5L15 12L9 19"
                          stroke="currentColor"
                          stroke-width="1.5"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        />
                      </svg>
                    </div>
                  </button>
                  <vue-collapsible :isOpen="activeDropdown === 'setup'">
                    <ul class="sub-menu text-gray-500">
                      <li>
                        <router-link to="/roupas" @click="toggleMobileMenu"
                          >Roupas</router-link
                        >
                      </li>
                    </ul>
                  </vue-collapsible>
                </li>
              </ul>
            </li>
          </ul>
        </perfect-scrollbar>
      </div>
    </nav>
  </div>
</template>

<script lang="ts" setup>
import { ref, onMounted } from "vue";

import { useAppStore } from "../../stores/index";
import VueCollapsible from "vue-height-collapsible/vue3";
const store = useAppStore();
const activeDropdown: any = ref("");
const subActive: any = ref("");

onMounted(() => {
  const selector = document.querySelector(
    '.sidebar ul a[href="' + window.location.pathname + '"]'
  );
  if (selector) {
    selector.classList.add("active");
    const ul: any = selector.closest("ul.sub-menu");
    if (ul) {
      let ele: any = ul.closest("li.menu").querySelectorAll(".nav-link") || [];
      if (ele.length) {
        ele = ele[0];
        setTimeout(() => {
          ele.click();
        });
      }
    }
  }
});

const toggleMobileMenu = () => {
  if (window.innerWidth < 1024) {
    store.toggleSidebar();
  }
};
</script>
