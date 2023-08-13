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
            @change="getClients(null)"
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
            @change="getRols(null)"
            type="text"
            class="form-input w-auto"
            placeholder="Procurar..."
          />
        </div>
        <button
          @click="showRolModal"
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
                @click="sortRol"
                class="border-b-2 p-2 text-left"
                field="link"
                :sort-field="sortField"
                :sort-direction="sortDirection"
                >Código
              </TableHeaderCell>
              <TableHeaderCell
                @click="sortRol"
                class="border-b-2 p-2 text-left"
                field="clientId"
                :sort-field="sortField"
                :sort-direction="sortDirection"
                >Cliente
              </TableHeaderCell>
              <TableHeaderCell
                @click="sortRol"
                class="border-b-2 p-2 text-left"
                field="clientId"
                :sort-field="sortField"
                :sort-direction="sortDirection"
                >Filial
              </TableHeaderCell>
              <TableHeaderCell
                @click="sortRol"
                class="border-b-2 p-2 text-left"
                field="clientId"
                :sort-field="sortField"
                :sort-direction="sortDirection"
                >Celular
              </TableHeaderCell>
              <TableHeaderCell
                @click="sortRol"
                class="border-b-2 p-2 text-left"
                field="created_at"
                :sort-field="sortField"
                :sort-direction="sortDirection"
                >Criado em
              </TableHeaderCell>
              <TableHeaderCell
                @click="sortRol"
                class="border-b-2 p-2 text-left"
                field="finishedAt"
                :sort-field="sortField"
                :sort-direction="sortDirection"
                >Finalizado em
              </TableHeaderCell>
              <TableHeaderCell
                @click="sortRol"
                class="border-b-2 p-2 text-left"
                field="userId"
                :sort-field="sortField"
                :sort-direction="sortDirection"
                >Criado por
              </TableHeaderCell>
              <TableHeaderCell
                @click="sortRol"
                class="border-b-2 p-2 text-left"
                field="status"
                :sort-field="sortField"
                :sort-direction="sortDirection"
                >Situação
              </TableHeaderCell>
              <TableHeaderCell
                class="border-b-2 p-2 text-left"
                :sort-field="sortField"
              >
                Ações
              </TableHeaderCell>
            </tr>
          </thead>
          <tbody v-if="rolStore.$state.isLoading || isLoading">
            <tr>
              <td colspan="9">
                <Spinner
                  class="h-full flex items-center justify-center p-3.5"
                />
              </td>
            </tr>
          </tbody>
          <tbody v-else-if="rolStore.$state.rol.data.length === 0">
            <tr>
              <td colspan="9">
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
            <tr v-for="rol in rolList" :key="rol.id">
              <td class="border-b p-2">{{ rol.link }}</td>
              <td
                class="border-b p-2 max-w-[200px] whitespace-nowrap overflow-hidden text-ellipsis"
              >
                {{ rol.client.name }}
              </td>
              <td class="border-b p-2">{{ rol.client.branch.name }}</td>
              <td class="border-b p-2">{{ rol.client.cellPhone }}</td>
              <td class="border-b p-2">{{ formatDate(rol.created_at) }}</td>
              <td class="border-b p-2">
                <span
                  class="badge"
                  :class="rol.finishedAt ? 'bg-secondary' : 'bg-success'"
                >
                  {{
                    rol.finishedAt ? formatDate(rol.finishedAt) : "Em andamento"
                  }}
                </span>
              </td>
              <td class="border-b p-2">{{ rol.user.name }}</td>
              <td class="border-b p-2">
                <span :class="getStatusClass(rol.status)">{{
                  rol.status
                }}</span>
              </td>
              <td class="border-b p-2">
                <div class="flex items-center justify-between gap-2">
                  <div>
                    <button
                      :disabled="rol.status !== 'INICIO'"
                      type="button"
                      v-tippy="{ content: 'Editar' }"
                      @click="editRol(rol)"
                      :class="
                        rol.status !== 'INICIO' ? 'cursor-not-allowed ' : ''
                      "
                    >
                      <v-icon name="co-pencil" class="text-gray-600 h-5 w-5" />
                    </button>
                  </div>
                  <div>
                    <button
                      type="button"
                      @click="deleteRol(rol)"
                      v-tippy="{ content: 'Deletar' }"
                    >
                      <v-icon name="co-trash" class="text-gray-600 h-5 w-5" />
                    </button>
                  </div>
                  <div>
                    <button
                      type="button"
                      @click="sendRol(rol)"
                      v-tippy="{ content: 'Enviar ROL' }"
                    >
                      <v-icon
                        class="text-gray-500 h-5 w-5"
                        name="ri-mail-send-line"
                      />
                    </button>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div
        v-show="!rolStore.$state.isLoading"
        class="flex justify-between items-center mt-5"
      >
        <nav
          v-if="rolStore.$state.rol.total > rolStore.$state.rol.limit"
          class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px"
          aria-label="Pagination"
        >
          <a
            v-for="(link, i) of rolStore.$state.rol.links"
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
              i === rolStore.$state.rol.links.length - 1 ? 'rounded-r-md' : '',
              !link.url ? 'bg-gray-200 text-gray-700' : '',
            ]"
          >
          </a>
        </nav>
      </div>
    </div>
  </div>
  <RolModal
    v-model="showModal"
    :client="clientList.map((client) => ({ id: client.id, name: client.name }))"
    @close="onModalClose"
    :rol="rolModel"
  />
