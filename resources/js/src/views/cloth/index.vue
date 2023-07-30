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
        <span>Roupas</span>
      </li>
    </ul>

    <ClothTable title="Cadastro de Roupas" buttonTitle="Cadastrar Roupa" />
  </div>
</template>
<script lang="ts" setup>
import ClothTable from "@/views/cloth/clothTable.vue";
</script>
