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
                    plan.id
                      ? `Atualizar Usuário: "${props.plan.name}"`
                      : "Cadastrar novo Plano"
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
                  <!-- NOME e QTD DE PEÇAS -->
                  <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div
                      class="md:col-span-2"
                      :class="{
                        'has-error': $v.plan.name.$error || hasErrorMsg,
                        'has-success':
                          isSubmitForm && !$v.plan.name.$error && !hasErrorMsg,
                      }"
                    >
                      <label for="name">Nome</label>
                      <input
                        id="name"
                        type="text"
                        class="form-input"
                        v-model="plan.name"
                      />
                      <template v-if="isSubmitForm && $v.plan.name.$error">
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
                      class="md:col-span-1"
                      :class="{
                        'has-error': $v.plan.pieceQuantity.$error,
                        'has-success':
                          isSubmitForm && !$v.plan.pieceQuantity.$error,
                      }"
                    >
                      <label for="pieceQuantity">Quantidade total peças</label>
                      <input
                        id="pieceQuantity"
                        type="number"
                        class="form-input"
                        v-model="plan.pieceQuantity"
                      />
                      <template
                        v-if="isSubmitForm && $v.plan.pieceQuantity.$error"
                      >
                        <p class="text-danger mt-1 text-sm">
                          Por favor preencha esse campo
                        </p>
                      </template>
                    </div>

                    <div class="col-span-1">
                      <label for="isActive">Plano Ativo?</label>
                      <label
                        for="isActive"
                        class="flex items-center cursor-pointer"
                      >
                        <input
                          id="isActive"
                          v-model="isActive"
                          type="checkbox"
                          class="form-checkbox"
                        />
                        <span class="text-white-dark">Ativo</span>
                      </label>
                    </div>
                  </div>

                  <!-- QTD PÇ SIMPLES, VL PÇ SIMPLES E VL PÇ SM ADICIONAL -->
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div
                      class="md:col-span-1"
                      :class="{
                        'has-error':
                          $v.plan.simplePieceQuantity.$error ||
                          !isPiecesTotalValid,
                        'has-success':
                          isSubmitForm && !$v.plan.simplePieceQuantity.$error,
                      }"
                    >
                      <label for="simplePieceQuantity"
                        >Quantidade peças simples</label
                      >
                      <input
                        id="simplePieceQuantity"
                        type="number"
                        class="form-input"
                        v-model="plan.simplePieceQuantity"
                      />
                      <template
                        v-if="
                          isSubmitForm && $v.plan.simplePieceQuantity.$error
                        "
                      >
                        <p class="text-danger mt-1 text-sm">
                          Por favor preencha esse campo
                        </p>
                      </template>
                      <template v-if="!isPiecesTotalValid">
                        <p class="text-danger mt-1 text-sm">
                          Por favor verifique a quantidade total de peças
                        </p>
                      </template>
                    </div>

                    <div
                      :class="{
                        'has-error': $v.plan.simplePieceValue.$error,
                        'has-success':
                          isSubmitForm && !$v.plan.simplePieceValue.$error,
                      }"
                    >
                      <label for="simplePieceValue">Valor peça simples </label>
                      <moneyInput
                        id="simplePieceValue"
                        type="text"
                        class="form-input"
                        :options="moneyConfig"
                        v-model="plan.simplePieceValue"
                      />
                      <template
                        v-if="isSubmitForm && $v.plan.simplePieceValue.$error"
                      >
                        <p class="text-danger mt-1 text-sm">
                          Por favor preencha esse campo
                        </p>
                      </template>
                    </div>

                    <div
                      :class="{
                        'has-error': $v.plan.additionalSimplePieceValue.$error,
                        'has-success':
                          isSubmitForm &&
                          !$v.plan.additionalSimplePieceValue.$error,
                      }"
                    >
                      <label for="additionalSimplePieceValue"
                        >Valor peça simples adcional</label
                      >
                      <moneyInput
                        id="additionalSimplePieceValue"
                        type="text"
                        class="form-input"
                        :options="moneyConfig"
                        v-model="plan.additionalSimplePieceValue"
                      />
                      <template
                        v-if="
                          isSubmitForm &&
                          $v.plan.additionalSimplePieceValue.$error
                        "
                      >
                        <p class="text-danger mt-1 text-sm">
                          Por favor preencha esse campo
                        </p>
                      </template>
                    </div>
                  </div>

                  <!-- QTD PÇ DIFÍCIL, VL PÇ DIFÍCIL E VL PÇ SM ADICIONAL -->
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div
                      class="md:col-span-1"
                      :class="{
                        'has-error':
                          $v.plan.difficultPieceQuantity.$error ||
                          !isPiecesTotalValid,
                        'has-success':
                          isSubmitForm &&
                          !$v.plan.difficultPieceQuantity.$error,
                      }"
                    >
                      <label for="difficultPieceQuantity"
                        >Quantidade peças difíceis</label
                      >
                      <input
                        id="difficultPieceQuantity"
                        type="number"
                        class="form-input"
                        v-model="plan.difficultPieceQuantity"
                      />
                      <template
                        v-if="
                          isSubmitForm && $v.plan.difficultPieceQuantity.$error
                        "
                      >
                        <p class="text-danger mt-1 text-sm">
                          Por favor preencha esse campo
                        </p>
                      </template>
                      <template v-if="!isPiecesTotalValid">
                        <p class="text-danger mt-1 text-sm">
                          Por favor verifique a quantidade total de peças
                        </p>
                      </template>
                    </div>

                    <div
                      :class="{
                        'has-error': $v.plan.difficultPieceValue.$error,
                        'has-success':
                          isSubmitForm && !$v.plan.difficultPieceValue.$error,
                      }"
                    >
                      <label for="difficultPieceValue"
                        >Valor peça difíceis
                      </label>
                      <moneyInput
                        id="difficultPieceValue"
                        type="text"
                        class="form-input"
                        :options="moneyConfig"
                        v-model="plan.difficultPieceValue"
                      />
                      <template
                        v-if="
                          isSubmitForm && $v.plan.difficultPieceValue.$error
                        "
                      >
                        <p class="text-danger mt-1 text-sm">
                          Por favor preencha esse campo
                        </p>
                      </template>
                    </div>

                    <div
                      :class="{
                        'has-error':
                          $v.plan.additionalDifficultPieceValue.$error,
                        'has-success':
                          isSubmitForm &&
                          !$v.plan.additionalDifficultPieceValue.$error,
                      }"
                    >
                      <label for="additionalDifficultPieceValue"
                        >Valor peça difícil adcional</label
                      >
                      <moneyInput
                        id="additionalDifficultPieceValue"
                        type="text"
                        class="form-input"
                        :options="moneyConfig"
                        v-model="plan.additionalDifficultPieceValue"
                      />
                      <template
                        v-if="
                          isSubmitForm &&
                          $v.plan.additionalDifficultPieceValue.$error
                        "
                      >
                        <p class="text-danger mt-1 text-sm">
                          Por favor preencha esse campo
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
                      v-show="!plan.id"
                      @click="buttonCreate"
                    >
                      Cadastrar
                    </button>
                    <button
                      type="submit"
                      class="btn btn-primary ml-4 bg-primary/90 hover:bg-primary"
                      v-show="plan.id"
                      @click="buttonEdit"
                    >
                      Atualizar
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
import { computed, defineEmits, onUpdated, ref, watch } from "vue";
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogOverlay,
} from "@headlessui/vue";
import { useMeta } from "@/composables/use-meta";
import { useVuelidate } from "@vuelidate/core";
import { required } from "@vuelidate/validators";
import { usePlanStore } from "@/stores/usePlanStore";
import Spinner from "@/components/core/Spinner.vue";
import moneyInput from "../../components/core/input/moneyInput.vue";
import { showMessage } from "@/utils/utils";

