import {ref} from 'vue'
import sound from '../../assets/audio/mp3/notification.mp3'
// Global Notifications Handler

const notification = {
    install(app, options){
        app.config.globalProperties.$notification = ref({
            notifications: [],
            // value structure {title: string, text: string, type: string[success, warn, error, info], timeout: int(ms)}
            add: async function (value){
                this.notifications.push(value)
                // // Notification Audio
                const audio = new Audio(sound)
                audio.volume = 0.25
                audio.play()
                
                // Remove Object from Notifications
                setTimeout(async () => {
                    const index = this.notifications.indexOf(value)
                    this.notifications.splice(index, 1)
                }, value.timeout ?? 2500)
            }
        }).value
    }
}

export default notification;