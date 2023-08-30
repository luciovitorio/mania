<template>
  <ClientFormLayout>
    <div>
      <div class="">
        <div class="bg-gray-50 p-3 rounded-md mb-5">
          <h2 class="text-center text-2xl md:text-5xl font-bold">
            Formulário e-ROL
          </h2>
          <h5 class="text-center mb-5 mt-3 text-l md:text-xl font-bold">
            Coloque abaixo a quantidade de peças por tipo que serão enviadas
          </h5>
        </div>

        <ul
          class="md:space-x-5grid grid-cols-1 md:grid-cols-0 md:flex justify-center mb-5 gap-5 md:gap-0"
        >
          <li
            class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-4 py-10 bg-green-50 dark:bg-[#0e1726] text-center md:w-[480px] group hover:shadow-[rgba(145,158,171,0.20)_0px_0px_2px_0px,_rgba(145,158,171,0.12)_0px_12px_24px_-4px]"
          >
            <div>
              <p class="font-bold group-hover:text-primary text-lg">
                Dados do cliente:
              </p>
            </div>
            <div
              class="flex flex-col mt-3 text-base text-left"
              v-if="client.plan"
            >
              <span>Nome: {{ client.name }}</span>
              <span>Plano: {{ client.plan.name }}</span>
              <span
                >Endereço Coleta: {{ client.address.street }},
                {{ client.address.number }} {{ client.address.complement }} -
                {{ client.address.district }}</span
              >
            </div>
            <div v-else>loading</div>
          </li>
        </ul>
        <form @submit.prevent="submitForm()" class="mb-5">
          <div
            class="relative max-w-[580px] bg-[#D6E3FF]/10 mx-auto border-[#ebedf2] p-5 border rounded-md"
          >
            <h3 class="font-bold text-xl">Peças Fáceis</h3>
            <h4 class="mt-3 italic text-sm">
              Informe a quantidade de peças e a quantidade de cabides enviados
              para facilitar a devolução das peças passadas.
            </h4>
            <div
              v-for="(cloth, index) in easyClothins"
              :key="index"
              class="my-5 flex flex-col items-center"
            >
              <div class="font-bold text-base pr-2 pt-3 pb-3">
                <span>{{ cloth.name }}</span>
              </div>
              <div class="flex justify-center gap-4">
                <div class="text-center">
                  <label :for="`cabides-${cloth.id}`">Qtd Cabides</label>
                  <input
                    type="text"
                    class="form-input w-14"
                    v-model="cloth.hangQuantity"
                  />
                </div>
                <div class="text-center">
                  <label :for="`quantidade-${cloth.id}`">Qtd de Peças</label>
                  <input
                    type="text"
                    class="form-input w-14"
                    v-model="cloth.clothQuantity"
                    @input="calculateQtd()"
                  />
                </div>
              </div>
            </div>

            <hr />

            <h3 class="font-bold text-xl mt-3">Peças Difíceis</h3>
            <div
              v-for="(cloth, index) in hardClothins"
              :key="index"
              class="my-5 flex flex-col items-center"
            >
              <div class="font-bold text-base pr-2 pt-3 pb-3">
                <span>{{ cloth.name }}</span>
              </div>
              <div class="flex justify-center gap-4">
                <div class="text-center">
                  <label :for="`cabides-${cloth.id}`">Qtd Cabides</label>
                  <input
                    type="text"
                    class="form-input w-14"
                    v-model="cloth.hangQuantity"
                  />
                </div>
                <div class="text-center">
                  <label :for="`quantidade-${cloth.id}`">Qtd de Peças</label>
                  <input
                    type="text"
                    class="form-input w-14"
                    v-model="cloth.clothQuantity"
                    @input="calculateQtd()"
                  />
                </div>
              </div>
            </div>
            <hr />
            <div class="my-5 flex items-center justify-between">
              <div class="font-semibold pt-3">
                <input
                  type="checkbox"
                  class="form-checkbox"
                  v-model="isHanger"
                />
                <span class="">Envio de cabides Mania de Passar?</span>
              </div>
              <div class="flex gap-2">
                <div class="text-center">
                  <label for="quantidade">Quantidade</label>
                  <input
                    id="quantidade"
                    type="text"
                    class="form-input w-14 disabled:cursor-not-allowed disabled:bg-gray-300"
                    :disabled="!isHanger"
                    v-model="hangerQuantity"
                  />
                </div>
              </div>
            </div>
            <hr />
            <div class="mt-2 mb-2 text-base text-center">
              <p class="mb-2">
                Quantidade total de peças fáceis:
                <b>{{ calculateQtdTotal("EASY") }}</b>
              </p>
              <p>
                Quantidade total de peças difíceis:
                <b>{{ calculateQtdTotal("HARD") }}</b>
              </p>
            </div>
            <hr />
            <div>
              <button
                type="submit"
                class="btn btn-primary w-full mt-4"
                :disabled="loading"
                :class="{
                  'cursor-not-allowed': loading,
                  'hover:bg-indigo-500': loading,
                }"
              >
                <span
                  class="absolute left-0 inset-y-0 flex items-center pl-3"
                  v-if="!loading"
                >
                </span>
                ENVIAR
              </button>
            </div>

            <div class="border mt-4 p-2 text-center">
              <h2 class="font-bold text-base">Observações Gerais</h2>
              <p class="italic">
                1- Após a lavagem e secagem das roupas, dobre e separe por tipos
                de peças.<br />
                2- Preencha o eROL de arrecadação anotando a quantidade e tipo
                de roupas para passar. <br />
                3- Coloque na BAG MANIA DE PASSAR. <br />
                4- Leve ou peça para retirar em uma unidade da MANIA. <br />
                5- Pronto! Agora você receberá no dia e horário marcado suas
                roupas carinhosamente passadas prontas para usar.
              </p>
            </div>
          </div>
        </form>

        <h3 class="font-bold text-xl md:text-3xl mb-5 text-center">
          Perguntas e respostas
        </h3>
        <div class="space-y-5 mb-10">
          <div
            class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md bg-white dark:bg-[#0e1726]"
          >
            <div
              class="flex font-semibold p-5 cursor-pointer"
              :class="{ 'text-primary': active === 1 }"
              @click="active === 1 ? (active = null) : (active = 1)"
            >
              <svg
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 ltr:mr-4 rtl:ml-4"
              >
                <path
                  d="M17 7.82959L18.6965 9.35641C20.239 10.7447 21.0103 11.4389 21.0103 12.3296C21.0103 13.2203 20.239 13.9145 18.6965 15.3028L17 16.8296"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                />
                <path
                  opacity="0.5"
                  d="M13.9868 5L10.0132 19.8297"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                />
                <path
                  d="M7.00005 7.82959L5.30358 9.35641C3.76102 10.7447 2.98975 11.4389 2.98975 12.3296C2.98975 13.2203 3.76102 13.9145 5.30358 15.3028L7.00005 16.8296"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                />
              </svg>
              <span class="text-primary"
                >Como posso entrar em contato com a Mania de Passar</span
              >
            </div>
            <vue-collapsible :isOpen="active === 1">
              <div class="p-5 text-white-dark font-semibold">
                <p>
                  Para entrar em contato conosco basta enviar um email para
                  maniadepassar@email.com.br ou através de nossas redes sociais.
                </p>
              </div>
            </vue-collapsible>
          </div>

          <div
            class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md bg-white dark:bg-[#0e1726]"
          >
            <div
              class="flex font-semibold cursor-pointer p-5"
              :class="{ 'text-primary': active === 2 }"
              @click="active === 2 ? (active = null) : (active = 2)"
            >
              <svg
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 ltr:mr-4 rtl:ml-4"
              >
                <path
                  d="M17 7.82959L18.6965 9.35641C20.239 10.7447 21.0103 11.4389 21.0103 12.3296C21.0103 13.2203 20.239 13.9145 18.6965 15.3028L17 16.8296"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                />
                <path
                  opacity="0.5"
                  d="M13.9868 5L10.0132 19.8297"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                />
                <path
                  d="M7.00005 7.82959L5.30358 9.35641C3.76102 10.7447 2.98975 11.4389 2.98975 12.3296C2.98975 13.2203 3.76102 13.9145 5.30358 15.3028L7.00005 16.8296"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                />
              </svg>
              <span class="text-primary"
                >Posso solicitar o adiantamento da retirada das roupas?</span
              >
            </div>
            <vue-collapsible :isOpen="active === 2">
              <div class="p-5 text-white-dark font-semibold">
                <p>
                  Pode sim. Basta entrar em contato conosco através das nossas
                  redes e solicite o adiantamento, fique atento sobre a taxa que
                  deverá ser paga para realizar esse procedimento.
                </p>
              </div>
            </vue-collapsible>
          </div>
        </div>
      </div>
    </div>
  </ClientFormLayout>
</template>
<script setup>
import { computed, onMounted, ref, watch } from "vue";
import VueCollapsible from "vue-height-collapsible/vue3";
import ClientFormLayout from "../../components/ClientFormLayout.vue";
import { useClientStore } from "@/stores/useClientStore";
import { useMeta } from "@/composables/use-meta";

import { showMessage } from "@/utils/utils";

useMeta({ title: "Formulário eROL" });

const clientStore = useClientStore();

const loading = ref(false);

const clothesAsString = sessionStorage.getItem("CLOTHES_DATA");
const clothes = JSON.parse(clothesAsString);

const link = sessionStorage.getItem("CLIENT_LINK");

const props = defineProps({
  client: {
    type: Object,
  },
});

const client = ref(clientStore.client.data);

const erol = ref([]);
const erolFinal = ref([]);
const isHanger = ref(false);
const hangerQuantity = ref("");

onMounted(async () => {
  try {
    const client = await clientStore.client.data;
    if (isEmptyObject(clientStore.client.data)) {
      sessionStorage.removeItem("CLIENT_LINK");
      sessionStorage.removeItem("CLIENT_TOKEN");
      sessionStorage.removeItem("CLOTHES_DATA");
      window.location.href = `${link}`;
    } else {
      client.value = client;
      erol.value = clothes.map((cloth) => ({
        rolId: client.value.rol.id,
        clothinId: cloth.id,
        name: cloth.name,
        type: cloth.type,
        hangQuantity: "",
        clothQuantity: "",
      }));
    }
  } catch (error) {
    console.error("Erro durante a montagem:", error);
  }
});

watch(
  () => props.client,
  (newClient) => {
    Object.assign(client.value, newClient);
  }
);

const easyClothins = computed(() =>
  erol.value.filter((clothin) => clothin.type === "EASY")
);
const hardClothins = computed(() =>
  erol.value.filter((clothin) => clothin.type === "HARD")
);

function isEmptyObject(obj) {
  return Object.keys(obj).length === 0;
}

const submitForm = async () => {
  erolFinal.value = erol.value.filter((item) => item.clothQuantity > 0);

  erolFinal.value.forEach((item) => {
    delete item.name;
    delete item.type;
    item.clothQuantity = parseInt(item.clothQuantity);
    item.hangQuantity = parseInt(item.hangQuantity);
  });

  console.log(erolFinal.value);
  console.log(isHanger.value);
  console.log(hangerQuantity.value);

  try {
    if (erolFinal.value.id) {
      // const erolUpdated = await clientStore.updateErol(
      //   erolFinal.value,
      //   erolFinal.value.id
      // );
      // if (erolUpdated.erroMsg) {
      //   showMessage("Email ou CPF já cadastrado", "#FFF", "error", "danger");
      //   erroMsgEmail.value.message = clientUpdated.erroMsg.messageEmail;
      //   erroMsgCPF.value.message = clientUpdated.erroMsg.messageCPF;
      //   loading.value = false;
      //   return;
      // }
      // await clientStore.updateAddress(address.value, address.value.id);
      // showMessage("Usuário alterado com sucesso!", "#fff");
      // loading.value = false;
      // clientStore.getClients();
      // closeModal();
    } else {
      console.log("aqui");
      // Criando o eROL
      await clientStore.fillErol(erolFinal.value);

      showMessage("eROL cadastrado com sucesso!", "#fff");
      sessionStorage.removeItem("CLIENT_LINK");
      sessionStorage.removeItem("CLIENT_TOKEN");
      sessionStorage.removeItem("CLOTHES_DATA");
      window.location.href = "/clientes/login";
    }
  } catch (error) {}
};

const calculateQtd = () => {
  erolFinal.value = erol.value.filter((item) => item.clothQuantity > 0);
  calculateQtdTotal("EASY");
  calculateQtdTotal("HARD");
};

const calculateQtdTotal = (type) => {
  return erolFinal.value.reduce((total, item) => {
    if (item.type === type) {
      return total + parseInt(item.clothQuantity);
    }
    return total;
  }, 0);
};

const active = ref(null);
</script>
