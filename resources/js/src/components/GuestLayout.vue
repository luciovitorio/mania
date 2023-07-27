<template>
  <div
    class="flex justify-center items-center min-h-screen bg-[url('/assets/images/map.svg')] dark:bg-[url('/assets/images/map-dark.svg')] bg-cover bg-center"
  >
    <div class="panel sm:w-[480px] m-6 max-w-lg w-full">
      <h2 class="font-bold text-2xl mb-3">{{ title }}</h2>
      <p class="mb-7">{{ subtitle }}</p>
      <slot></slot>
    </div>
  </div>
</template>

<script lang="ts" setup>
const { title, subtitle } = defineProps({
  title: String,
  subtitle: String,
});
</script>
