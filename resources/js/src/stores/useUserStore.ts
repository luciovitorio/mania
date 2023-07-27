import { defineStore } from "pinia";
import axiosClient from "../axios";

export const useUserStore = defineStore("user", {
  state: () => ({
    isLoading: false,
    user: {
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
    async getUsers(
      url = null,
      search: string,
      perParge: number,
      sortField: string,
      sortDirection: string
    ) {
      this.isLoading = true;
      url = url || "/user";
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
            this.user = {
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
    async createUser(user: {}) {
      try {
        const response = await axiosClient.post("/user", user);
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
    async updateBranch(user: {}, userId: number) {
      try {
        const response = await axiosClient.put(`/branch/${branchId}`, branch);
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
    // async updateAddress(address: {}, addressId: number) {
    //   try {
    //     await axiosClient.put(`/address/${addressId}`, address);
    //   } catch (error) {
    //     console.log(error);
    //   }
    // },
    // async deleteBranch(id: number) {
    //   await axiosClient.delete(`/branch/${id}`);
    // },
    // async deleteAddress(id: number) {
    //   await axiosClient.delete(`/address/${id}`);
    // },
    async getUser(id: number) {
      const response = await axiosClient.get(`/user/${id}`);
      console.log(response.data);

      return response.data;
    },
  },
  getters: {
    // showData: (state) => state.branch.data,
  },
});
