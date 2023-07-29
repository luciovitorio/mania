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
                    user.id
                      ? `Atualizar Usuário: "${props.user.name}"`
                      : "Cadastrar novo Usuário"
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
                      class="md:col-span-2"
                      :class="{
                        'has-error': $v.user.name.$error,
                        'has-success': isSubmitForm && !$v.user.name.$error,
                      }"
                    >
                      <label for="name">Nome</label>
                      <input
                        id="name"
                        type="text"
                        class="form-input"
                        v-model="user.name"
                      />
                      <template v-if="isSubmitForm && $v.user.name.$error">
                        <p class="text-danger mt-1 text-sm">
                          Por favor preencha esse campo
                        </p>
                      </template>
                    </div>

                    <div
                      class="col-span-1"
                      :class="{
                        'has-error': $v.user.branchId.$error,
                        'has-success': isSubmitForm && !$v.user.branchId.$error,
                      }"
                    >
                      <label>Selecione uma filial</label>
                      <select
                        v-model="user.branchId"
                        class="form-select text-white-dark"
                      >
                        <option value="" disabled>Filiais</option>
                        <option
                          v-for="branch in props.branch"
                          :value="branch.id"
                          :key="branch.id"
                        >
                          {{ branch.name }}
                        </option>
                      </select>
                      <template v-if="isSubmitForm && $v.user.branchId.$error">
                        <p class="text-danger mt-1 text-sm">
                          Selecione uma opção
                        </p>
                      </template>
                    </div>

                    <div
                      :class="{
                        'has-error': $v.address.cep.$error,
                        'has-success': isSubmitForm && !$v.address.cep.$error,
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
                      <template v-if="isSubmitForm && $v.address.cep.$error">
                        <p class="text-danger mt-1 text-sm">
                          Por favor preencha esse campo
                        </p>
                      </template>
                    </div>
                  </div>

                  <!-- EMAIL, CPF E DATA NASC -->
                  <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div
                      class="col-span-2"
                      :class="{
                        'has-error': $v.user.email.$error || hasErrorEmail,
                      }"
                    >
                      <label for="email">E-mail</label>
                      <input
                        id="email"
                        type="email"
                        class="form-input"
                        v-model="user.email"
                      />
                      <template v-if="isSubmitForm && $v.user.email.$error">
                        <p class="text-danger mt-1 text-sm">
                          Preencha com um e-mail válido
                        </p>
                      </template>
                      <template v-if="hasErrorEmail">
                        <p class="text-danger mt-1 text-sm">
                          {{ erroMsgEmail.message }}
                        </p>
                      </template>
                    </div>
                    <div
                      :class="{
                        'has-error': $v.user.cpf.$error || hasErrorCPF,
                        'has-success':
                          isSubmitForm && !$v.user.cpf.$error && !hasErrorCPF,
                      }"
                    >
                      <label for="cpf">CPF</label>
                      <input
                        id="cpf"
                        type="text"
                        class="form-input"
                        v-model="cpf"
                        v-maska="'###.###.###-##'"
                      />
                      <template v-if="isSubmitForm && $v.user.cpf.$error">
                        <p class="text-danger mt-1 text-sm">
                          Por favor Preencha esse campo
                        </p>
                      </template>
                      <template v-if="hasErrorCPF">
                        <p class="text-danger mt-1 text-sm">
                          {{ erroMsgCPF.message }}
                        </p>
                      </template>
                    </div>
                    <div>
                      <label for="dtNasc">Data de Nascimento</label>
                      <input
                        id="dtNasc"
                        type="date"
                        class="form-input"
                        v-model="user.dateOfBirth"
                      />
                    </div>
                  </div>

                  <!-- SENHA, CONFIRMAR SENHA, PERFIL E ATIVO -->
                  <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div
                      class="col-span-1"
                      :class="{
                        'has-error':
                          $v.user.password.$error || !arePasswordsMatching,
                        'has-success':
                          isSubmitForm &&
                          !$v.user.password.$error &&
                          arePasswordsMatching,
                      }"
                    >
                      <label for="password">Senha</label>
                      <input
                        id="password"
                        type="password"
                        class="form-input"
                        v-model="user.password"
                      />
                      <template v-if="isSubmitForm && $v.user.password.$error">
                        <p class="text-danger mt-1 text-sm">
                          Por favor, preencha esse campo
                        </p>
                      </template>
                      <template v-if="isSubmitForm && !arePasswordsMatching">
                        <p class="text-danger mt-1 text-sm">
                          As senhas não coincidem
                        </p>
                      </template>
                    </div>

                    <div
                      class="col-span-1"
                      :class="{
                        'has-error':
                          $v.user.confirm_password.$error ||
                          !arePasswordsMatching,
                        'has-success':
                          isSubmitForm &&
                          !$v.user.password.$error &&
                          arePasswordsMatching,
                      }"
                    >
                      <label for="confirm_password">Confirmar Senha</label>
                      <input
                        id="confirm_password"
                        type="password"
                        class="form-input"
                        v-model="user.confirm_password"
                      />
                      <template
                        v-if="isSubmitForm && $v.user.confirm_password.$error"
                      >
                        <p class="text-danger mt-1 text-sm">
                          Por favor, preencha esse campo
                        </p>
                      </template>
                      <template v-if="isSubmitForm && !arePasswordsMatching">
                        <p class="text-danger mt-1 text-sm">
                          As senhas não coincidem
                        </p>
                      </template>
                    </div>

                    <div
                      class="col-span-1"
                      :class="{
                        'has-error': $v.user.profile.$error,
                        'has-success': isSubmitForm && !$v.user.profile.$error,
                      }"
                    >
                      <label for="profile">Perfil do Usuário</label>
                      <select
                        v-model="user.profile"
                        class="form-select text-white-dark"
                      >
                        <option value="" disabled>Selecione o Perfil</option>
                        <option value="ADMIN">Administrador</option>
                        <option value="SUPERVISAO">Supervisão</option>
                        <option value="PASSADEIRA">Passadeira</option>
                      </select>
                      <template v-if="isSubmitForm && $v.user.profile.$error">
                        <p class="text-danger mt-1 text-sm">
                          Selecione uma opção
                        </p>
                      </template>
                    </div>

                    <div class="col-span-1">
                      <label for="isActive">Usuário Ativo?</label>
                      <label
                        for="isActive"
                        class="flex items-center cursor-pointer"
                      >
                        <input
                          id="isActive"
                          v-model="isActive"
                          type="checkbox"
                          class="form-checkbox"
                        />
                        <span class="text-white-dark">Ativo</span>
                      </label>
                    </div>
                  </div>

                  <!-- RUA, NUMERO E COMPLEMENTO -->
                  <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div
                      class="md:col-span-2"
                      :class="{
                        'has-success':
                          isSubmitForm && !$v.address.street.$error,
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
                      <template v-if="isSubmitForm && $v.address.street.$error">
                        <p class="text-danger mt-1 text-sm">
                          Campo obrigatório, verifique o CEP digitado
                        </p>
                      </template>
                    </div>
                    <div
                      :class="{
                        'has-error': $v.address.number.$error,
                        'has-success':
                          isSubmitForm && !$v.address.number.$error,
                      }"
                    >
                      <label for="number">Número</label>
                      <input
                        id="number"
                        type="text"
                        class="form-input"
                        v-model="address.number"
                      />
                      <template v-if="isSubmitForm && $v.address.number.$error">
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
                          isSubmitForm && !$v.address.district.$error,
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
                        v-if="isSubmitForm && $v.address.district.$error"
                      >
                        <p class="text-danger mt-1 text-sm">
                          Campo obrigatório, verifique o CEP digitado
                        </p>
                      </template>
                    </div>
                    <div
                      :class="{
                        'has-success': isSubmitForm && !$v.address.city.$error,
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
                      <template v-if="isSubmitForm && $v.address.city.$error">
                        <p class="text-danger mt-1 text-sm">
                          Campo obrigatório, verifique o CEP digitado
                        </p>
                      </template>
                    </div>
                    <div
                      :class="{
                        'has-success': isSubmitForm && !$v.address.state.$error,
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
                      <template v-if="isSubmitForm && $v.address.state.$error">
                        <p class="text-danger mt-1 text-sm">
                          Campo obrigatório, verifique o CEP digitado
                        </p>
                      </template>
                    </div>
                  </div>

                  <div
                    class="grid grid-cols-1 md:grid-cols-4 gap-5"
                    v-if="user.profile === 'PASSADEIRA'"
                  >
                    <div
                      :class="{
                        'has-error': $v.user.percent.$error,
                        'has-success': isSubmitForm && !$v.user.percent.$error,
                      }"
                    >
                      <label for="percent">Comissão %</label>
                      <input
                        id="percent"
                        type="number"
                        class="form-input"
                        v-model="user.percent"
                      />
                      <template v-if="isSubmitForm && $v.user.percent.$error">
                        <p class="text-danger mt-1 text-sm">
                          Campo obrigatório para o perfil Passadeira
                        </p>
                      </template>
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
                      v-show="!user.id"
                      @click="buttonCreate"
                    >
                      Cadastrar
                    </button>
                    <button
                      type="submit"
                      class="btn btn-primary ml-4 bg-primary/90 hover:bg-primary"
                      v-show="user.id"
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
import { computed, defineEmits, onMounted, onUpdated, ref, watch } from "vue";
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
import { useUserStore } from "@/stores/useUserStore";
import Spinner from "@/components/core/Spinner.vue";

