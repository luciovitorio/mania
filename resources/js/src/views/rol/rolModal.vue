<template>
  <div class="mb-5">
    <!-- Modal -->
    <TransitionRoot appear :show="show" as="template">
      <Dialog as="div" @close="closeModal" class="relative z-50">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <DialogOverlay class="fixed inset-0 bg-[black]/60" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center px-4 py-8">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel
                class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-5xl text-black dark:text-white-dark"
              >
                <header
                  class="text-lg font-bold bg-[#fbfbfb] dark:bg-[#121c2c] ltr:pl-5 rtl:pr-5 py-3 ltr:pr-[50px] rtl:pl-[50px]"
                >
                  {{
                    rol.id
                      ? `Atualizar eROL: "${props.rol.link}"`
                      : "Cadastrar novo eROL"
                  }}
                </header>
                <button
                  type="button"
                  class="absolute top-4 ltr:right-4 rtl:left-4 text-gray-400 hover:text-gray-800 dark:hover:text-gray-600 outline-none"
                  @click="closeModal"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24px"
                    height="24px"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="w-6 h-6"
                  >
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                  </svg>
                </button>
                <Spinner
                  v-show="loading"
                  class="absolute left-0 top-0 bg-white right-0 bottom-0 flex items-center justify-center"
                />
                <form class="space-y-5 p-5" @submit.prevent="submitForm()">
                  <!-- CLIENTE e RESPONSáVEL e ID EROL -->
                  <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div
                      class="md:col-span-2"
                      :class="{
                        'has-error': $v.rol.clientId.$error,
                        'has-success': isSubmitForm && !$v.rol.clientId.$error,
                      }"
                    >
                      <label for="name">Cliente</label>
                      <multiselect
                        v-model="selectedClientName"
                        v-show="!loading"
                        :options="client"
                        class="custom-multiselect"
                        :searchable="true"
                        track-by="id"
                        label="name"
                        :optionsLimit="2"
                        placeholder="Pesquisar cliente..."
                        selected-label=""
                        select-label=""
                        deselect-label=""
                      ></multiselect>
                      <template v-if="isSubmitForm && $v.rol.clientId.$error">
                        <p class="text-danger mt-1 text-sm">
                          Por favor preencha esse campo
                        </p>
                      </template>
                    </div>

                    <div
                      class="md:col-span-1"
                      :class="{
                        'has-error': $v.rol.userId.$error,
                        'has-success': isSubmitForm && !$v.rol.userId.$error,
                      }"
                    >
                      <label for="name">Responsável</label>
                      <input
                        id="name"
                        type="text"
                        class="form-input read-only:bg-[#eee] dark:read-only:bg-[#1b2e4b] text-white-dark"
                        v-model="userAuthName"
                        readonly
                      />
                      <template v-if="isSubmitForm && $v.rol.userId.$error">
                        <p class="text-danger mt-1 text-sm">
                          Por favor preencha esse campo
                        </p>
                      </template>
                    </div>

                    <div
                      class="md:col-span-1"
                      :class="{
                        'has-error': $v.rol.link.$error,
                        'has-success': isSubmitForm && !$v.rol.link.$error,
                      }"
                    >
                      <label for="name">Id do eROL</label>
                      <input
                        id="name"
                        type="text"
                        class="form-input read-only:bg-[#eee] dark:read-only:bg-[#1b2e4b] text-white-dark"
                        placeholder="Clique aqui para gerar"
                        readonly
                        v-model="rol.link"
                        @click="generateUUID"
                      />
                      <template v-if="isSubmitForm && $v.rol.link.$error">
                        <p class="text-danger mt-1 text-sm">
                          Por favor preencha esse campo
                        </p>
                      </template>
                    </div>
                  </div>

                  <footer class="flex justify-end items-center mt-8">
                    <button
                      type="button"
                      @click="closeModal"
                      class="btn btn-outline-danger mt-10"
                    >
                      Cancelar
                    </button>
                    <button
                      type="submit"
                      class="btn btn-primary ml-4 bg-primary/90 hover:bg-primary mt-10"
                      v-show="!rol.id"
                      @click="buttonCreate"
                    >
                      Cadastrar
                    </button>
                    <button
                      type="submit"
                      class="btn btn-primary ml-4 bg-primary/90 hover:bg-primary mt-10"
                      v-show="rol.id"
                      @click="buttonEdit"
                    >
                      Atualizar
                    </button>
                  </footer>
                </form>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </div>
