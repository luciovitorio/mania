import { defineStore } from "pinia";
import axiosClient from "../axios";

export const useBranchStore = defineStore("branch", {
  state: () => ({
    isLoading: false,
    branch: {
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
    async getBranches(url = null, search, perParge, sortField, sortDirection) {
      this.isLoading = true;
      url = url || "/branch";
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
            this.branch = {
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
    async createBranch(branch: {}) {
      try {
        const response = await axiosClient.post("/branch", branch);
        return response.data;
      } catch (error) {
        if (
          error.response &&
          error.response.data &&
          error.response.data.message
        ) {
          const errorMessage = error.response.data.message;
          console.log(errorMessage);
          return { erroMsg: errorMessage };
        } else {
          console.log("Erro desconhecido");
        }
        throw error;
      }
    },
    async createAddress(address: {}) {
      await axiosClient.post("/address", address);
    },
    async updateBranch(branch: {}, branchId: number) {
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
    async updateAddress(address: {}, addressId: number) {
      try {
        await axiosClient.put(`/address/${addressId}`, address);
      } catch (error) {
        console.log(error);
      }
    },
    async deleteBranch(id: number) {
      await axiosClient.delete(`/branch/${id}`);
    },
    async deleteAddress(id: number) {
      await axiosClient.delete(`/address/${id}`);
    },
    async getBranche(id: number) {
      const response = await axiosClient.get(`/branch/${id}`);
      return response.data;
    },
  },
  getters: {
    showData: (state) => state.branch.data,
  },
});
