import { defineStore } from "pinia";
import axiosClient from "../axios";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    user: {
      token: sessionStorage.getItem("TOKEN"),
      data: {},
    },
  }),
  actions: {
    async login({ cpf, password }) {
      return await axiosClient
        .post("/login", { cpf, password })
        .then(({ data }) => {
          this.user.data = data.user;
          this.user.token = data.token;
          if (data.token) {
            sessionStorage.setItem("TOKEN", data.token);
          } else {
            sessionStorage.removeItem("TOKEN");
          }
          return data;
        });
    },
    async logout() {
      return await axiosClient.post("/logout").then((response) => {
        this.user.data = null;
        this.user.token = null;
        sessionStorage.removeItem("TOKEN");
        return response;
      });
    },
  },
  getters: {
    async getUser() {
      await axiosClient.get("/auth").then((response) => {
        this.user.data = response.data;
      });
    },
    isLogged: (state) => state.user.token,
  },
});