useMeta({ title: "Cadastrar Planos" });

const planStore = usePlanStore();

const moneyConfig = {
  locale: "pt-BR",
  currency: "BRL",
  currencyDisplay: "symbol",
  hideCurrencySymbolOnFocus: true,
  hideGroupingSeparatorOnFocus: true,
  hideNegligibleDecimalDigitsOnFocus: false,
  autoDecimalDigits: true,
  useGrouping: true,
  accountingSign: false,
};

const plan = ref({ ...props.plan });

const loading = ref(false);

const props = defineProps({
  modelValue: Boolean,
  plan: {
    required: true,
    type: Object,
  },
});

const emit = defineEmits(["update:modelValue", "close"]);

const show = computed({
  get: () => props.modelValue,
  set: (value) => emit("update:modelValue", value),
});

// Tratando a msg de erro gerada pelo back
const erroMsg = ref({
  message: null,
});

const hasErrorMsg = computed(() => {
  return erroMsg.value.message !== null;
});

const isPiecesTotalValid = computed(() => {
  const totalPieces = Number(plan.value.pieceQuantity);
  const simplePieces = Number(plan.value.simplePieceQuantity);
  const difficultPieces = Number(plan.value.difficultPieceQuantity);
  return totalPieces === simplePieces + difficultPieces;
});

const isSubmitForm = ref(false);

const isActive = computed({
  get: () => plan.value.isActive === 1,
  set: (value) => (plan.value.isActive = value ? 1 : 0),
});

const customRequired = (value) => {
  if (value) {
    return true;
  }
  return false;
};

const rules = {
  plan: {
    name: { required },
    pieceQuantity: { required },
    simplePieceQuantity: { required },
    difficultPieceQuantity: { required },
    simplePieceValue: { customRequired },
    difficultPieceValue: { customRequired },
    additionalSimplePieceValue: { customRequired },
    additionalDifficultPieceValue: { customRequired },
  },
};

const $v = useVuelidate(rules, { plan });

watch(
  () => props.plan,
  (newPlan) => {
    Object.assign(plan.value, newPlan);
  }
);

const submitForm = async () => {
  isSubmitForm.value = true;
  $v.value.plan.$touch();
  if ($v.value.plan.$invalid) {
    return false;
  }

  // Chamando a action no meu useUserStore
  try {
    loading.value = true;
    if (plan.value.id) {
      const planUpdated = await planStore.updatePlan(plan.value, plan.value.id);
      if (planUpdated.erroMsg) {
        showMessage(planUpdated.erroMsg.message, "#FFF", "error", "danger");
        erroMsg.value.message = planUpdated.erroMsg.message;
        loading.value = false;
        return;
      }
      showMessage("Plano alterado com sucesso!", "#fff");
      loading.value = false;
      planStore.getPlans();
      closeModal();
    } else {
      // Criando o plano
      const planCreated = await planStore.createPlan(plan.value);
      if (planCreated.erroMsg) {
        showMessage(planCreated.erroMsg.message, "#FFF", "error", "danger");
        erroMsg.value.message = planCreated.erroMsg.message;
        loading.value = false;
        return;
      }
      // Criando o endereço e associando ao ID da filial
      showMessage("Plano cadastrado com sucesso!", "#fff");
      loading.value = false;
      planStore.getPlans();
      closeModal();
    }
  } catch (error) {
    console.log(error);
  }
};

function closeModal() {
  show.value = false;
  isSubmitForm.value = false;
  erroMsg.value.message = null;
  $v.value.plan.$reset();
  emit("close");
}
</script>