import {
  showMessage,
  removeSpecialCharacters,
  formatCEP,
  formatCPF,
} from "@/utils/utils";

useMeta({ title: "Cadastrar Usuários" });

const userStore = useUserStore();

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
      return console.error(error);
    }
  }
  address.value.street = "";
  address.value.city = "";
  address.value.district = "";
  address.value.state = "";
};

const branches = ref([...props.branch]);

const user = ref({
  ...props.user,
});

const address = ref({
  ...props.address,
});

// Mantendo os campos formatados no front
const formattedCEP = computed(() => {
  return formatCEP(address.value.cep) || null;
});

const formattedCPF = computed(() => {
  return formatCPF(user.value.cpf);
});

// Limpando os campos
const cep = computed({
  get: () => formattedCEP.value,
  set: (value) => (address.value.cep = removeSpecialCharacters(value)),
});

const cpf = computed({
  get: () => formattedCPF.value,
  set: (value) => (user.value.cpf = removeSpecialCharacters(value)),
});

const loading = ref(false);

const props = defineProps({
  modelValue: Boolean,
  branch: {
    type: Array,
  },
  user: {
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
  () => props.user,
  (newUser) => {
    Object.assign(user.value, newUser);
  }
);

watch(
  () => props.address,
  (newAddress) => {
    Object.assign(address.value, newAddress);
  }
);

watch(
  () => props.branch,
  (newBranch) => {
    branches.value = [...newBranch];
  }
);

const isSubmitForm = ref(false);

const erroMsgEmail = ref({
  message: null,
});

const erroMsgCPF = ref({
  message: null,
});

const hasErrorEmail = computed(() => {
  return erroMsgEmail.value.message !== null;
});

const hasErrorCPF = computed(() => {
  return erroMsgCPF.value.message !== null;
});

function isValidProfile(value) {
  const allowedProfiles = ["ADMIN", "SUPERVISAO", "PASSADEIRA"];
  return allowedProfiles.includes(value);
}

const arePasswordsMatching = computed(() => {
  return user.value.password === user.value.confirm_password;
});

const isActive = computed({
  get: () => user.value.isActive === 1,
  set: (value) => (user.value.isActive = value ? 1 : 0),
});

function isValidCPF(value) {
  const cpfRegex = /^\d{11}$/;
  return cpfRegex.test(value);
}

const rules = {
  user: {
    branchId: { required },
    name: { required },
    email: { email },
    password: { required },
    confirm_password: { required },
    sameAsPassword: arePasswordsMatching,
    cpf: { required, isValidCPF },
    dateOfBirth: "",
    profile: { required, isValidProfile },
    percent: {
      required: (value) =>
        user.value.profile === "PASSADEIRA" ? !!value : true,
      numeric,
    },
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

const $v = useVuelidate(rules, { user, address });

const submitForm = async () => {
  isSubmitForm.value = true;
  $v.value.user.$touch();
  $v.value.address.$touch();
  if ($v.value.user.$invalid || $v.value.address.$invalid) {
    return false;
  }

  // Chamando a action no meu useUserStore
  try {
    loading.value = true;
    if (user.value.id) {
      const userUpdated = await userStore.updateUser(user.value, user.value.id);
      if (userUpdated.erroMsg) {
        showMessage("Email ou CPF já cadastrado", "#FFF", "error", "danger");
        erroMsgEmail.value.message = userUpdated.erroMsg.messageEmail;
        erroMsgCPF.value.message = userUpdated.erroMsg.messageCPF;
        loading.value = false;
        return;
      }
      await userStore.updateAddress(address.value, address.value.id);
      showMessage("Usuário alterado com sucesso!", "#fff");
      loading.value = false;
      userStore.getUsers();
      closeModal();
    } else {
      // Criando o usuario e retornando o ID
      const userCreated = await userStore.createUser(user.value, address.value);
      if (userCreated.erroMsg) {
        showMessage("Email ou CPF já cadastrado", "#FFF", "error", "danger");
        erroMsgEmail.value.message = userCreated.erroMsg.messageEmail;
        erroMsgCPF.value.message = userCreated.erroMsg.messageCPF;
        loading.value = false;
        return;
      }
      // Criando o endereço e associando ao ID da filial
      address.value.userId = userCreated.id;
      await userStore.createAddress(address.value);
      showMessage("Usuário cadastrado com sucesso!", "#fff");
      loading.value = false;
      userStore.getUsers();
      closeModal();
    }
  } catch (error) {}
};

function closeModal() {
  show.value = false;
  isSubmitForm.value = false;
  $v.value.user.$reset();
  $v.value.address.$reset();
  erroMsgEmail.value.message = null;
  erroMsgCPF.value.message = null;
  emit("close");
}
</script>