</template>

<script setup>
import { computed, defineEmits, ref, watch } from "vue";
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogOverlay,
} from "@headlessui/vue";
import { useMeta } from "@/composables/use-meta";
import { useVuelidate } from "@vuelidate/core";
import { required, email, numeric } from "@vuelidate/validators";
import Multiselect from "@suadelabs/vue3-multiselect";
import "@suadelabs/vue3-multiselect/dist/vue3-multiselect.css";
import { useClientStore } from "@/stores/useClientStore";
import { v4 as uuidv4 } from "uuid";
import { useAuthStore } from "@/stores/useAuthStore";
import { useRolStore } from "@/stores/useRolStore";
import Spinner from "@/components/core/Spinner.vue";

import {
  showMessage,
  removeSpecialCharacters,
  formatCEP,
  formatPhone,
  formatCellphone,
  formatCPF,
} from "@/utils/utils";

const selectedClientName = ref(null);

watch(selectedClientName, (newClient) => {
  if (newClient !== null) {
    rol.value.clientId = newClient.id; // Atualize o 'rol' com o ID
  } else {
    rol.value.clientId = null;
  }
});

useMeta({ title: "Cadastrar eROL" });

const clientStore = useClientStore();
const authStore = useAuthStore();
const rolStore = useRolStore();

const userAuthName = computed(() => authStore.$state.user.data.name);
const userAuthId = computed(() => authStore.$state.user.data.id);

const rol = ref({
  ...props.rol,
});

const generateUUID = () => {
  rol.value.link = uuidv4();
};

const client = ref([...props.client]);

const loading = ref(false);

const props = defineProps({
  modelValue: Boolean,
  client: {
    type: Array,
  },
  rol: {
    required: true,
    type: Object,
  },
});

const emit = defineEmits(["update:modelValue", "close"]);

const show = computed({
  get: () => props.modelValue,
  set: (value) => emit("update:modelValue", value),
});

watch(
  () => props.rol,
  (newRol) => {
    Object.assign(rol.value, newRol);
  }
);

watch(
  () => props.client,
  (newClient) => {
    client.value = [...newClient];
  }
);

const isSubmitForm = ref(false);

const rules = {
  rol: {
    clientId: { required },
    userId: { required },
    link: { required },
  },
};

const $v = useVuelidate(rules, { rol });

const submitForm = async () => {
  rol.value.userId = userAuthId.value;
  isSubmitForm.value = true;
  $v.value.rol.$touch();
  if ($v.value.rol.$invalid) {
    return false;
  }

  // Chamando a action no meu useUserStore
  try {
    loading.value = true;
    if (client.value.id) {
      const clientUpdated = await clientStore.updateClient(
        client.value,
        client.value.id
      );
      if (clientUpdated.erroMsg) {
        showMessage("Email ou CPF já cadastrado", "#FFF", "error", "danger");
        erroMsgEmail.value.message = clientUpdated.erroMsg.messageEmail;
        erroMsgCPF.value.message = clientUpdated.erroMsg.messageCPF;
        loading.value = false;
        return;
      }
      await clientStore.updateAddress(address.value, address.value.id);
      showMessage("Usuário alterado com sucesso!", "#fff");
      loading.value = false;
      clientStore.getClients();
      closeModal();
    } else {
      // Criando o usuario e retornando o ID
      await rolStore.createRol(rol.value);
      showMessage("eROL cadastrado com sucesso!", "#fff");
      loading.value = false;
      rolStore.getRols();
      closeModal();
    }
  } catch (error) {}
};

function closeModal() {
  show.value = false;
  isSubmitForm.value = false;
  $v.value.rol.$reset();
  emit("close");
}
</script>
