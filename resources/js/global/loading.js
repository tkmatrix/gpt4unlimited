import {ref} from 'vue'
// Global Loading Function

const gloading = {
    install(app, options) {
        app.config.globalProperties.$gloading = ref({
            loading: false,
            start: function (){
                if(!this.loading){
                    this.loading = true;
                }
            },
            stop: function (){
                if(this.loading){
                    this.loading = false;
                }
            }
        }).value
    }
}

export default gloading