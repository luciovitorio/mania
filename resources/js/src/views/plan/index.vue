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
        <span>Planos</span>
      </li>
    </ul>
    <PlanTable title="Cadastro de Planos" buttonTitle="Cadastrar Plano" />
  </div>
</template>
<script lang="ts" setup>
import { onMounted } from "vue";
import { useAuthStore } from "@/stores/useAuthStore";
import PlanTable from "@/views/plan/planTable.vue";

const authStore = useAuthStore();

onMounted(() => {
  authStore.getUser;
});
</script>
