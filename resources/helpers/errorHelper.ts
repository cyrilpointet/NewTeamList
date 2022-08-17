import axios from "axios";

export const errorHelper = {
    formatMessage(e: any): string {
        return axios.isAxiosError(e) && typeof e.response?.data === "string"
            ? e.response.data
            : "Erreur";
    },
};
