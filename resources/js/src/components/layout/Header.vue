<template>
  <header :class="{ dark: store.semidark && store.menu === 'horizontal' }">
    <div class="shadow-sm">
      <div
        class="relative bg-white flex w-full items-center px-5 py-2.5 dark:bg-[#0e1726]"
      >
        <div
          class="horizontal-logo flex lg:hidden justify-between items-center ltr:mr-2 rtl:ml-2"
        >
          <a
            href="javascript:;"
            class="collapse-icon flex-none dark:text-[#d0d2d6] hover:text-primary dark:hover:text-primary flex lg:hidden ltr:ml-2 rtl:mr-2 p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:bg-white-light/90 dark:hover:bg-dark/60"
            @click="store.toggleSidebar()"
          >
            <svg
              width="20"
              height="20"
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M20 7L4 7"
                stroke="currentColor"
                stroke-width="1.5"
                stroke-linecap="round"
              />
              <path
                opacity="0.5"
                d="M20 12L4 12"
                stroke="currentColor"
                stroke-width="1.5"
                stroke-linecap="round"
              />
              <path
                d="M20 17L4 17"
                stroke="currentColor"
                stroke-width="1.5"
                stroke-linecap="round"
              />
            </svg>
          </a>
        </div>
        <div
          class="sm:flex-1 ltr:sm:ml-0 ltr:ml-auto sm:rtl:mr-0 rtl:mr-auto flex items-center space-x-1.5 lg:space-x-2 rtl:space-x-reverse dark:text-[#d0d2d6]"
        >
          <div class="sm:ltr:mr-auto sm:rtl:ml-auto"></div>
          <div>
            <span>Unidade: {{ currentUser?.branch?.name }}</span>
          </div>
          <div>
            <a
              href="javascript:;"
              v-show="store.theme === 'light'"
              class="flex items-center p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60"
              @click="store.toggleTheme('dark')"
            >
              <svg
                width="25"
                height="25"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <circle
                  cx="12"
                  cy="12"
                  r="5"
                  stroke="currentColor"
                  stroke-width="1.5"
                />
                <path
                  d="M12 2V4"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                />
                <path
                  d="M12 20V22"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                />
                <path
                  d="M4 12L2 12"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                />
                <path
                  d="M22 12L20 12"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                />
                <path
                  opacity="0.5"
                  d="M19.7778 4.22266L17.5558 6.25424"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                />
                <path
                  opacity="0.5"
                  d="M4.22217 4.22266L6.44418 6.25424"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                />
                <path
                  opacity="0.5"
                  d="M6.44434 17.5557L4.22211 19.7779"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                />
                <path
                  opacity="0.5"
                  d="M19.7778 19.7773L17.5558 17.5551"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                />
              </svg>
            </a>
            <a
              href="javascript:;"
              v-show="store.theme === 'dark'"
              class="flex items-center p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60"
              @click="store.toggleTheme('light')"
            >
              <svg
                width="25"
                height="25"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M21.0672 11.8568L20.4253 11.469L21.0672 11.8568ZM12.1432 2.93276L11.7553 2.29085V2.29085L12.1432 2.93276ZM21.25 12C21.25 17.1086 17.1086 21.25 12 21.25V22.75C17.9371 22.75 22.75 17.9371 22.75 12H21.25ZM12 21.25C6.89137 21.25 2.75 17.1086 2.75 12H1.25C1.25 17.9371 6.06294 22.75 12 22.75V21.25ZM2.75 12C2.75 6.89137 6.89137 2.75 12 2.75V1.25C6.06294 1.25 1.25 6.06294 1.25 12H2.75ZM15.5 14.25C12.3244 14.25 9.75 11.6756 9.75 8.5H8.25C8.25 12.5041 11.4959 15.75 15.5 15.75V14.25ZM20.4253 11.469C19.4172 13.1373 17.5882 14.25 15.5 14.25V15.75C18.1349 15.75 20.4407 14.3439 21.7092 12.2447L20.4253 11.469ZM9.75 8.5C9.75 6.41182 10.8627 4.5828 12.531 3.57467L11.7553 2.29085C9.65609 3.5593 8.25 5.86509 8.25 8.5H9.75ZM12 2.75C11.9115 2.75 11.8077 2.71008 11.7324 2.63168C11.6686 2.56527 11.6538 2.50244 11.6503 2.47703C11.6461 2.44587 11.6482 2.35557 11.7553 2.29085L12.531 3.57467C13.0342 3.27065 13.196 2.71398 13.1368 2.27627C13.0754 1.82126 12.7166 1.25 12 1.25V2.75ZM21.7092 12.2447C21.6444 12.3518 21.5541 12.3539 21.523 12.3497C21.4976 12.3462 21.4347 12.3314 21.3683 12.2676C21.2899 12.1923 21.25 12.0885 21.25 12H22.75C22.75 11.2834 22.1787 10.9246 21.7237 10.8632C21.286 10.804 20.7293 10.9658 20.4253 11.469L21.7092 12.2447Z"
                  fill="currentColor"
                />
              </svg>
            </a>
          </div>

          <div class="dropdown shrink-0">
            <Popper
              :placement="
                store.rtlClass === 'rtl' ? 'bottom-end' : 'bottom-start'
              "
              offsetDistance="8"
              class="!block"
            >
              <button type="button" class="relative group block">
                <v-icon
                  name="fa-user"
                  scale="2"
                  class="bg-white-light rounded-full object-cover saturate-50 group-hover:saturate-100 p-1"
                />
              </button>
              <template #content="{ close }">
                <ul
                  class="text-dark dark:text-white-dark !py-0 w-[230px] font-semibold dark:text-white-light/90"
                >
                  <li>
                    <div class="flex items-center px-4 py-4">
                      <div class="flex-none">
                        <v-icon name="fa-user" scale="1.5" />
                      </div>
                      <div class="ltr:pl-4 rtl:pr-4">
                        <h4 class="text-sm">
                          {{ currentUser?.name
                          }}<span
                            class="text-xs bg-success-light rounded text-success px-1 ltr:ml-2 rtl:ml-2 capitalize"
                            >{{ currentUser?.profile }}</span
                          >
                        </h4>
                        <a
                          class="text-black/60 hover:text-primary dark:text-dark-light/60 dark:hover:text-white"
                          href="javascript:;"
                          >{{ currentUser?.email }}</a
                        >
                      </div>
                    </div>
                  </li>
                  <li
                    class="border-t border-white-light dark:border-white-light/10"
                  >
                    <button class="text-danger !py-3" @click="logout">
                      <svg
                        class="w-4.5 h-4.5 ltr:mr-2 rtl:ml-2 rotate-90"
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          opacity="0.5"
                          d="M17 9.00195C19.175 9.01406 20.3529 9.11051 21.1213 9.8789C22 10.7576 22 12.1718 22 15.0002V16.0002C22 18.8286 22 20.2429 21.1213 21.1215C20.2426 22.0002 18.8284 22.0002 16 22.0002H8C5.17157 22.0002 3.75736 22.0002 2.87868 21.1215C2 20.2429 2 18.8286 2 16.0002L2 15.0002C2 12.1718 2 10.7576 2.87868 9.87889C3.64706 9.11051 4.82497 9.01406 7 9.00195"
                          stroke="currentColor"
                          stroke-width="1.5"
                          stroke-linecap="round"
                        />
                        <path
                          d="M12 15L12 2M12 2L15 5.5M12 2L9 5.5"
                          stroke="currentColor"
                          stroke-width="1.5"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        />
                      </svg>

                      Sair
                    </button>
                  </li>
                </ul>
              </template>
            </Popper>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script lang="ts" setup>
