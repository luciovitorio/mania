import { defineStore } from "pinia";
import axiosClient from "../axios";

export const useClientStore = defineStore("client", {
  state: () => ({
    isEdit: false,
    isLoading: false,
    client: {
      token: sessionStorage.getItem("CLIENT_TOKEN"),
      link: sessionStorage.getItem("CLIENT_LINK"),
      clothes: [],
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
    async login({ cpf, link }) {
      this.isLoading = true;
      return await axiosClient
        .post("/client/login", { cpf, link })
        .then(({ data }) => {
          this.client.clothes = data.clothes;
          this.client.data = data.client;
          this.client.token = data.token;

          if (data.token) {
            const clothes = JSON.stringify(data.clothes);
            sessionStorage.setItem("CLIENT_TOKEN", data.token);
            sessionStorage.setItem("CLOTHES_DATA", clothes);
          } else {
            sessionStorage.removeItem("CLIENT_TOKEN");
          }
          this.data = this.isLoading = false;
          return data;
        });
    },
    async getClients(
      url = null,
      search: string,
      perParge: number,
      sortField: string,
      sortDirection: string
    ) {
      this.isLoading = true;
      url = url || "/client";
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
            this.client = {
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
    async createClient(client: {}) {
      try {
        const response = await axiosClient.post("/client", client);
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
    async createAddress(address: {}) {
      await axiosClient.post("/address", address);
    },
    async updateClient(client: {}, clientId: number) {
      try {
        const response = await axiosClient.put(`/client/${clientId}`, client);
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
    async updateAddress(address: {}, addressId: number) {
      try {
        await axiosClient.put(`/address/${addressId}`, address);
      } catch (error) {
        console.log(error);
      }
    },
    async deleteClient(id: number) {
      await axiosClient.delete(`/client/${id}`);
    },
    async deleteAddress(id: number) {
      await axiosClient.delete(`/address/${id}`);
    },
    async getClient(id: number) {
      const response = await axiosClient.get(`/client/${id}`);
      console.log(response.data);

      return response.data;
    },
    async fillErol(erol: []) {
      const response = await axiosClient.post("/client/erol", erol);
      return response.data;
    },
    // async getClientWithLink(link: string) {
    //   const response = await axiosClient.get(`/erol/getClient/${link}`);
    //   this.client.data = response.data;
    //   console.log(response.data);

    //   return response.data;
    // },
  },
  getters: {
    // showIsEdit: (state) => state.isEdit,
  },
});
