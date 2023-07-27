<template>
  <GuestLayout
    title="Solicitar nova senha"
    subtitle="Esqueceu sua senha? Entre com seu e-mail para solicitar uma nova"
  >
    <form class="space-y-5" @submit.prevent="submitForm()">
      <div
        :class="{
          'has-error': $v.form.email.$error,
          'has-success': isSubmitForm && !$v.form.email.$error,
        }"
      >
        <label for="email">E-mail</label>
        <input
          id="email"
          type="email"
          class="form-input"
          placeholder="Entre com seu e-mail"
          v-model="form.email"
        />
        <template v-if="isSubmitForm && !$v.form.email.$error">
          <p class="text-[#1abc9c] mt-1"></p>
        </template>
        <template v-if="isSubmitForm && $v.form.email.$error">
          <p class="text-danger mt-1">{{ form.message }}</p>
        </template>
      </div>
      <button type="submit" class="btn btn-primary w-full">Enviar</button>
    </form>
    <div
      class="relative my-7 h-5 text-center before:w-full before:h-[1px] before:absolute before:inset-0 before:m-auto before:bg-[#ebedf2] dark:before:bg-[#253b5c]"
    >
      <div
        class="font-bold text-white-dark bg-white dark:bg-[#0e1726] px-2 relative z-[1] inline-block"
      ></div>
      <p class="text-center">
        Voltar para o
        <router-link
          :to="{ name: 'login' }"
          class="text-primary font-bold hover:underline"
          >Início</router-link
        >
      </p>
    </div>
  </GuestLayout>
</template>

<script lang="ts" setup>
import { ref } from "vue";
import { useVuelidate } from "@vuelidate/core";
import { required, email } from "@vuelidate/validators";
import Swal from "sweetalert2";
import { useMeta } from "../../../composables/use-meta";
import GuestLayout from "../../../components/GuestLayout.vue";
useMeta({ title: "Solicitar nova senha" });

const form = ref({
  email: "",
  message: "",
});

const isSubmitForm = ref(false);

const rules = {
  form: {
    email: { required, email },
  },
};

const $v = useVuelidate(rules, { form });

const submitForm = () => {
  isSubmitForm.value = true;
  $v.value.form.$touch();
  if ($v.value.form.$invalid) {
    $v.value.form.email.email.$invalid
      ? (form.value.message =
          "Por favor, preencha o campo com um e-mail válido")
      : "";
    $v.value.form.email.required.$invalid
      ? (form.value.message = "Por favor, preencha o campo de e-mail")
      : "";
    return false;
  }

  const showMessage = (msg = "", type = "success") => {
    const toast: any = Swal.mixin({
      toast: true,
      position: "top",
      showConfirmButton: false,
      timer: 3000,
      customClass: { container: "toast" },
    });
    toast.fire({
      icon: type,
      title: msg,
      padding: "10px 20px",
    });
  };

  //form validated success
  showMessage("Form submitted successfully.");
};
</script>