import { computed, onMounted, watch } from "vue";

import { useRoute } from "vue-router";
import { useAppStore } from "../../stores/index";
import { useAuthStore } from "@/stores/useAuthStore";
import { useRouter } from "vue-router";
const store = useAppStore();
const authStore = useAuthStore();
const route = useRoute();

watch(route, (to, from) => {
  setActiveDropdown();
});

const currentUser = computed(() => authStore.$state.user.data);

onMounted(() => {
  setActiveDropdown();
});

const router = useRouter();

const logout = () => {
  authStore.logout().then(() => {
    router.push({ name: "login" });
  });
};

const setActiveDropdown = () => {
  const selector = document.querySelector(
    'ul.horizontal-menu a[href="' + window.location.pathname + '"]'
  );
  if (selector) {
    selector.classList.add("active");
    const all: any = document.querySelectorAll(
      "ul.horizontal-menu .nav-link.active"
    );
    for (let i = 0; i < all.length; i++) {
      all[0]?.classList.remove("active");
    }
    const ul: any = selector.closest("ul.sub-menu");
    if (ul) {
      let ele: any = ul.closest("li.menu").querySelectorAll(".nav-link");
      if (ele) {
        ele = ele[0];
        setTimeout(() => {
          ele?.classList.add("active");
        });
      }
    }
  }
};
</script>
