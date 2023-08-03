import { defineStore } from "pinia";
import axiosClient from "../axios";

export const useClothStore = defineStore("cloth", {
  state: () => ({
    isLoading: false,
    cloth: {
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
    async getClothes(
      url = null,
      search: string,
      perParge: number,
      sortField: string,
      sortDirection: string
    ) {
      this.isLoading = true;
      url = url || "/cloth";
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
            this.cloth = {
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
    async createCloth(cloth: {}) {
      try {
        const response = await axiosClient.post("/cloth", cloth);
        return response.data;
      } catch (error) {
        if (
          error.response &&
          error.response.data &&
          error.response.data.message
        ) {
          const errorMessage = error.response.data.message;
          return { erroMsg: errorMessage };
        } else {
          console.log("Erro desconhecido");
        }
        throw error;
      }
    },
    async updateCloth(cloth: {}, clothId: number) {
      try {
        const response = await axiosClient.put(`/cloth/${clothId}`, cloth);
        return response.data;
      } catch (error) {
        if (
          error.response &&
          error.response.data &&
          error.response.data.message
        ) {
          const errorMessage = error.response.data.message;
          return { erroMsg: errorMessage };
        } else {
          console.log("Erro desconhecido");
        }
        throw error;
      }
    },
    async deleteCloth(id: number) {
      await axiosClient.delete(`/cloth/${id}`);
    },
    async getCloth(id: number) {
      const response = await axiosClient.get(`/cloth/${id}`);

      return response.data;
    },
  },
  getters: {
    showData: (state) => state.branch.data,
  },
});
