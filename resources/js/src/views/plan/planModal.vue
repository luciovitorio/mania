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
                        'has-error': $v.plan.name.$error,
                        'has-success': isSubmitForm && !$v.plan.name.$error,
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
                        'has-error': $v.plan.simplePieceQuantity.$error,
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
                    </div>

                    <div
                      :class="{
                        'has-error': $v.plan.simplePieceValue.$error,
                        'has-success':
                          isSubmitForm && !$v.plan.simplePieceValue.$error,
                      }"
                    >
                      <label for="simplePieceValue">Valor peça simples </label>
                      <input
                        id="simplePieceValue"
                        type="text"
                        class="form-input"
                        v-money="moneyConfig"
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
                      <input
                        id="additionalSimplePieceValue"
                        type="text"
                        class="form-input"
                        v-money="moneyConfig"
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
                        'has-error': $v.plan.difficultPieceQuantity.$error,
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
                      <input
                        id="difficultPieceValue"
                        type="text"
                        class="form-input"
                        v-money="moneyConfig"
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
                      <input
                        id="additionalDifficultPieceValue"
                        type="text"
                        class="form-input"
                        v-money="moneyConfig"
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
import { computed, defineEmits, onUpdated, ref } from "vue";
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

import { showMessage } from "@/utils/utils";

useMeta({ title: "Cadastrar Planos" });

const planStore = usePlanStore();

const moneyConfig = {
  prefix: "R$ ",
  thousands: ".",
  decimal: ",",
  precision: 2,
  masked: false, // Define como `false` para manter apenas o valor numérico no modelo (sem o "R$")
};

const plan = ref({
  id: props.plan.id,
  name: props.plan.name,
  pieceQuantity: props.plan.pieceQuantity,
  simplePieceQuantity: props.plan.simplePieceQuantity,
  simplePieceValue: props.plan.simplePieceValue,
  difficultPieceQuantity: props.plan.difficultPieceQuantity,
  difficultPieceValue: props.plan.difficultPieceValue,
  additionalSimplePieceValue: props.plan.additionalSimplePieceValue,
  additionalDifficultPieceValue: props.plan.additionalDifficultPieceValue,
  isActive: props.plan.isActive,
});

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

const isSubmitForm = ref(false);

const isActive = computed({
  get: () => plan.value.isActive === 1,
  set: (value) => (plan.value.isActive = value ? 1 : 0),
});

const customRequired = (value) => {
  // Verifica se o valor não é "R$ 0,00"
  if (typeof value === "string" && value.trim() !== "R$ 0,00") {
    return true;
  }
  // Se for "R$ 0,00", considera o campo não preenchido
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

onUpdated(() => {
  plan.value = {
    id: props.plan.id,
    name: props.plan.name,
    pieceQuantity: props.plan.pieceQuantity,
    simplePieceQuantity: props.plan.simplePieceQuantity,
    simplePieceValue: props.plan.simplePieceValue,
    difficultPieceQuantity: props.plan.difficultPieceQuantity,
    difficultPieceValue: props.plan.difficultPieceValue,
    additionalSimplePieceValue: props.plan.additionalSimplePieceValue,
    additionalDifficultPieceValue: props.plan.additionalDifficultPieceValue,
    isActive: props.plan.isActive,
  };
});

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
      console.log("atualizar");
      const userUpdated = await userStore.updateUser(user.value, user.value.id);
      if (userUpdated.erroMsg) {
        console.log("entrou erro");
        showMessage("Email ou CPF já cadastrado", "#FFF", "error", "danger");
        erroMsgEmail.value.message = userUpdated.erroMsg.messageEmail;
        erroMsgCPF.value.message = userUpdated.erroMsg.messageCPF;
        loading.value = false;
        return;
      }
      await userStore.updateAddress(address.value, address.value.id);
      showMessage("Usuário alterado com sucesso!", "#fff");
      loading.value = false;
      userStore.getUsers();
      closeModal();
    } else {
      // Criando o plano
      console.log(plan.value);
      const planCreated = await planStore.createPlan(plan.value);
      if (planCreated.erroMsg) {
        showMessage("Email ou CPF já cadastrado", "#FFF", "error", "danger");
        erroMsgEmail.value.message = userCreated.erroMsg.messageEmail;
        erroMsgCPF.value.message = userCreated.erroMsg.messageCPF;
        loading.value = false;
        return;
      }
      // Criando o endereço e associando ao ID da filial
      address.value.userId = userCreated.id;
      console.log(address.value.userId);
      await userStore.createAddress(address.value);
      showMessage("Usuário cadastrado com sucesso!", "#fff");
      loading.value = false;
      userStore.getUsers();
      closeModal();
    }
  } catch (error) {}
};

function closeModal() {
  show.value = false;
  isSubmitForm.value = false;
  $v.value.plan.$reset();
  emit("close");
}
</script>
