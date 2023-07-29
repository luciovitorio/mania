import { defineStore } from "pinia";
import axiosClient from "../axios";

export const usePlanStore = defineStore("plan", {
  state: () => ({
    isLoading: false,
    plan: {
      data: [],
      links: [],
      from: null,
      to: null,
      page: 1,
      limit: null,
      total: null,
    },
  }),
  actions: {
    async getPlans(
      url = null,
      search: string,
      perParge: number,
      sortField: string,
      sortDirection: string
    ) {
      this.isLoading = true;
      url = url || "/plan";
      return await axiosClient
        .get(url, {
          params: {
            search,
            per_page: perParge,
            sort_field: sortField,
            sort_direction: sortDirection,
          },
        })
        .then((response) => {
          if (response) {
            this.plan = {
              data: response.data.data,
              links: response.data.meta.links,
              total: response.data.meta.total,
              limit: response.data.meta.per_page,
              from: response.data.meta.from,
              to: response.data.meta.to,
              page: response.data.meta.current_page,
            };
          }

          this.data = this.isLoading = false;
        });
    },
    async createPlan(plan: {}) {
      console.log(plan);
      try {
        const response = await axiosClient.post("/plan", plan);
        return response.data;
      } catch (error) {
        if (error.response.data) {
          return { erroMsg: error.response.data };
        } else {
          console.log("Erro Desconhecido");
        }
        throw error;
      }
    },
    async updatePlan(plan: {}, planId: number) {
      try {
        const response = await axiosClient.put(`/plan/${planId}`, plan);
        return response.data;
      } catch (error) {
        if (error.response.data) {
          return { erroMsg: error.response.data };
        } else {
          console.log("Erro desconhecido");
        }
        throw error;
      }
    },
    async deletePlan(id: number) {
      await axiosClient.delete(`/plan/${id}`);
    },
    async getPlan(id: number) {
      const response = await axiosClient.get(`/plan/${id}`);

      return response.data;
    },
  },
  getters: {
    // showIsEdit: (state) => state.isEdit,
  },
});

function converterCamposNumericos(obj) {
  const keys = Object.keys(obj);

  for (const key of keys) {
    // Verificar se o valor é uma string e contém "R$" ou vírgula
    if (
      typeof obj[key] === "string" &&
      (obj[key].includes("R$") || obj[key].includes(","))
    ) {
      // Remover o símbolo "R$" e quaisquer outros caracteres não numéricos (como pontos e vírgulas)
      const valorLimpo = obj[key].replace(/[^\d,]/g, "");

      // Substituir a vírgula por ponto para garantir o formato correto de número decimal
      const valorDecimalFormatado = valorLimpo.replace(",", ".");

      // Converter o valor para decimal
      const valorDecimal = parseFloat(valorDecimalFormatado);

      // Atualizar o campo do objeto com o valor numérico convertido
      obj[key] = valorDecimal;
    }
  }

  return obj;
}
