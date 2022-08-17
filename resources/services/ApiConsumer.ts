import axios from "axios";

const BASE_URL = "http://0.0.0.0/api/";

class ApiConsumer {
    private headers: { Accept: string; Authorization?: string };
    constructor() {
        this.headers = {
            Accept: "application/json",
        };
    }

    setToken(token: string) {
        console.log(token);
        localStorage.setItem("token", token);
        this.headers = {
            ...this.headers,
            Authorization: "Bearer " + token,
        };
    }

    removeToken() {
        localStorage.removeItem("token");
        delete this.headers.Authorization;
    }

    get(url: string) {
        return new Promise((resolve, reject) => {
            axios
                .get(BASE_URL + url, { headers: this.headers })
                .then((resp) => {
                    resolve(resp.data);
                })
                .catch((e: Error) => {
                    reject(e);
                });
        });
    }

    post(url: string, value: Record<string, unknown>) {
        return new Promise((resolve, reject) => {
            axios
                .post(BASE_URL + url, value, { headers: this.headers })
                .then((resp) => {
                    resolve(resp.data);
                })
                .catch((e: Error) => {
                    reject(e);
                });
        });
    }

    put(url: string, value?: Record<string, unknown>) {
        return new Promise((resolve, reject) => {
            axios
                .put(BASE_URL + url, value, { headers: this.headers })
                .then((resp) => {
                    resolve(resp.data);
                })
                .catch((e: Error) => {
                    reject(e);
                });
        });
    }

    delete(url: string, value?: Record<string, unknown>) {
        return new Promise((resolve, reject) => {
            axios
                .delete(BASE_URL + url, { headers: this.headers, data: value })
                .then((resp) => {
                    resolve(resp.data);
                })
                .catch((e: Error) => {
                    reject(e);
                });
        });
    }
}

const apiConsumer = new ApiConsumer();
export { apiConsumer as ApiConsumer };
