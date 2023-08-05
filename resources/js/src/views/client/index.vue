<template>
  <div>
    <ul class="flex space-x-2">
      <li>
        <router-link
          :to="{ name: 'dashboard' }"
          class="text-primary hover:underline"
          >Dashboard</router-link
        >
      </li>
      <li class="before:content-['/'] before:mr-2">
        <span>Clientes</span>
      </li>
    </ul>
    <ClientTable title="Cadastro de Clientes" buttonTitle="Cadastrar Cliente" />
  </div>
</template>
<script lang="ts" setup>
import { onMounted } from "vue";
import ClientTable from "@/views/client/clientTable.vue";
import { useAuthStore } from "@/stores/useAuthStore";

const authStore = useAuthStore();

onMounted(() => {
  authStore.getUser;
});
</script>