</template>

<script setup>
import { computed, onMounted, ref } from "vue";
import { useMeta } from "@/composables/use-meta";
import { useAuthStore } from "@/stores/useAuthStore";
import { PER_PAGE } from "@/constants";
import TableHeaderCell from "@/components/core/table/TableHeaderCell.vue";
import Swal from "sweetalert2";
import RolModal from "./rolModal.vue";
import { useRolStore } from "@/stores/useRolStore";
import { useClientStore } from "@/stores/useClientStore";
import Spinner from "@/components/core/Spinner.vue";

useMeta({ title: "ROLs" });

const { title, buttonTitle } = defineProps({
  title: String,
  buttonTitle: String,
});

const authStore = useAuthStore();
const rolStore = useRolStore();
const clientStore = useClientStore();

const rolList = computed(() => rolStore.$state.rol.data);
const clientList = computed(() => clientStore.$state.client.data);

//inicio- chamando o modal
const showModal = ref(false);

const isLoading = ref(false);

function showRolModal() {
  showModal.value = true;
}
//fim - chamando o modal

const sortField = ref("id");
const sortDirection = ref("desc");
const perPage = ref(PER_PAGE);
const search = ref("");

const DEFAULT_ROL_OBJECT = {
  id: "",
  clientId: "",
  userId: "",
  link: "",
  finishedAt: "",
  isHanger: "",
  status: "",
  deliveryDate: "",
  productionStartDate: "",
  productionEndDate: "",
  description: "",
};

const rolModel = ref({ ...DEFAULT_ROL_OBJECT });

const getStatusClass = (status) => {
  switch (status) {
    case "INICIO":
      return "badge bg-success";
    case "ENVIADO":
      return "badge bg-warning";
    case "CONCLUIDO":
      return "badge bg-primary";
    case "CANCELADO":
      return "badge bg-danger";
    default:
      return "badge bg-secondary";
  }
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString();
};

onMounted(() => {
  authStore.getUser;
  if (isUserLoggedIn()) {
    getRols();
    getClients();
    return;
  }
});

function getRols(url = null) {
  rolStore.getRols(
    url,
    search.value,
    perPage.value,
    sortField.value,
    sortDirection.value
  );
}

function getClients(url = null) {
  clientStore.getClients(
    url,
    search.value,
    perPage.value,
    sortField.value,
    sortDirection.value
  );
}

async function editClient(client) {
  isLoading.value = true;
  const response = await clientStore.getClient(client.id);
  clientModel.value = response;
  clientAddress.value = response.address;
  isLoading.value = false;
  showClientModal();
}

function getForPage(ev, link) {
  if (!link.url || link.active) {
    return;
  }
  getRols(link.url);
}

function sortRol(field) {
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

  getRols();
}

async function sendRol(rol) {
  console.log(rol);
  Swal.fire({
    icon: "question",
    title: `Deseja enviar o ROL para o/a cliente ${rol.client.name}?`,
    text: "Essa ação não poderá ser desfeita!",
    showCancelButton: true,
    showLoaderOnConfirm: true,
    confirmButtonText: "Enviar",
    confirmButtonColor: "#00AB55",
    cancelButtonText: "Cancelar",
    padding: "2em",
    customClass: "sweet-alerts",
    preConfirm: () => {
      return rolStore
        .sendRol(rol)
        .then(() => {
          getRols();
          Swal.fire({
            title: "ROL enviado com sucesso!",
            icon: "success",
            customClass: "sweet-alerts",
          });
        })
        .catch(() => {
          Swal.fire({
            icon: "error",
            title: "Não foi possível enviar o ROL no momento",
            customClass: "sweet-alerts",
          });
        });
    },
  });
}

async function deleteClient(client) {
  console.log(client);
  Swal.fire({
    icon: "question",
    title: `Deseja excluir o client ${client.name}?`,
    text: "Essa ação não poderá ser desfeita!",
    showCancelButton: true,
    showLoaderOnConfirm: true,
    confirmButtonText: "Excluir",
    confirmButtonColor: "#E7515A",
    cancelButtonText: "Cancelar",
    padding: "2em",
    customClass: "sweet-alerts",
    preConfirm: () => {
      return clientStore
        .deleteAddress(client.address.id)
        .then(() => {
          clientStore.deleteClient(client.id);
        })
        .then(() => {
          clientStore.getClients();
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
  rolModel.value = { ...DEFAULT_ROL_OBJECT };
}

function isUserLoggedIn() {
  if (authStore.isLogged) return true;
}
</script>
