<template>
  <div class="pt-5">
    <div class="panel pb-0">
      <div class="flex items-center md:flex-row flex-col mb-5 gap-5">
        <h5 class="font-semibold text-lg dark:text-white-light">
          {{ title }}
        </h5>
        <div class="xl:ml-auto flex items-center w-full md:w-fit">
          <span class="whitespace-nowrap mr-3">Por Página</span>
          <select
            id="perPage"
            @change="getBranches(null)"
            v-model="perPage"
            class="appearance-none relative block w-24 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
          >
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="20">20</option>
          </select>
        </div>
        <div class="xl:ml-auto w-full md:w-fit">
          <input
            id="search"
            v-model="search"
            @change="getBranches(null)"
            type="text"
            class="form-input w-auto"
            placeholder="Procurar..."
          />
        </div>
        <button
          @click="showBranchModal"
          type="button"
          class="btn btn-primary w-full md:w-fit"
        >
          <v-icon name="bi-plus-circle" class="mr-2" />
          {{ buttonTitle }}
        </button>
      </div>

      <div class="table-responsive">
        <table class="table-hover">
          <thead>
            <tr>
              <TableHeaderCell
                @click="sortBranch"
                class="border-b-2 p-2 text-left"
                field="name"
                :sort-field="sortField"
                :sort-direction="sortDirection"
                >Nome
              </TableHeaderCell>
              <TableHeaderCell
                @click="sortBranch"
                class="border-b-2 p-2 text-left"
                field="address"
                :sort-field="sortField"
                :sort-direction="sortDirection"
                >Endereço
              </TableHeaderCell>
              <TableHeaderCell
                @click="sortBranch"
                class="border-b-2 p-2 text-left"
                field="phone"
                :sort-field="sortField"
                :sort-direction="sortDirection"
                >Telefone
              </TableHeaderCell>
              <TableHeaderCell
                @click="sortBranch"
                class="border-b-2 p-2 text-left"
                field="whatsapp"
                :sort-field="sortField"
                :sort-direction="sortDirection"
                >WhatsApp
              </TableHeaderCell>
              <TableHeaderCell
                class="border-b-2 p-2 text-left"
                :sort-field="sortField"
              >
                Ações
              </TableHeaderCell>
            </tr>
          </thead>
          <tbody v-if="branchStore.$state.isLoading || isLoading">
            <tr>
              <td colspan="5">
                <Spinner
                  class="h-full flex items-center justify-center p-3.5"
                />
              </td>
            </tr>
          </tbody>
          <tbody v-else-if="branchStore.$state.branch.data.length === 0">
            <tr>
              <td colspan="5">
                <div
                  class="h-full flex items-center justify-center p-3.5 rounded text-danger bg-danger-light dark:bg-danger-dark-light"
                >
                  <span class="pr-2"
                    ><strong class="mr-1">AVISO!</strong>Nenhum registro
                    encontrado!</span
                  >
                </div>
              </td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr v-for="branche in branchList" :key="branche.id">
              <td class="border-b p-2">{{ branche.name }}</td>
              <td
                class="border-b p-2 max-w-[200px] whitespace-nowrap overflow-hidden text-ellipsis"
              >
                {{
                  branche.address
                    ? `${branche.address.street}, ${branche.address.number} - ${branche.address.district}`
                    : "Sem endereço cadastrado"
                }}
              </td>
              <td class="border-b p-2">{{ branche.phone }}</td>
              <td class="border-b p-2">{{ branche.whatsapp }}</td>
              <td class="border-b p-2">
                <div class="flex items-center">
                  <div>
                    <button
                      type="button"
                      v-tippy="{ content: 'Editar' }"
                      @click="editBranch(branche)"
                    >
                      <svg
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-4.5 h-4.5 ltr:mr-2 rtl:ml-2"
                      >
                        <path
                          d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999L5.83881 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844L7.47919 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z"
                          stroke="currentColor"
                          stroke-width="1.5"
                        />
                        <path
                          opacity="0.5"
                          d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015"
                          stroke="currentColor"
                          stroke-width="1.5"
                        />
                      </svg>
                    </button>
                  </div>
                  <div>
                    <button
                      type="button"
                      @click="deleteBranch(branche)"
                      v-tippy="{ content: 'Deletar' }"
                    >
                      <svg
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                      >
                        <path
                          d="M20.5001 6H3.5"
                          stroke="currentColor"
                          stroke-width="1.5"
                          stroke-linecap="round"
                        />
                        <path
                          d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5"
                          stroke="currentColor"
                          stroke-width="1.5"
                          stroke-linecap="round"
                        />
                        <path
                          opacity="0.5"
                          d="M9.5 11L10 16"
                          stroke="currentColor"
                          stroke-width="1.5"
                          stroke-linecap="round"
                        />
                        <path
                          opacity="0.5"
                          d="M14.5 11L14 16"
                          stroke="currentColor"
                          stroke-width="1.5"
                          stroke-linecap="round"
                        />
                        <path
                          opacity="0.5"
                          d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6"
                          stroke="currentColor"
                          stroke-width="1.5"
                        />
                      </svg>
                    </button>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div
        v-show="!branchStore.$state.isLoading"
        class="flex justify-between items-center mt-5"
      >
        <nav
          v-if="
            branchStore.$state.branch.total > branchStore.$state.branch.limit
          "
          class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px"
          aria-label="Pagination"
        >
          <a
            v-for="(link, i) of branchStore.$state.branch.links"
            :key="i"
            :disabled="!link.url"
            href="#"
            @click.prevent="getForPage($event, link)"
            aria-current="page"
            class="relative inline-flex items-center px-4 py-2 border text-sm font-medium whitespace-nowrap mb-6"
            v-html="link.label"
            :class="[
              link.active
                ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-200',
              i === 0 ? 'rounded-l-md' : '',
              i === branchStore.$state.branch.links.length - 1
                ? 'rounded-r-md'
                : '',
              !link.url ? 'bg-gray-200 text-gray-700' : '',
            ]"
          >
          </a>
        </nav>
      </div>
    </div>
  </div>
  <BranchModal
    v-model="showModal"
    :branch="branchModel"
    :address="branchAddress"
    @close="onModalClose"
  />
