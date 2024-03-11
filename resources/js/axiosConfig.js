import axios from "axios";

axios.defaults.headers.common = {
    "X-Requested-With": "XMLHttpRequest",
    "X-CSRF-TOKEN": document.head.querySelector('meta[name="csrf-token"]'),
    Accept: "application/json",
    // 'Access-Control-Allow-Origin': '*',
    // 'Access-Control-Allow-Methods': 'GET,POST,OPTIONS,DELETE,PUT'
};

axios.interceptors.request.use((config) => {
    config.headers["Authorization"] = `Bearer ${localStorage.getItem( "gpt4u_token" )}`;
    return config;
});

axios.interceptors.response.use(
    (response) => response,
    async (error) => {
        const app = window.gpt4u.config.globalProperties;
        const status = error.response ? error.response.status : null;

        // Ignore auth endpoints
        const fullURL =
            (error.response.config.baseURL ?? "") + error.response.config.url;

        if (status == 401 && !fullURL.includes("/api/auth/")) {
            const alert = {
                deafult_msg: "invalid session, please login again to continue.",
            };
            const res = await app.$apiHandler.get("auth/validate", {}, alert);

            if (res.status >= 400) {
                app.$session.clear_token();
                return app.$router.push({
                    name: "Home",
                    query: { then: app.$route.path },
                });
            }
        }

        return Promise.reject(error);
    }
);
