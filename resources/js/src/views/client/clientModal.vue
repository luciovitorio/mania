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
                    client.id
                      ? `Atualizar Cliente: "${props.client.name}"`
                      : "Cadastrar novo Cliente"
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
                  <!-- NOME e CEP e FILIAL -->
                  <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div
                      class="md:col-span-2"
                      :class="{
                        'has-error': $v.client.name.$error,
                        'has-success': isSubmitForm && !$v.client.name.$error,
                      }"
                    >
                      <label for="name">Nome</label>
                      <input
                        id="name"
                        type="text"
                        class="form-input"
                        v-model="client.name"
                      />
                      <template v-if="isSubmitForm && $v.client.name.$error">
                        <p class="text-danger mt-1 text-sm">
                          Por favor preencha esse campo
                        </p>
                      </template>
                    </div>

                    <div
                      class="col-span-1"
                      :class="{
                        'has-error': $v.client.branchId.$error,
                        'has-success':
                          isSubmitForm && !$v.client.branchId.$error,
                      }"
                    >
                      <label>Selecione uma filial</label>
                      <select
                        v-model="client.branchId"
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
                      <template
                        v-if="isSubmitForm && $v.client.branchId.$error"
                      >
                        <p class="text-danger mt-1 text-sm">
                          Selecione uma opção
                        </p>
                      </template>
                    </div>

                    <div
                      :class="{
                        'has-error': $v.address.cep.$error,
                        'has-success': isSubmitForm && !$v.address.cep.$error,
                      }"
                    >
                      <label for="cep">CEP</label>
                      <input
                        id="cep"
                        type="text"
                        class="form-input"
                        v-maska="'#####-###'"
                        v-model="cep"
                        @blur="searchCep"
                      />
                      <template v-if="isSubmitForm && $v.address.cep.$error">
                        <p class="text-danger mt-1 text-sm">
                          Por favor preencha esse campo
                        </p>
                      </template>
                    </div>
                  </div>

                  <!-- EMAIL, PLANO, CPF E DATA NASC -->
                  <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div
                      class="col-span-1"
                      :class="{
                        'has-success':
                          isSubmitForm &&
                          !$v.client.email.$error &&
                          !hasErrorEmail,
                        'has-error': $v.client.email.$error || hasErrorEmail,
                      }"
                    >
                      <label for="email">E-mail</label>
                      <input
                        id="email"
                        type="email"
                        class="form-input"
                        v-model="client.email"
                      />
                      <template v-if="isSubmitForm && $v.client.email.$error">
                        <p class="text-danger mt-1 text-sm">
                          Preencha com um e-mail válido
                        </p>
                      </template>
                      <template v-if="hasErrorEmail">
                        <p class="text-danger mt-1 text-sm">
                          {{ erroMsgEmail.message }}
                        </p>
                      </template>
                    </div>

                    <div
                      :class="{
                        'has-error': $v.client.cpf.$error || hasErrorCPF,
                        'has-success':
                          isSubmitForm && !$v.client.cpf.$error && !hasErrorCPF,
                      }"
                    >
                      <label for="cpf">CPF</label>
                      <input
                        id="cpf"
                        type="text"
                        class="form-input"
                        v-model="cpf"
                        v-maska="'###.###.###-##'"
                      />
                      <template v-if="isSubmitForm && $v.client.cpf.$error">
                        <p class="text-danger mt-1 text-sm">
                          Por favor Preencha esse campo
                        </p>
                      </template>
                      <template v-if="hasErrorCPF">
                        <p class="text-danger mt-1 text-sm">
                          {{ erroMsgCPF.message }}
                        </p>
                      </template>
                    </div>

                    <div
                      class="col-span-1"
                      :class="{
                        'has-error': $v.client.planId.$error,
                        'has-success': isSubmitForm && !$v.client.planId.$error,
                      }"
                    >
                      <label>Selecione um plano</label>
                      <select
                        v-model="client.planId"
                        class="form-select text-white-dark"
                      >
                        <option value="" disabled>Planos</option>
                        <option
                          v-for="plan in props.plan"
                          :value="plan.id"
                          :key="plan.id"
                        >
                          {{ plan.name }}
                        </option>
                      </select>
                      <template v-if="isSubmitForm && $v.client.planId.$error">
                        <p class="text-danger mt-1 text-sm">
                          Selecione uma opção
                        </p>
                      </template>
                    </div>

                    <div
                      :class="{
                        'has-success': isSubmitForm,
                      }"
                    >
                      <label for="dtNasc">Data de Nascimento</label>
                      <input
                        id="dtNasc"
                        type="date"
                        class="form-input"
                        v-model="client.dateOfBirth"
                      />
                    </div>
                  </div>

                  <!-- RG, TELEFONE RESIDENCIAL, TELEFONE CELULAR, ATIVO -->
                  <div class="grid grid-cols-1 md:grid-cols-4 gap-5 pb-4">
                    <div
                      class="col-span-1"
                      :class="{
                        'has-error': $v.client.rg.$error,
                        'has-success': isSubmitForm && !$v.client.rg.$error,
                      }"
                    >
                      <label for="rg">RG</label>
                      <input
                        id="rg"
                        type="text"
                        class="form-input"
                        v-model="client.rg"
                      />
                      <template v-if="isSubmitForm && $v.client.rg.$error">
                        <p class="text-danger mt-1 text-sm">
                          Por favor, preencha esse campo
                        </p>
                      </template>
                    </div>

                    <div
                      class="col-span-1"
                      :class="{
                        'has-error': $v.client.rg.$error,
                        'has-success': isSubmitForm && !$v.client.rg.$error,
                      }"
                    >
                      <label for="homePhone">Telefone Residencial</label>
                      <input
                        id="homePhone"
                        type="text"
                        class="form-input"
                        v-model="homePhone"
                        v-maska="'(##) ####-####'"
                      />
                      <template
                        v-if="isSubmitForm && $v.client.homePhone.$error"
                      >
                        <p class="text-danger mt-1 text-sm">
                          Por favor, preencha esse campo
                        </p>
                      </template>
                    </div>

                    <div
                      class="col-span-1"
                      :class="{
                        'has-error': $v.client.rg.$error,
                        'has-success': isSubmitForm && !$v.client.rg.$error,
                      }"
                    >
                      <label for="cellPhone">Telefone Celular</label>
                      <input
                        id="cellPhone"
                        type="text"
                        class="form-input"
                        v-model="cellPhone"
                        v-maska="'(##) #####-####'"
                      />
                      <template
                        v-if="isSubmitForm && $v.client.cellPhone.$error"
                      >
                        <p class="text-danger mt-1 text-sm">
                          Por favor, preencha esse campo
                        </p>
                      </template>
                    </div>

                    <div class="col-span-1">
                      <label for="isActive">Cliente Ativo?</label>
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
                  <hr />

                  <!-- RUA, NUMERO E COMPLEMENTO -->
                  <div class="grid grid-cols-1 md:grid-cols-4 gap-5 pt-4">
                    <div
                      class="md:col-span-2"
                      :class="{
                        'has-success':
                          isSubmitForm && !$v.address.street.$error,
                      }"
                    >
                      <label for="street">Nome da rua</label>
                      <input
                        id="street"
                        type="text"
                        class="form-input cursor-not-allowed bg-gray-200"
                        v-model="address.street"
                        disabled
                      />
                      <template v-if="isSubmitForm && $v.address.street.$error">
                        <p class="text-danger mt-1 text-sm">
                          Campo obrigatório, verifique o CEP digitado
                        </p>
                      </template>
                    </div>
                    <div
                      :class="{
                        'has-error': $v.address.number.$error,
                        'has-success':
                          isSubmitForm && !$v.address.number.$error,
                      }"
                    >
                      <label for="number">Número</label>
                      <input
                        id="number"
                        type="text"
                        class="form-input"
                        v-model="address.number"
                      />
                      <template v-if="isSubmitForm && $v.address.number.$error">
                        <p class="text-danger mt-1 text-sm">
                          Por favor preencha esse campo
                        </p>
                      </template>
                    </div>
                    <div
                      :class="{
                        'has-success': isSubmitForm,
                      }"
                    >
                      <label for="complement">Complemento</label>
                      <input
                        id="complement"
                        type="text"
                        class="form-input"
                        v-model="address.complement"
                      />
                    </div>
                  </div>

                  <!-- BAIRRO, CIDADE E UF -->
                  <div class="grid grid-cols-1 md:grid-cols-4 gap-5 pb-4">
                    <div
                      class="md:col-span-2"
                      :class="{
                        'has-success':
                          isSubmitForm && !$v.address.district.$error,
                      }"
                    >
                      <label for="district">Bairro</label>
                      <input
                        id="district"
                        type="text"
                        class="form-input cursor-not-allowed bg-gray-200"
                        v-model="address.district"
                        disabled
                      />
                      <template
                        v-if="isSubmitForm && $v.address.district.$error"
                      >
                        <p class="text-danger mt-1 text-sm">
                          Campo obrigatório, verifique o CEP digitado
                        </p>
                      </template>
                    </div>
                    <div
                      :class="{
                        'has-success': isSubmitForm && !$v.address.city.$error,
                      }"
                    >
                      <label for="city">Cidade</label>
                      <input
                        id="city"
                        type="text"
                        class="form-input cursor-not-allowed bg-gray-200"
                        v-model="address.city"
                        disabled
                      />
                      <template v-if="isSubmitForm && $v.address.city.$error">
                        <p class="text-danger mt-1 text-sm">
                          Campo obrigatório, verifique o CEP digitado
                        </p>
                      </template>
                    </div>
                    <div
                      :class="{
                        'has-success': isSubmitForm && !$v.address.state.$error,
                      }"
                    >
                      <label for="uf">UF</label>
                      <input
                        id="uf"
                        type="text"
                        class="form-input cursor-not-allowed bg-gray-200"
                        v-model="address.state"
                        disabled
                      />
                      <template v-if="isSubmitForm && $v.address.state.$error">
                        <p class="text-danger mt-1 text-sm">
                          Campo obrigatório, verifique o CEP digitado
                        </p>
                      </template>
                    </div>
                  </div>
                  <hr />

                  <!-- FREQUENCIA DE COLETA, DIA DE COLETA E HORA DA COLETA -->
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-5 pt-4">
                    <div
                      class="col-span-1"
                      :class="{
                        'has-error': $v.client.collectionFrequency.$error,
                        'has-success':
                          isSubmitForm && !$v.client.collectionFrequency.$error,
                      }"
                    >
                      <label for="collectionFrequency"
                        >Frequencia de Coleta</label
                      >
                      <select
                        v-model="client.collectionFrequency"
                        class="form-select text-white-dark"
                      >
                        <option value="" disabled>
                          Selecione a frequencia
                        </option>
                        <option value="SEMANAL">SEMANAL</option>
                        <option value="QUINZENAL">QUINZENA</option>
                        <option value="MENSAL">MENSAL</option>
                        <option value="AVULSO">AVULSO</option>
                      </select>
                      <template
                        v-if="
                          isSubmitForm && $v.client.collectionFrequency.$error
                        "
                      >
                        <p class="text-danger mt-1 text-sm">
                          Selecione uma opção
                        </p>
                      </template>
                    </div>

                    <div
                      class="col-span-1"
                      :class="{
                        'has-error': $v.client.collectionDay.$error,
                        'has-success':
                          isSubmitForm && !$v.client.collectionDay.$error,
                      }"
                    >
                      <label for="collectionDay">Dia da Coleta</label>
                      <select
                        v-model="client.collectionDay"
                        class="form-select text-white-dark"
                      >
                        <option value="" disabled>
                          Selecione o dia da coleta
                        </option>
                        <option value="SEGUNDA">SEGUNDA</option>
                        <option value="TERCA">TERÇA</option>
                        <option value="QUARTA">QUARTA</option>
                        <option value="QUINTA">QUINTA</option>
                        <option value="SEXTA">SEXTA</option>
                        <option value="SABADO">SÁBADO</option>
                      </select>
                      <template
                        v-if="isSubmitForm && $v.client.collectionDay.$error"
                      >
                        <p class="text-danger mt-1 text-sm">
                          Selecione uma opção
                        </p>
                      </template>
                    </div>

                    <div
                      :class="{
                        'has-success': isSubmitForm,
                      }"
                    >
                      <label for="collectionTime">Hora da Coleta</label>
                      <input
                        id="collectionTime"
                        type="time"
                        class="form-input"
                        v-model="client.collectionTime"
                      />
                    </div>
                  </div>

                  <!-- DIA DA ENTREGA, HORA DA ENTREGA E INÍCIO DA COLETA -->
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-5 pb-4">
                    <div
                      class="col-span-1"
                      :class="{
                        'has-error': $v.client.deliveryDay.$error,
                        'has-success':
                          isSubmitForm && !$v.client.deliveryDay.$error,
                      }"
                    >
                      <label for="deliveryDay">Dia da Entrega</label>
                      <select
                        v-model="client.deliveryDay"
                        class="form-select text-white-dark"
                      >
                        <option value="" disabled>
                          Selecione o dia da entrega
                        </option>
                        <option value="SEGUNDA">SEGUNDA</option>
                        <option value="TERCA">TERÇA</option>
                        <option value="QUARTA">QUARTA</option>
                        <option value="QUINTA">QUINTA</option>
                        <option value="SEXTA">SEXTA</option>
                        <option value="SABADO">SÁBADO</option>
                      </select>
                      <template
                        v-if="isSubmitForm && $v.client.deliveryDay.$error"
                      >
                        <p class="text-danger mt-1 text-sm">
                          Selecione uma opção
                        </p>
                      </template>
                    </div>

                    <div
                      :class="{
                        'has-success': isSubmitForm,
                      }"
                    >
                      <label for="deliveryTime">Hora da Entrega</label>
                      <input
                        id="deliveryTime"
                        type="time"
                        class="form-input"
                        v-model="client.deliveryTime"
                      />
                    </div>

                    <div
                      :class="{
                        'has-success': isSubmitForm,
                      }"
                    >
                      <label for="collectionStart">Início da Coleta</label>
                      <input
                        id="collectionStart"
                        type="date"
                        class="form-input"
                        v-model="client.collectionStart"
                      />
                    </div>
                  </div>
                  <hr />

                  <!-- OBSERVAÇAO, TAXA ENTREGA, DIA VENCIMENTO -->
                  <div class="grid grid-cols-1 md:grid-cols-4 gap-5 pt-4">
                    <div
                      class="col-span-1"
                      :class="{
                        'has-error': $v.client.description.$error,
                        'has-success':
                          isSubmitForm && !$v.client.description.$error,
                      }"
                    >
                      <label for="description">Observação</label>
                      <textarea
                        id="description"
                        rows="3"
                        class="form-textarea"
                        v-model="client.description"
                      ></textarea>
                      <template
                        v-if="isSubmitForm && $v.client.description.$error"
                      >
                        <p class="text-danger mt-1 text-sm">
                          Por favor, preencha esse campo
                        </p>
                      </template>
                    </div>

                    <div class="col-span-1">
                      <label for="deliveryFee">Taxa de entrega?</label>
                      <label
                        for="deliveryFee"
                        class="flex items-center cursor-pointer"
                      >
                        <input
                          id="deliveryFee"
                          v-model="deliveryFee"
                          type="checkbox"
                          class="form-checkbox"
                        />
                        <span class="text-white-dark">Taxa de Entrega</span>
                      </label>
                    </div>

                    <div
                      :class="{
                        'has-error': $v.client.deliveryAmount.$error,
                        'has-success':
                          isSubmitForm && !$v.client.deliveryAmount.$error,
                      }"
                    >
                      <label for="deliveryAmount"
                        >Valor da taxa de Entrega</label
                      >
                      <moneyInput
                        id="deliveryAmount"
                        type="text"
                        class="form-input disabled:cursor-not-allowed disabled:bg-gray-200"
                        :options="moneyConfig"
                        v-model="client.deliveryAmount"
                        :disabled="!deliveryFee"
                      />
                      <template
                        v-if="isSubmitForm && $v.client.deliveryAmount.$error"
                      >
                        <p class="text-danger mt-1 text-sm">
                          Por favor preencha esse campo
                        </p>
                      </template>
                    </div>

                    <div
                      class="col-span-1"
                      :class="{
                        'has-error': $v.client.dueDate.$error,
                        'has-success':
                          isSubmitForm && !$v.client.dueDate.$error,
                      }"
                    >
                      <label for="dueDate">Dia do Vencimento</label>
                      <input
                        id="dueDate"
                        type="number"
                        class="form-input"
                        v-model="client.dueDate"
                      />
                      <template v-if="isSubmitForm && $v.client.dueDate.$error">
                        <p class="text-danger mt-1 text-sm">
                          Por favor, preencha esse campo
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
                      v-show="!client.id"
                      @click="buttonCreate"
                    >
                      Cadastrar
                    </button>
                    <button
                      type="submit"
                      class="btn btn-primary ml-4 bg-primary/90 hover:bg-primary"
                      v-show="client.id"
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
import { required, email, numeric } from "@vuelidate/validators";
import moneyInput from "../../components/core/input/moneyInput.vue";
import { useClientStore } from "@/stores/useClientStore";
import Spinner from "@/components/core/Spinner.vue";

