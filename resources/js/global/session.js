import {ref} from 'vue'
// Global Loading Function

const session = {
    install(app, options) {
        app.config.globalProperties.$session = ref({
            set_token: function (token){
                return localStorage.setItem('gpt4u_token', token);
            },
            get_token: function (){
                return localStorage.getItem('gpt4u_token');
            },
            clear_token: function (){
                localStorage.removeItem('gpt4u_token')
                return;
            },
            get: function(){
                return JSON.parse(localStorage.getItem('gpt4u_session'));
            },
            set: function(data){
                if('profile_picture' in data){
                    data.profile_picture = data.profile_picture ? window.location.protocol+'//'+window.location.host+'/storage/'+data.profile_picture : null
                }

                return localStorage.setItem('gpt4u_session', JSON.stringify(data));
            },
            clear: function(data){
                localStorage.removeItem('gpt4u_session');
                localStorage.removeItem('gpt4u_token');
                return;
            },
            valid: async function(){
                const res = await app.config.globalProperties.$apiHandler.get('auth/validate')
                if(res.status == 200){
                    this.set(res.data);
                    return true;
                }

                return false;
            }
        }).value
    }
}

export default session