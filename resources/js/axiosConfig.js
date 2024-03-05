import axios from 'axios';

axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]'),
    'Accept': 'application/json'
    // 'Authorization': 'Bearer '+localStorage.getItem("rpm_token")
    // 'Access-Control-Allow-Origin': '*',
    // 'Access-Control-Allow-Methods': 'GET,POST,OPTIONS,DELETE,PUT'
};

axios.interceptors.request.use(
    config => {
      config.headers['Authorization'] = `Bearer ${localStorage.getItem('ai_unlocked_token')}`;
        return config;
    }
);

axios.interceptors.response.use(
    response => response,
    async error => {
        const status = error.response ? error.response.status : null;

        // Ignore auth endpoints
        if(status == 401 && !error.response.config.url.includes('/api/auth/')){
            const token = localStorage.getItem("ai_unlocked_token");

            await axios.get('/api/auth/validate-token', {
                "headers": {
                    "Authorization": "Bearer "+token
                }
            })
            .then(response => {
                
            })
            .catch(error => {
                // Clear token from local storage and redirect to login page
                if(token){
                    localStorage.removeItem("ai_unlocked_token")
                }

                // Return to Login / Signup Page
                return window.location.replace(window.location.protocol+'//'+window.location.host+'/')
            })
        }

        return Promise.reject(error);
    }
);