import {
  showMessage,
  removeSpecialCharacters,
  formatCEP,
  formatPhone,
  formatCellphone,
  formatCPF,
} from "@/utils/utils";

useMeta({ title: "Cadastrar Clientes" });

const clientStore = useClientStore();

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

// Buscando CEP
const searchCep = async () => {
  if (cep.value !== null) {
    try {
      const response = await fetch(
        `https://viacep.com.br/ws/${removeSpecialCharacters(
          address.value.cep
        )}/json/`
      );
      const data = await response.json();
      if (data.erro) {
        return showMessage("Cep incorreto", "#fff", "error", "danger");
      }
      address.value.street = data.logradouro;
      address.value.city = data.localidade;
      address.value.district = data.bairro;
      address.value.state = data.uf;
      return;
    } catch (error) {
      return console.error(error);
    }
  }
  address.value.street = "";
  address.value.city = "";
  address.value.district = "";
  address.value.state = "";
};

const branches = ref([...props.branch]);

const plans = ref([...props.plan]);

const client = ref({
  ...props.client,
});

const address = ref({
  ...props.address,
});

// Mantendo os campos formatados no front
const formattedCEP = computed(() => {
  return formatCEP(address.value.cep) || null;
});

const formattedCPF = computed(() => {
  return formatCPF(client.value.cpf);
});

