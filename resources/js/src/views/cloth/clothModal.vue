<template>
  <div class="mb-5">
    <!-- Modal -->
    <TransitionRoot appear :show="show" as="template">
      <Dialog as="div" @close="closeModal" class="relative z-50">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <DialogOverlay class="fixed inset-0 bg-[black]/60" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center px-4 py-8">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel
                class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-5xl text-black dark:text-white-dark"
              >
                <header
                  class="text-lg font-bold bg-[#fbfbfb] dark:bg-[#121c2c] ltr:pl-5 rtl:pr-5 py-3 ltr:pr-[50px] rtl:pl-[50px]"
                >
                  {{
                    cloth.id
                      ? `Atualizar Filial: "${props.cloth.name}"`
                      : "Criar nova filial"
                  }}
                </header>
                <button
                  type="button"
                  class="absolute top-4 ltr:right-4 rtl:left-4 text-gray-400 hover:text-gray-800 dark:hover:text-gray-600 outline-none"
                  @click="closeModal"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24px"
                    height="24px"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="w-6 h-6"
                  >
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                  </svg>
                </button>
                <Spinner
                  v-show="loading"
                  class="absolute left-0 top-0 bg-white right-0 bottom-0 flex items-center justify-center"
                />
                <form class="space-y-5 p-5" @submit.prevent="submitForm()">
                  <!-- NOME e TIPO e LOJA -->
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div
                      class="md:col-span-1"
                      :class="{
                        'has-error': $v.cloth.name.$error || hasErrorMsg,
                        'has-success':
                          isSubmitForm && !$v.cloth.name.$error && !hasErrorMsg,
                      }"
                    >
                      <label for="name">Nome da peça de roupa</label>
                      <input
                        id="name"
                        type="text"
                        class="form-input"
                        v-model="cloth.name"
                      />
                      <template v-if="isSubmitForm && ''">
                        <p class="text-danger mt-1 text-sm">
                          Por favor preencha esse campo
                        </p>
                      </template>
                      <template v-if="hasErrorMsg">
                        <p class="text-danger mt-1 text-sm">
                          {{ erroMsg.message }}
                        </p>
                      </template>
                    </div>

                    <div
                      class="col-span-1"
                      :class="{
                        'has-error': $v.cloth.type.$error,
                        'has-success': isSubmitForm && !$v.cloth.type.$error,
                      }"
                    >
                      <label for="profile">Dificuldade</label>
                      <select
                        v-model="cloth.type"
                        class="form-select text-white-dark"
                      >
                        <option value="" disabled>
                          Selecione a dificuldade
                        </option>
                        <option value="EASY">Fácil</option>
                        <option value="HARD">Difícil</option>
                      </select>
                      <template v-if="isSubmitForm && $v.cloth.type.$error">
                        <p class="text-danger mt-1 text-sm">
                          Selecione uma opção
                        </p>
                      </template>
                    </div>

                    <div
                      class="col-span-1"
                      :class="{
                        'has-error': $v.cloth.branchId.$error,
                        'has-success':
                          isSubmitForm && !$v.cloth.branchId.$error,
                      }"
                    >
                      <label>Selecione uma filial</label>
                      <select
                        v-model="cloth.branchId"
                        class="form-select text-white-dark"
                      >
                        <option value="" disabled>Filiais</option>
                        <option
                          v-for="branch in props.branch"
                          :value="branch.id"
                          :key="branch.id"
                        >
                          {{ branch.name }}
                        </option>
                      </select>
                      <template v-if="isSubmitForm && $v.cloth.branchId.$error">
                        <p class="text-danger mt-1 text-sm">
                          Selecione uma opção
                        </p>
                      </template>
                    </div>
                  </div>

                  <footer class="flex justify-end items-center mt-8">
                    <button
                      type="button"
                      @click="closeModal"
                      class="btn btn-outline-danger"
                    >
                      Cancelar
                    </button>
                    <button
                      type="submit"
                      class="btn btn-primary ml-4 bg-primary/90 hover:bg-primary"
                    >
                      {{ cloth.id ? "Atualizar" : "Cadastrar" }}
                    </button>
                  </footer>
                </form>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </div>
</template>

<script setup>
import { computed, defineEmits, ref, watch } from "vue";
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogOverlay,
} from "@headlessui/vue";
import { useMeta } from "@/composables/use-meta";
import { useVuelidate } from "@vuelidate/core";
import { required, email } from "@vuelidate/validators";
import { useClothStore } from "@/stores/useClothStore";
import Spinner from "@/components/core/Spinner.vue";

import { showMessage } from "@/utils/utils";

useMeta({ title: "Cadastrar Roupas" });

const clothStore = useClothStore();

const cloth = ref({ ...props.cloth });
const branches = ref([...props.branch]);

const loading = ref(false);

const props = defineProps({
  modelValue: Boolean,
  branch: {
    type: Array,
  },
  cloth: {
    required: true,
    type: Object,
  },
});

const emit = defineEmits(["update:modelValue", "close"]);

const show = computed({
  get: () => props.modelValue,
  set: (value) => emit("update:modelValue", value),
});

watch(
  () => props.cloth,
  (newCloth) => {
    Object.assign(cloth.value, newCloth);
  }
);

watch(
  () => props.branch,
  (newBranch) => {
    branches.value = [...newBranch];
  }
);

const isSubmitForm = ref(false);

const erroMsg = ref({
  message: null,
});

const hasErrorMsg = computed(() => {
  return erroMsg.value.message !== null;
});

const rules = {
  cloth: {
    name: { required },
    branchId: { required },
    type: { required },
  },
};

const $v = useVuelidate(rules, { cloth });

const submitForm = async () => {
  isSubmitForm.value = true;
  $v.value.cloth.$touch();
  if ($v.value.cloth.$invalid) {
    return false;
  }

  // Chamando a action no meu useBranchStore
  try {
    loading.value = true;
    if (cloth.value.id) {
      const clothUpdated = await clothStore.updateCloth(
        cloth.value,
        cloth.value.id
      );
      if (clothUpdated.erroMsg) {
        showMessage(clothUpdated.erroMsg, "#FFF", "error", "danger");
        erroMsg.value.message = clothUpdated.erroMsg;
        loading.value = false;
        return;
      }
      showMessage("Peça alterada com sucesso!", "#fff");
      loading.value = false;
      clothStore.getClothes();
      closeModal();
    } else {
      // Criando a peça
      const clothCreated = await clothStore.createCloth(cloth.value);
      if (clothCreated.erroMsg) {
        showMessage(clothCreated.erroMsg, "#FFF", "error", "danger");
        erroMsg.value.message = clothCreated.erroMsg;
        loading.value = false;
        return;
      }
      showMessage("Peça cadastrada com sucesso!", "#fff");
      loading.value = false;
      clothStore.getClothes();
      closeModal();
    }
  } catch (error) {}
};

function closeModal() {
  show.value = false;
  isSubmitForm.value = false;
  $v.value.cloth.$reset();
  erroMsg.value.message = null;
  emit("close");
}
</script>
