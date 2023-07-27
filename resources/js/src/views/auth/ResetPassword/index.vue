<template>
  <GuestLayout
    title="Configurar nova senha"
    subtitle="Entre com sua nova senha"
  >
    <form class="space-y-5" @submit.prevent="submitForm()">
      <div
        :class="{
          'has-error': $v.form.password.$error,
          'has-success': isSubmitForm && !$v.form.password.$error,
        }"
      >
        <label for="password">Nova senha</label>
        <input
          id="password"
          type="password"
          class="form-input"
          placeholder="Entre com sua nova senha"
          v-model="form.password"
        />
        <template v-if="isSubmitForm && !$v.form.password.$error">
          <p class="text-[#1abc9c] mt-1"></p>
        </template>
        <template v-if="isSubmitForm && $v.form.password.$error">
          <p class="text-danger mt-1">{{ form.message }}</p>
        </template>
      </div>
      <div
        :class="{
          'has-error': $v.form.confirmPassword.$error,
          'has-success': isSubmitForm && !$v.form.confirmPassword.$error,
        }"
      >
        <label for="confirmPassword"></label>
        <input
          id="confirmPassword"
          type="password"
          class="form-input"
          placeholder="Repita a senha"
          v-model="form.confirmPassword"
        />
        <template v-if="isSubmitForm && !$v.form.confirmPassword.$error">
          <p class="text-[#1abc9c] mt-1"></p>
        </template>
        <template v-if="isSubmitForm && $v.form.confirmPassword.$error">
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
import { computed, ref } from "vue";
import { useVuelidate } from "@vuelidate/core";
import { required, sameAs } from "@vuelidate/validators";
import Swal from "sweetalert2";
import { useMeta } from "../../../composables/use-meta";
import GuestLayout from "../../../components/GuestLayout.vue";
useMeta({ title: "Reset de senha" });

const form = ref({
  password: "",
  confirmPassword: "",
  message: "",
});

const isSubmitForm = ref(false);

const rules = computed(() => {
  return {
    form: {
      password: {
        required,
        sameAsConfirmPassword: sameAs(form.value.confirmPassword),
      },
      confirmPassword: {
        required,
        sameAsPassword: sameAs(form.value.password),
      },
    },
  };
});

const $v = useVuelidate(rules, { form });

const submitForm = () => {
  isSubmitForm.value = true;
  $v.value.form.$touch();
  if ($v.value.form.$invalid) {
    $v.value.form.confirmPassword.sameAsPassword.$invalid
      ? (form.value.message = "As senhas não conferem")
      : "";
    $v.value.form.confirmPassword.required.$invalid
      ? (form.value.message = "Por favor, preencha esse campo")
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