</template>

<script setup>
import { computed, onMounted, ref } from "vue";
import { useMeta } from "@/composables/use-meta";
import { useBranchStore } from "@/stores/useBranchStore";
import { useAuthStore } from "@/stores/useAuthStore";
import { PER_PAGE } from "@/constants";
import TableHeaderCell from "@/components/core/table/TableHeaderCell.vue";
import Swal from "sweetalert2";
import Spinner from "@/components/core/Spinner.vue";
import BranchModal from "./branchModal.vue";

useMeta({ title: "Filiais" });

//inicio- chamando o modal
const showModal = ref(false);

const isLoading = ref(false);

function showBranchModal() {
  showModal.value = true;
}
//fim - chamando o modal

const branchModel = ref({
  id: "",
  name: "",
  phone: "",
  whatsapp: "",
  email: "",
});

const branchAddress = ref({
  id: "",
  cep: "",
  street: "",
  number: "",
  complement: "",
  district: "",
  city: "",
  state: "",
});

const { title, buttonTitle } = defineProps({
  title: String,
  buttonTitle: String,
});

const branchStore = useBranchStore();
const authStore = useAuthStore();

const branchList = computed(() => branchStore.$state.branch.data);

const sortField = ref("id");
const sortDirection = ref("desc");
const perPage = ref(PER_PAGE);
const search = ref("");

onMounted(() => {
  authStore.getUser;
  if (isUserLoggedIn()) {
    getBranches();
    return;
  }
});

function getBranches(url = null) {
  branchStore.getBranches(
    url,
    search.value,
    perPage.value,
    sortField.value,
    sortDirection.value
  );
}

function getForPage(ev, link) {
  if (!link.url || link.active) {
    return;
  }
  getBranches(link.url);
}

function sortBranch(field) {
  if (sortField.value === field) {
    if (sortDirection.value === "asc") {
      sortDirection.value = "desc";
    } else {
      sortDirection.value = "asc";
    }
  } else {
    sortField.value = field;
    sortDirection.value = "asc";
  }

  getBranches();
}

async function editBranch(branch) {
  isLoading.value = true;
  const response = await branchStore.getBranche(branch.id);
  branchModel.value = response;
  branchAddress.value = response.address;
  isLoading.value = false;
  showBranchModal();
}

async function deleteBranch(branch) {
  Swal.fire({
    icon: "question",
    title: `Deseja excluir a filial ${branch.name}?`,
    text: "Essa ação não poderá ser desfeita!",
    showCancelButton: true,
    showLoaderOnConfirm: true,
    confirmButtonText: "Excluir",
    confirmButtonColor: "#E7515A",
    cancelButtonText: "Cancelar",
    padding: "2em",
    customClass: "sweet-alerts",
    preConfirm: () => {
      return branchStore
        .deleteAddress(branch.address.id)
        .then(() => {
          branchStore.deleteBranch(branch.id);
        })
        .then(() => {
          branchStore.getBranches();
          Swal.fire({
            title: "Excluído!",
            icon: "success",
            customClass: "sweet-alerts",
          });
        })
        .catch(() => {
          Swal.fire({
            icon: "error",
            title: "Não foi possível excluir o registro no momento",
            customClass: "sweet-alerts",
          });
        });
    },
  });
}

function onModalClose() {
  branchModel.value = {};
  branchAddress.value = {};
}

function isUserLoggedIn() {
  if (authStore.isLogged) return true;
}
</script>