const formattedPhone = computed(() => {
  return formatPhone(client.value.homePhone);
});

const formattedCellphone = computed(() => {
  return formatCellphone(client.value.cellPhone);
});

const cellPhone = computed({
  get: () => formattedCellphone.value,
  set: (value) => (client.value.cellPhone = removeSpecialCharacters(value)),
});

const homePhone = computed({
  get: () => formattedPhone.value,
  set: (value) => (client.value.homePhone = removeSpecialCharacters(value)),
});

// Limpando os campos
const cep = computed({
  get: () => formattedCEP.value,
  set: (value) => (address.value.cep = removeSpecialCharacters(value)),
});

const cpf = computed({
  get: () => formattedCPF.value,
  set: (value) => (client.value.cpf = removeSpecialCharacters(value)),
});

const loading = ref(false);

const props = defineProps({
  modelValue: Boolean,
  plan: {
    type: Array,
  },
  branch: {
    type: Array,
  },
  client: {
    required: true,
    type: Object,
  },
  address: {
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
  () => props.client,
  (newClient) => {
    Object.assign(client.value, newClient);
  }
);

watch(
  () => props.address,
  (newAddress) => {
    Object.assign(address.value, newAddress);
  }
);

watch(
  () => props.branch,
  (newBranch) => {
    branches.value = [...newBranch];
  }
);

watch(
  () => props.plan,
  (newPlan) => {
    plans.value = [...newPlan];
  }
);

const isSubmitForm = ref(false);

const erroMsgEmail = ref({
  message: null,
});

const erroMsgCPF = ref({
  message: null,
});

const hasErrorEmail = computed(() => {
  return erroMsgEmail.value.message !== null;
});

const hasErrorCPF = computed(() => {
  return erroMsgCPF.value.message !== null;
});

function isValidCollectionFrequency(value) {
  const allowedCollectionFrequency = [
    "SEMANAL",
    "QUINZENAL",
    "MENSAL",
    "AVULSO",
  ];
  return allowedCollectionFrequency.includes(value);
}

function isValidCollectionDay(value) {
  const allowedCollectionDay = [
    "SEGUNDA",
    "TERCA",
    "QUARTA",
    "QUINTA",
    "SEXTA",
    "SABADO",
    "DOMINGO",
  ];
  if (!value) {
    return true;
  } else {
    return allowedCollectionDay.includes(value);
  }
}

function isValidDeliveryDay(value) {
  const allowedDeliveryDay = [
    "SEGUNDA",
    "TERCA",
    "QUARTA",
    "QUINTA",
    "SEXTA",
    "SABADO",
    "DOMINGO",
  ];
  if (!value) {
    return true;
  } else {
    return allowedDeliveryDay.includes(value);
  }
}

const isActive = computed({
  get: () => client.value.isActive === 1,
  set: (value) => (client.value.isActive = value ? 1 : 0),
});

const deliveryFee = computed({
  get: () => client.value.deliveryFee === 1,
  set: (value) => (client.value.deliveryFee = value ? 1 : 0),
});

function isValidCPF(value) {
  const cpfRegex = /^\d{11}$/;
  if (!value) {
    return true;
  } else {
    return cpfRegex.test(value);
  }
}

function isDeliveryFee(value) {
  if (!client.value.deliveryFee) {
    return true;
  } else {
    return value;
  }
}

const rules = {
  client: {
    branchId: { required },
    planId: { required },
    name: { required },
    cpf: { isValidCPF },
    rg: "",
    dateOfBirth: "",
    email: { email },
    homePhone: "",
    cellPhone: "",
    collectionFrequency: { isValidCollectionFrequency },
    collectionDay: { isValidCollectionDay },
    collectionTime: "",
    deliveryDay: { isValidDeliveryDay },
    deliveryTime: "",
    collectionStart: "",
    description: "",
    deliveryFee: "",
    deliveryAmount: { isDeliveryFee },
    dueDate: "",
  },
  address: {
    cep: { required },
    street: { required },
    number: { required },
    complement: "",
    district: { required },
    city: { required },
    state: { required },
  },
};

const $v = useVuelidate(rules, { client, address });

const submitForm = async () => {
  isSubmitForm.value = true;
  $v.value.client.$touch();
  $v.value.address.$touch();
  if ($v.value.client.$invalid || $v.value.address.$invalid) {
    return false;
  }

  // Chamando a action no meu useUserStore
  try {
    loading.value = true;
    if (client.value.id) {
      const clientUpdated = await clientStore.updateClient(
        client.value,
        client.value.id
      );
      if (clientUpdated.erroMsg) {
        showMessage("Email ou CPF já cadastrado", "#FFF", "error", "danger");
        erroMsgEmail.value.message = clientUpdated.erroMsg.messageEmail;
        erroMsgCPF.value.message = clientUpdated.erroMsg.messageCPF;
        loading.value = false;
        return;
      }
      await clientStore.updateAddress(address.value, address.value.id);
      showMessage("Usuário alterado com sucesso!", "#fff");
      loading.value = false;
      clientStore.getClients();
      closeModal();
    } else {
      // Criando o usuario e retornando o ID
      const clientCreated = await clientStore.createClient(client.value);
      if (clientCreated.erroMsg) {
        console.log(clientCreated.erroMsg);
        showMessage("Email ou CPF já cadastrado", "#FFF", "error", "danger");
        erroMsgEmail.value.message = clientCreated.erroMsg.messageEmail;
        erroMsgCPF.value.message = clientCreated.erroMsg.messageCPF;
        loading.value = false;
        return;
      }
      // Criando o endereço e associando ao ID da filial
      address.value.clientId = clientCreated.id;
      await clientStore.createAddress(address.value);
      showMessage("Cliente cadastrado com sucesso!", "#fff");
      loading.value = false;
      clientStore.getClients();
      closeModal();
    }
  } catch (error) {}
};

function closeModal() {
  show.value = false;
  isSubmitForm.value = false;
  $v.value.client.$reset();
  $v.value.address.$reset();
  erroMsgEmail.value.message = null;
  erroMsgCPF.value.message = null;
  emit("close");
}
</script>
