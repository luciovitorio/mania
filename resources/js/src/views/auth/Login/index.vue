<template>
  <GuestLayout
    title="EasyCRM - Módulo de Unidades"
    subtitle="Entre com seu CPF e senha"
  >
    <form class="space-y-5" @submit.prevent="submitForm()">
      <div
        :class="{
          'has-error': $v.loginForm.cpf.$error,
        }"
      >
        <label for="cpf">CPF</label>
        <input
          id="cpf"
          type="text"
          class="form-input"
          placeholder="Entre com seu CPF"
          v-maska="'###.###.###-##'"
          v-model="loginForm.cpf"
        />
        <template v-if="isSubmitForm && $v.loginForm.cpf.$error">
          <p class="text-danger mt-1">Por favor, preencha esse campo</p>
        </template>
      </div>
      <div
        :class="{
          'has-error': $v.loginForm.password.$error,
        }"
      >
        <label for="password">Senha</label>
        <input
          id="password"
          type="password"
          class="form-input"
          placeholder="Entre com sua senha"
          v-model="loginForm.password"
        />
        <template v-if="isSubmitForm && $v.loginForm.password.$error">
          <p class="text-danger mt-1">Por favor, preencha esse campo</p>
        </template>
      </div>
      <div>
        <button
          type="submit"
          class="btn btn-primary w-full"
          :disabled="loading"
          :class="{
            'cursor-not-allowed': loading,
            'hover:bg-indigo-500': loading,
          }"
        >
          <svg
            v-if="loading"
            viewBox="0 0 24 24"
            width="24"
            height="24"
            stroke="currentColor"
            stroke-width="1.5"
            fill="none"
            stroke-linecap="round"
            stroke-linejoin="round"
            class="w-5 h-5 animate-[spin_2s_linear_infinite] inline-block align-middle ltr:mr-2 rtl:ml-2"
          >
            <line x1="12" y1="2" x2="12" y2="6"></line>
            <line x1="12" y1="18" x2="12" y2="22"></line>
            <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
            <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
            <line x1="2" y1="12" x2="6" y2="12"></line>
            <line x1="18" y1="12" x2="22" y2="12"></line>
            <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
            <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
          </svg>
          <span
            class="absolute left-0 inset-y-0 flex items-center pl-3"
            v-if="!loading"
          >
            <v-icon
              name="fa-lock"
              class="h-5 w-5 text-white group-hover:text-indigo-400"
            />
          </span>
          Entrar
        </button>
      </div>
    </form>
    <div
      class="relative my-7 h-5 text-center before:w-full before:h-[1px] before:absolute before:inset-0 before:m-auto before:bg-[#ebedf2] dark:before:bg-[#253b5c]"
    >
      <div
        class="font-bold text-white-dark bg-white dark:bg-[#0e1726] px-2 relative z-[1] inline-block"
      ></div>
      <p class="text-center">
        Não lembra sua senha?
        <router-link
          :to="{ name: 'requestPassword' }"
          class="text-primary font-bold hover:underline"
          >Solicite nova senha</router-link
        >
      </p>
    </div>
  </GuestLayout>
</template>

<script lang="ts" setup>
import { ref } from "vue";
import { useVuelidate } from "@vuelidate/core";
import { required } from "@vuelidate/validators";
import { useMeta } from "../../../composables/use-meta";
import GuestLayout from "../../../components/GuestLayout.vue";
import { useAuthStore } from "@/stores/useAuthStore";
import { showMessage } from "../../../utils/utils";

// import router from "../../../router";
import { useRouter } from "vue-router";
useMeta({ title: "Login" });

const router = useRouter();

const store = useAuthStore();

let loading = ref(false);
let errorMsg = ref("");

const loginForm = ref({
  cpf: "",
  password: "",
});

const isSubmitForm = ref(false);

const rules = {
  loginForm: {
    cpf: { required },
    password: { required },
  },
};

const $v = useVuelidate(rules, { loginForm });

// Removendo os . e - do campo CPF
function removeSpecialCharacters(value) {
  return value.replace(/[\.-]/g, "");
}

const submitForm = () => {
  isSubmitForm.value = true;
  $v.value.loginForm.$touch();
  if ($v.value.loginForm.$invalid) {
    return false;
  }

  const cleanedCPF = removeSpecialCharacters(loginForm.value.cpf);
  loading.value = true;
  store
    .login({ cpf: cleanedCPF, password: loginForm.value.password })
    .then(() => {
      loading.value = false;
      showMessage("Login realizado com sucesso!", "#fff");
      router.push({ name: "dashboard" });
    })
    .catch(({ response }) => {
      loading.value = false;
      errorMsg.value = response.data.message;
      showMessage(errorMsg.value, "#fff", "error", "danger");
    });
};
</script>
