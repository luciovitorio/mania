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
        <span>Usuários</span>
      </li>
    </ul>
    <UserTable title="Cadastro de Usuários" buttonTitle="Cadastrar Usuário" />
  </div>
</template>
<script lang="ts" setup>
import { onMounted } from "vue";
import UserTable from "@/views/user/userTable.vue";
import { useAuthStore } from "@/stores/useAuthStore";
// import BranchTable from "@/views/branch/branchTable.vue";

const authStore = useAuthStore();

onMounted(() => {
  authStore.getUser;
});
</script>
