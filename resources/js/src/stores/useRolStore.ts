import { defineStore } from "pinia";
import axiosClient from "../axios";
import axios from "axios";

export const useRolStore = defineStore("rol", {
  state: () => ({
    isEdit: false,
    isLoading: false,
    rol: {
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
    async getRols(
      url = null,
      search: string,
      perParge: number,
      sortField: string,
      sortDirection: string
    ) {
      this.isLoading = true;
      url = url || "/rol";
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
            this.rol = {
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
    async sendRol(rol: {}) {
      try {
        const response = await axiosClient.post(
          "http://localhost:8000/api/send",
          rol
        );
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
    async createRol(rol: {}) {
      try {
        const response = await axiosClient.post("/rol", rol);
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
    // async createAddress(address: {}) {
    //   await axiosClient.post("/address", address);
    // },
    // async updateUser(user: {}, userId: number) {
    //   try {
    //     const response = await axiosClient.put(`/user/${userId}`, user);
    //     return response.data;
    //   } catch (error) {
    //     if (error.response.data) {
    //       return { erroMsg: error.response.data };
    //     } else {
    //       console.log("Erro desconhecido");
    //     }
    //     throw error;
    //   }
    // },
    // async updateAddress(address: {}, addressId: number) {
    //   try {
    //     await axiosClient.put(`/address/${addressId}`, address);
    //   } catch (error) {
    //     console.log(error);
    //   }
    // },
    // async deleteUser(id: number) {
    //   await axiosClient.delete(`/user/${id}`);
    // },
    // async deleteAddress(id: number) {
    //   await axiosClient.delete(`/address/${id}`);
    // },
    // async getUser(id: number) {
    //   const response = await axiosClient.get(`/user/${id}`);
    //   console.log(response.data);
    //   return response.data;
    // },
  },
  getters: {
    // showIsEdit: (state) => state.isEdit,
  },
});
