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
                    branch.id
                      ? `Atualizar Filial: "${props.branch.name}"`
                      : "Criar nova filial"
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
                  <!-- NOME e CEP -->
                  <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div
                      class="md:col-span-3"
                      :class="{
                        'has-error': $validation.branch.name.$error,
                        'has-success':
                          isSubmitForm && !$validation.branch.name.$error,
                      }"
                    >
                      <label for="name">Nome</label>
                      <input
                        id="name"
                        type="text"
                        class="form-input"
                        v-model="branch.name"
                      />
                      <template
                        v-if="isSubmitForm && $validation.branch.name.$error"
                      >
                        <p class="text-danger mt-1 text-sm">
                          Por favor preencha esse campo
                        </p>
                      </template>
                    </div>
                    <div
                      :class="{
                        'has-error': $validation.address.cep.$error,
                        'has-success':
                          isSubmitForm && !$validation.address.cep.$error,
                      }"
                    >
                      <label for="cep">CEP</label>
                      <input
                        id="cep"
                        type="text"
                        class="form-input"
                        v-maska="'#####-###'"
                        v-model="cep"
                        @blur="searchCep"
                      />
                      <template
                        v-if="isSubmitForm && $validation.address.cep.$error"
                      >
                        <p class="text-danger mt-1 text-sm">
                          Por favor preencha esse campo
                        </p>
                      </template>
                    </div>
                  </div>

                  <!-- RUA, NUMERO E COMPLEMENTO -->
                  <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div
                      class="md:col-span-2"
                      :class="{
                        'has-success':
                          isSubmitForm && !$validation.address.street.$error,
                      }"
                    >
                      <label for="street">Nome da rua</label>
                      <input
                        id="street"
                        type="text"
                        class="form-input cursor-not-allowed bg-gray-200"
                        v-model="address.street"
                        disabled
                      />
                      <template
                        v-if="isSubmitForm && $validation.address.street.$error"
                      >
                        <p class="text-danger mt-1 text-sm">
                          Campo obrigatório, verifique o CEP digitado
                        </p>
                      </template>
                    </div>
                    <div
                      :class="{
                        'has-error': $validation.address.number.$error,
                        'has-success':
                          isSubmitForm && !$validation.address.number.$error,
                      }"
                    >
                      <label for="number">Número</label>
                      <input
                        id="number"
                        type="text"
                        class="form-input"
                        v-model="address.number"
                      />
                      <template
                        v-if="isSubmitForm && $validation.address.number.$error"
                      >
                        <p class="text-danger mt-1 text-sm">
                          Por favor preencha esse campo
                        </p>
                      </template>
                    </div>
                    <div>
                      <label for="complement">Complemento</label>
                      <input
                        id="complement"
                        type="text"
                        class="form-input"
                        v-model="address.complement"
                      />
                    </div>
                  </div>

                  <!-- BAIRRO, CIDADE E UF -->
                  <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div
                      class="md:col-span-2"
                      :class="{
                        'has-success':
                          isSubmitForm && !$validation.address.district.$error,
                      }"
                    >
                      <label for="district">Bairro</label>
                      <input
                        id="district"
                        type="text"
                        class="form-input cursor-not-allowed bg-gray-200"
                        v-model="address.district"
                        disabled
                      />
                      <template
                        v-if="
                          isSubmitForm && $validation.address.district.$error
                        "
                      >
                        <p class="text-danger mt-1 text-sm">
                          Campo obrigatório, verifique o CEP digitado
                        </p>
                      </template>
                    </div>
                    <div
                      :class="{
                        'has-success':
                          isSubmitForm && !$validation.address.city.$error,
                      }"
                    >
                      <label for="city">Cidade</label>
                      <input
                        id="city"
                        type="text"
                        class="form-input cursor-not-allowed bg-gray-200"
                        v-model="address.city"
                        disabled
                      />
                      <template
                        v-if="isSubmitForm && $validation.address.city.$error"
                      >
                        <p class="text-danger mt-1 text-sm">
                          Campo obrigatório, verifique o CEP digitado
                        </p>
                      </template>
                    </div>
                    <div
                      :class="{
                        'has-success':
                          isSubmitForm && !$validation.address.state.$error,
                      }"
                    >
                      <label for="uf">UF</label>
                      <input
                        id="uf"
                        type="text"
                        class="form-input cursor-not-allowed bg-gray-200"
                        v-model="address.state"
                        disabled
                      />
                      <template
                        v-if="isSubmitForm && $validation.address.state.$error"
                      >
                        <p class="text-danger mt-1 text-sm">
                          Campo obrigatório, verifique o CEP digitado
                        </p>
                      </template>
                    </div>
                  </div>

                  <!-- EMAIL, TEL E CEL -->
                  <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div
                      class="col-span-2"
                      :class="{
                        'has-error':
                          $validation.branch.email.$error || hasErrorMsg,
                      }"
                    >
                      <label for="email">E-mail</label>
                      <input
                        id="email"
                        type="email"
                        class="form-input"
                        v-model="branch.email"
                      />
                      <template
                        v-if="isSubmitForm && $validation.branch.email.$error"
                      >
                        <p class="text-danger mt-1 text-sm">
                          Preencha com um e-mail válido
                        </p>
                      </template>
                      <template v-if="hasErrorMsg">
                        <p class="text-danger mt-1 text-sm">
                          {{ erroMsg.message }}
                        </p>
                      </template>
                    </div>
                    <div>
                      <label for="phone">Telefone comercial</label>
                      <input
                        id="phone"
                        type="text"
                        class="form-input"
                        v-model="phone"
                        v-maska="'(##) ####-####'"
                      />
                    </div>
                    <div>
                      <label for="whatsapp">Celular</label>
                      <input
                        id="whatsapp"
                        type="text"
                        class="form-input"
                        v-model="whatsapp"
                        v-maska="'(##) #####-####'"
                      />
                    </div>
                  </div>

                  <footer class="flex justify-end items-center mt-8">
                    <button
                      type="button"
                      @click="closeModal"
                      class="btn btn-outline-danger"
                    >
                      Cancelar
                    </button>
                    <button
                      type="submit"
                      class="btn btn-primary ml-4 bg-primary/90 hover:bg-primary"
                    >
                      {{ branch.id ? "Atualizar" : "Cadastrar" }}
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
import { computed, defineEmits, onUpdated, ref, watch } from "vue";
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogOverlay,
} from "@headlessui/vue";
import { useMeta } from "@/composables/use-meta";
import { useVuelidate } from "@vuelidate/core";
import { required, email } from "@vuelidate/validators";
import { useBranchStore } from "@/stores/useBranchStore";
import Spinner from "@/components/core/Spinner.vue";

