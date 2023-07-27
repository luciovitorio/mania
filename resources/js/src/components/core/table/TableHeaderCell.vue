<template>
  <th
    @click="emit('click', field)"
    class="border-b-2 p-2 text-left cursor-pointer bg-gray-100"
    :class="field === sortField ? 'bg-blue-50' : ''"
  >
    <div class="flex items-center">
      <slot></slot>
      <div v-if="sortField === field" class="ml-2 flex">
        <!-- SortAsc -->
        <v-icon
          name="bi-sort-down-alt"
          v-if="sortDirection === 'asc'"
          class="h-6 w-6"
        />
        <v-icon name="bi-sort-up" v-else class="h-6 w-6" />
      </div>
    </div>
  </th>
</template>

<script setup>
const { field, sortField, sortDirection } = defineProps({
  field: String,
  sortField: String,
  sortDirection: String,
});

const emit = defineEmits(["click"]);
</script>
