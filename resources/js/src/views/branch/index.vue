<template>
  <div>
    <ul class="flex space-x-2 rtl:space-x-reverse">
      <li>
        <router-link
          :to="{ name: 'dashboard' }"
          class="text-primary hover:underline"
          >Dashboard</router-link
        >
      </li>
      <li class="before:content-['/'] before:mr-2">
        <span>Filiais</span>
      </li>
    </ul>

    <BranchTable title="Cadastro de Filiais" buttonTitle="Cadastrar Filial" />
  </div>
</template>
<script lang="ts" setup>
import BranchTable from "@/views/branch/branchTable.vue";
</script>
