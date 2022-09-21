import axios from "axios";

export const errorHelper = {
    formatMessage(e: unknown): string {
        return axios.isAxiosError(e) && typeof e.response?.data === "string"
            ? e.response.data
            : "Erreur";
    },
};
