import {ref} from 'vue'
import '../axiosConfig'
import axios from 'axios';
// Global API Handler Function

const apiHandler = {
    install(app, options) {
        app.config.globalProperties.$apiHandler = ref({
            // Handle axios request
            request: async function(method, endpoint, headers, data, alert){
                // Check HTTP method type to make sure correct request is made
                if(!['get', 'post', 'put', 'delete'].includes(method)){
                    return null;
                }
                
                const self = this;
                // Handler API errors
                function handleRes(error) {
                    // If there is response data call error handlers
                    const data = {
                        data: error,
                        alert: alert
                    }
                    
                    self.error(data)
                }

                const req_data = {
                    url: endpoint,
                    baseURL: '/api/',
                    method: method,
                    data: data,
                    headers: headers
                }

                // Make axios call
                try{
                    const res = await axios(req_data);
                    // Show message if return in successful API call
                    if('message' in res.data){
                        app.config.globalProperties.$notification.add({
                            title: alert.title || "Success",
                            text: res.data.message,
                            type: "success"
                        })
                    }

                    return res
                }catch(err){
                    handleRes(err.response);
                    return err.response
                }
            },
            // HTTP methods
            get: async function(endpoint, headers = {}, alert = {}){
                return await this.request('get', endpoint, headers, {}, alert);
            },
            post: async function(endpoint, data = {}, headers = {}, alert = {}){
                return await this.request('post', endpoint, headers, data, alert);
            },
            form: async function(endpoint, data = {}, headers = {}, alert = {}){
                headers = {
                    ...headers,
                    'Content-Type': 'multipart/form-data'
                }

                let form = new FormData();
                // turn the data object into form data
                Object.keys(data).forEach(key => {
                    form.append(key, data[key]);
                })

                return await this.request('post', endpoint, headers, form, alert);
            },
            put: async function(endpoint, data = {}, headers = {}, alert = {}){
                return await this.request('put', endpoint, headers, data, alert);
            },
            delete: async function(endpoint, headers = {}, alert = {}){
                return await this.request('delete', endpoint, headers, {}, alert);
            },
            // Handle response data
            error: async function(error_data = {data: null, alert: null}){
                const error = error_data.data;
                let alert = {
                    title: "Internal Service Error",
                    default_msg: "We were unable to process the request at this time."
                };
                // 
                if('alert' in error_data && error_data.alert){
                    Object.keys(alert).forEach(key => {
                        // Check to see if title and default_msg are passed in the alert object
                        // if they are update the default values
                        if(key in error_data.alert){
                            alert[key] = error_data.alert[key];
                        }
                    })
                }
        
                const status = error.status;
                const data = error.data;
                // Check if there is an errors object - object of arrays
                const errors = data && 'errors' in data;
        
                const message = status == 500 ? alert.default_msg : (data.message ?? null);
                if(message){
                    app.config.globalProperties.$notification.add({
                        title: status == 401 ? 'Authorization Error' : alert.title,
                        text: message,
                        type: status == 400 ? "warn" : "error"
                    })

                    // Check if User needed to be redirected to login page 
                    // Notification if redirected
                }
        
                if(errors){
                    Object.keys(data.errors).forEach(type => {
                        data.errors[type].forEach(err_msg => {
                            app.config.globalProperties.$notification.add({
                                title: status == 400 ? 'Validation Error' : alert.title,
                                text: err_msg,
                                type: status == 400 ? "warn" : "error"
                            })
                        })
                    })
                }
            }
        }).value
    }
}

export default apiHandler;