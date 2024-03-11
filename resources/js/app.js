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

// Custom Global Functions
import notification from './global/notifications.js'
import gloading from './global/loading.js'
import apiHandler from './global/apiHandler.js'
import session from './global/session.js'


let app = createApp(App)
.use(router)

// Custom Global Functions
.use(notification)
.use(gloading)
.use(apiHandler)
.use(session)

// Set app in window so that router can access it
window.gpt4u = app;

app.mount("#app")