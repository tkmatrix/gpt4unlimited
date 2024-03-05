import './bootstrap'
import './axiosConfig'
import {createApp, ref} from 'vue'
import App from '../App.vue'
// CSS
import '../css/tailwind.css'
import '../css/app.css'
import 'animate.css';
// Router
import router from './router.js'
// Notification
import noti_sound from '../assets/audio/mp3/notification.mp3'


let app = createApp(App)
.use(router)

// Notification Alerts
// add function properties - title, text, timeout, type
app.config.globalProperties.$notification = ref({
    notifications: [],
    add: async function (value){
        this.notifications.push(value)
        // // Notification Audio
        const audio = new Audio(noti_sound)
        audio.volume = 0.25
        audio.play()
        
        // Remove Object from Notifications
        setTimeout(async () => {
            const index = this.notifications.indexOf(value)
            this.notifications.splice(index, 1)
        }, value.timeout ?? 2500)
    }
}).value

// Global Loading Variable
app.config.globalProperties.$gloading = ref({
    loading: false,
    start: async function(){
        if(!this.loading){
            this.loading = true
        }
    },
    stop: async function(){
        if(this.loading){
            this.loading = false
        }
    }
}).value

// Global Sleep Function
app.config.globalProperties.$gsleep = ref({
    delay: async function(delay){
        new Promise((resolve) => setTimeout(resolve, delay))
    }
}).value


app.mount("#app")