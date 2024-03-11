import {ref} from 'vue'
// Global Sleep Function

const sleep = {
    install(app, options) {
        app.config.globalProperties.$sleep = ref({
            delay: async function(delay){
                new Promise((resolve) => setTimeout(resolve, delay))
            }
        }).value
    }
}

export default sleep