import {
  showMessage,
  removeSpecialCharacters,
  formatCEP,
  formatPhone,
  formatCellphone,
} from "@/utils/utils";

useMeta({ title: "Cadastrar Filiais" });

const branchStore = useBranchStore();

// Buscando CEP
const searchCep = async () => {
  if (cep.value !== null) {
    try {
      const response = await fetch(
        `https://viacep.com.br/ws/${removeSpecialCharacters(
          address.value.cep
        )}/json/`
      );
      const data = await response.json();
      if (data.erro) {
        return showMessage("Cep incorreto", "#fff", "error", "danger");
      }
      address.value.street = data.logradouro;
      address.value.city = data.localidade;
      address.value.district = data.bairro;
      address.value.state = data.uf;
      return;
    } catch (error) {
      console.error(error);
    }
  }
  address.value.street = "";
  address.value.city = "";
  address.value.district = "";
  address.value.state = "";
};

const branch = ref({
  ...props.branch,
});

const address = ref({
  ...props.address,
});

// Mantendo os campos formatados no front
const formattedCEP = computed(() => {
  return formatCEP(address.value.cep) || null;
});

const formattedPhone = computed(() => {
  return formatPhone(branch.value.phone);
});

const formattedCellphone = computed(() => {
  return formatCellphone(branch.value.whatsapp);
});

// Limpando os campos
const cep = computed({
  get: () => formattedCEP.value,
  set: (value) => (address.value.cep = removeSpecialCharacters(value)),
});

const phone = computed({
  get: () => formattedPhone.value,
  set: (value) => (branch.value.phone = removeSpecialCharacters(value)),
});

const whatsapp = computed({
  get: () => formattedCellphone.value,
  set: (value) => (branch.value.whatsapp = removeSpecialCharacters(value)),
});

const loading = ref(false);

const props = defineProps({
  modelValue: Boolean,
  branch: {
    required: true,
    type: Object,
  },
  address: {
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
  () => props.address,
  (newAddress) => {
    Object.assign(address.value, newAddress);
  }
);

watch(
  () => props.branch,
  (newBranch) => {
    Object.assign(branch.value, newBranch);
  }
);

const isSubmitForm = ref(false);

const erroMsg = ref({
  message: null,
});

const hasErrorMsg = computed(() => {
  return erroMsg.value.message !== null;
});

const rules = {
  branch: {
    name: { required },
    email: { email },
    phone: "",
    whatsapp: "",
  },
  address: {
    cep: { required },
    street: { required },
    number: { required },
    complement: "",
    district: { required },
    city: { required },
    state: { required },
  },
};

const $validation = useVuelidate(rules, { branch, address });

const submitForm = async () => {
  isSubmitForm.value = true;
  $validation.value.branch.$touch();
  $validation.value.address.$touch();
  if ($validation.value.branch.$invalid || $validation.value.address.$invalid) {
    return false;
  }

  // Chamando a action no meu useBranchStore
  try {
    loading.value = true;
    if (branch.value.id) {
      const branchUpdated = await branchStore.updateBranch(
        branch.value,
        branch.value.id
      );
      if (branchUpdated.erroMsg) {
        showMessage(branchUpdated.erroMsg, "#FFF", "error", "danger");
        erroMsg.value.message = branchUpdated.erroMsg;
        loading.value = false;
        return;
      }
      await branchStore.updateAddress(address.value, address.value.id);
      showMessage("Filial alterada com sucesso!", "#fff");
      loading.value = false;
      branchStore.getBranches();
      closeModal();
    } else {
      // Criando a filial e retornando o ID
      const branchCreated = await branchStore.createBranch(
        branch.value,
        address.value
      );
      if (branchCreated.erroMsg) {
        showMessage(branchCreated.erroMsg, "#FFF", "error", "danger");
        erroMsg.value.message = branchCreated.erroMsg;
        loading.value = false;
        return;
      }
      // Criando o endereço e associando ao ID da filial
      address.value.branchId = branchCreated.id;
      await branchStore.createAddress(address.value);
      showMessage("Filial cadastrada com sucesso!", "#fff");
      loading.value = false;
      branchStore.getBranches();
      closeModal();
    }
  } catch (error) {}
};

function closeModal() {
  show.value = false;
  isSubmitForm.value = false;
  $validation.value.branch.$reset();
  $validation.value.address.$reset();
  erroMsg.value.message = null;
  emit("close");
}
</script>
