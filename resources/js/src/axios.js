import axios from "axios";
import { useAuthStore } from "./stores/useAuthStore";
import router from "./router";

const axiosClient = axios.create({
  baseURL: `${import.meta.env.VITE_API_BASE_URL}/api`,
});

const setAuthorizationHeader = (config) => {
  const authStore = useAuthStore();
  config.headers.Authorization = `Bearer ${authStore.user.token}`;
  return config;
};

axiosClient.interceptors.request.use(setAuthorizationHeader);

axiosClient.interceptors.response.use(
  (response) => {
    return response;
  },
  (error) => {
    if (error.response.status === 401) {
      sessionStorage.removeItem("TOKEN");
      router.push({ name: "login" });
    }

    throw error;
  }
);

export default axiosClient;
