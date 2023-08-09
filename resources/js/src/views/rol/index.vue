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
        <span>Gerar ROL</span>
      </li>
    </ul>
    <RolTable title="Cadastro de ROLs" buttonTitle="Cadastrar ROL" />
  </div>
</template>
<script lang="ts" setup>
import { onMounted } from "vue";
import RolTable from "@/views/rol/rolTable.vue";
import { useAuthStore } from "@/stores/useAuthStore";

const authStore = useAuthStore();

onMounted(() => {
  authStore.getUser;
});
</script>
