<template>
    <!-- Save Chat Popup -->
    <saveChat v-if="save_chat" @close="save_chat = false" />
    <settings v-if="view_settings" @close="view_settings = false" />

    <div class="w-full h-screen md:flex absolute top-0 left-0 text-custom-black">
        <!-- Mobile Header -->
        <div class="grid items-center md:hidden w-full h-[55px] border-b-[1px] border-custom-black/10 fixed z-[35] overflow-hidden bg-white ">
            <!-- Open Side Bar Button -->
            <Icon @click="show_sidebar = true;" icon="jam:menu" height="36px" class="left-2 absolute text-custom-gray" />

            <!-- Header -->
            <div class="w-fit max-w-[65%] h-fit m-auto flex items-center gap-2 truncate">
                <!-- AI Logo -->
                <img src="../../../assets/images/aiLogo.png" alt="AI Logo" class="h-[26px]">
                <p class="truncate font-semibold">GPT4 Unlimited</p>
            </div>

            <!-- Save Chat Icon -->
            <Icon @click="save_chat = true" icon="circum:save-down-2" height="26px" class="right-2 absolute text-custom-gray cursor-pointer" />
        </div>

        <!-- Chat Side Bar -->
        <div class="w-fit h-screen fixed md:relative flex flex-row-reverse items-center z-[75] left-0 top-0">
            <!-- Open Button -->
            <!-- Only visible when the sidebar is open on mobile, always visible on desktop -->
            <button @click="show_sidebar = !show_sidebar" :class="show_sidebar ? '' : 'hidden md:block'" class="md:w-[24px] md:h-[24px] p-2 md:p-0 ml-6 top-6 -right-[55px] md:-right-0 md:relative absolute bg-custom-dark-blue1 md:bg-transparent border-[1px] md:border-none rounded-[4px] shadow-newdrop md:shadow-none">
                <Icon :icon="show_sidebar ? 'line-md:arrow-open-left' : 'line-md:arrow-open-right' " height="24px" class="m-auto text-white md:text-custom-gray" />
            </button>
            
            <!-- Sidebar -->
            <div :class="show_sidebar ? 'w-[300px] p-4 md:p-6' : 'w-[0px] p-0'" class=" h-full bg-custom-light-gray relative overflow-hidden transition-all duration-1000 shadow-2xl">
                <div class="w-full h-[80%] grid pt-[48px] sidebar-elm visible overflow-x-clip relative">
                    <!-- New Chat Button -->
                    <button class="w-full h-fit grid items-center px-2 py-[4px] hover:bg-custom-hover-gray rounded-[8px] absolute top-0">
                        <div class="w-fit h-fit flex items-center gap-2 truncate">
                            <div class="w-[28px] h-[28px] grid bg-white rounded-full border-[1px] border-custom-black/10">
                                <Icon icon="simple-icons:openai" height="18px" class="m-auto text-custom-black" />
                            </div>
                            <p class="text-[14px] font-semibold truncate select-none">New Chat</p>
                        </div>
                        
                        <!-- Write Icon -->
                        <Icon icon="jam:write" height="18px" class="absolute right-2" />
                    </button>

                    <!-- Chats -->
                    <div class="w-full min-h-fit h-fit max-h-full mt-8 grid pr-2 relative overflow-y-auto">
                        <!-- Timeframes -->
                        <div v-for="(e, i) in ['Today', 'January', '2023']" :class="i == 0 ? '' : 'mt-6'" class="w-full h-fit grid gap-[2px]">
                            <!-- Header -->
                            <p class="px-2 text-[14px] text-custom-gray opacity-70 pb-2">{{ e }}</p>

                            <!-- Chats -->
                            <button v-for="(w, ii) in 2" class="w-full h-[36px] grid items-center px-2 py-[4px] hover:bg-custom-hover-gray rounded-[8px] relative pr-[36px] group">
                                <p class="text-[14px] text-left h-fit font-semibold truncate select-none">This is the Chats</p>

                                <!-- Menu Icon -->
                                <!-- <Icon icon="fe:elipsis-h" height="18px" class="absolute right-2" /> -->
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Bottom Menu -->
                <div class="w-full h-fit grid gap-2 p-4 md:p-6 left-0 bottom-0 absolute sidebar-elm visible">
                    <!-- Upgrate account -->
                    <button class="w-full h-fit grid items-center px-2 py-[4px] hover:bg-custom-hover-gray rounded-[8px] relative">
                        <!-- Account -->
                        <div class="w-fit flex gap-2 items-center truncate">
                            <!-- Profile Picture -->
                            <div class="min-w-[32px] min-h-[32px] w-[32px] h-[32px] grid bg-white border-[1px] border-custom-black/10 rounded-full overflow-hidden relative ">
                                <Icon icon="mynaui:sparkles" height="24px" class="text-custom-dark-blue1 m-auto" />
                            </div>

                            <!-- Name -->
                            <div class="w-fit grid text-left">
                                <p class="text-[14px] truncate font-semibold select-none">Upgrade Plan</p>
                                <p class="text-[13px] text-custom-gray truncate -mt-1">Get unlimited requests and more</p>
                            </div>
                        </div>
                    </button>

                    <!-- User Account -->
                    <button @click="account_menu = !account_menu;" :class="account_menu ? 'bg-custom-hover-gray' : 'hover:bg-custom-hover-gray'" class="w-full h-fit grid items-center px-2 py-[4px]  rounded-[8px] relative account-menu">
                        <!-- Menu -->
                        <div v-if="account_menu" class="w-full h-fit grid gap-2 p-2 bg-white border-[1px] border-custom-black/10 rounded-[8px] left-0 -top-[150px] absolute shadow-newdrop">
                            <!-- Customize -->
                            <button class="w-full h-fit flex items-center gap-2 px-2 py-[4px] hover:bg-custom-hover-gray rounded-[2px]">
                                <Icon icon="carbon:area-custom" height="18px" class="text-custom-black" />
                                <p class="select-none">Cutomize ChatGPT</p>
                            </button>

                            <!-- Settings -->
                            <button @click="view_settings = true;" class="w-full h-fit flex items-center gap-2 px-2 py-[4px] hover:bg-custom-hover-gray rounded-[2px]">
                                <Icon icon="solar:settings-broken" height="18px" class="text-custom-black" />
                                <p class="select-none">Settings</p>
                            </button>

                            <hr>
                            
                            <!-- Logout -->
                            <button @click="logout" class="w-full h-fit flex items-center gap-2 px-2 py-[4px] hover:bg-custom-hover-gray rounded-[2px]">
                                <Icon icon="ic:round-logout" height="18px" class="text-custom-black" />
                                <p class="select-none">Log out</p>
                            </button>
                        </div>

                        <!-- Account -->
                        <div class="w-fit flex gap-2 items-center truncate account-menu">
                            <!-- Profile Picture -->
                            <div class="min-w-[32px] min-h-[32px] w-[32px] h-[32px] grid rounded-full overflow-hidden relative account-menu">
                                <img :src="session.profile_picture" alt="Account Profile Picture" class="m-auto object-cover w-[32px] h-[32px] select-none account-menu">
                            </div>

                            <!-- Name -->
                            <div class="w-fit grid">
                                <p class="text-[14px] truncate font-semibold select-none account-menu">{{ session.name }}</p>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Body -->
        <div id="chat" class="w-full h-full grid md:justify-items-center pt-[100px] px-4 md:p-6 relative">
            <div class="w-full h-fit hidden md:grid items-center relative">
                <p class="w-full text-left text-[24px] font-medium">GPT4 Unlimited <span class="opacity-50">v0.1 Beta</span></p>

                <!-- Save Button -->
                <button @click="save_chat = true;" class="w-[32px] h-[32px] grid bg-white rounded-[4px] border-[1px] border-custom-black/10 right-0 absolute">
                    <Icon icon="circum:save-down-2" height="24px" class="m-auto text-custom-dark-blue1" />
                </button>
            </div>

            <div id="chat-view" class="bottom-6 absolute w-full md:w-[65%] h-fit max-h-[90%] md:pr-6 grid gap-4 md:gap-8 md:overflow-auto px-4 py-6 md:px-0 md:py-0">
                <!-- No Messages -->
                <div v-if="messages.length == 0" class="w-full h-fit grid gap-48 md:gap-96">
                    <div class="mx-auto w-fit h-fit grid justify-items-center gap-2 select-none">
                        <!-- Icon -->
                        <div class="min-w-[48px] min-h-[48px] w-[48px] h-[48px] grid bg-white border-[1px] border-custom-black/10 rounded-full">
                            <Icon icon="simple-icons:openai" height="32px" class="m-auto text-custom-dark-blue1" />
                        </div>

                        <!-- Header -->
                        <p class="text-[24px] font-bold text-custom-dark-blue1 text-center">How can I help you today?</p>
                    </div>

                    <!-- Sugestions -->
                    <div class="w-full h-fit grid grid-cols-2 gap-4">
                        <button v-for="(e, i) in 4" class="w-full h-fit grid p-2 md:p-4 border-[1px] border-custom-black/10 rounded-[8px] md:rounded-[15px] select-none text-left bg-white hover:bg-custom-hover-gray/30">
                            <p class="text-[13px] md:text-[15px] text-custom-dark-blue font-bold truncate">Write a message</p>
                            <p class="text-[13px] md:text-[15px] text-custom-gray opaity-70 font-medium truncate">that goes with a kitten gif for a friend on a rough day</p>
                        </button>
                    </div>
                </div>

                <!-- Messages -->
                <div v-for="(message, index) in messages" class="w-full h-fit flex gap-4">
                    <!-- AI Icon -->
                    <div v-if="message.type == 'response'" class="min-w-[28px] min-h-[28px] w-[28px] h-[28px] grid bg-custom-dark-blue2 rounded-full select-none">
                        <Icon icon="simple-icons:openai" height="18px" class="m-auto text-white" />
                    </div>
                    <!-- User Profile Picture -->
                    <div v-else class="min-w-[28px] min-h-[28px] w-[28px] h-[28px] grid rounded-full overflow-hidden relative account-menu">
                        <img :src="session.profile_picture" alt="Account Profile Picture" class="m-auto object-cover w-[28px] h-[28px] select-none account-menu">
                    </div>

                    <div class="w-full h-fit grid gap-1 group">
                        <!-- Name -->
                        <p class="text-[17px] font-bold text-custom-dark-blue1 select-none">{{ message.type == 'prompt' ? session.name : 'GPT4 Unlimited' }}</p>
                        <!-- Text -->
                        <p class="text-[15px]">{{ message.text }}</p>

                        <!-- Message Actions -->
                        <div class="w-fit h-fit flex itmes-center gap-4 invisible group-hover:visible">
                            <!-- Copy to clipboard -->
                            <Icon @click="copy(message.text)" icon="iconoir:paste-clipboard" height="16px" class="m-auto hover:text-custom-dark-blue1 text-custom-gray/50 cursor-pointer" />
                        </div>
                    </div>
                </div>

                <!-- Show AI typing -->
                <div v-if="show_typing" class="w-full h-fit flex gap-4">
                    <div class="min-w-[28px] min-h-[28px] w-[28px] h-[28px] grid bg-custom-dark-blue2 rounded-full">
                        <Icon icon="simple-icons:openai" height="18px" class="m-auto text-white" />
                    </div>

                    <div class="w-full h-fit grid gap-1">
                        <p class="text-[17px] font-bold text-custom-dark-blue1">GPT4 Unlimited</p>
                        <Icon icon="svg-spinners:3-dots-scale" height="26px" class="text-custom-dark-blue1" />
                    </div>
                </div>

                <!-- Prompt Input -->
                <div class="w-full h-fit mt-8 relative">
                    <textarea v-model="prompt" id="prompt-input" @keydown="handleKey" @input="adjust_input()" style="height: 48px; min-height: 48px !important; max-height: 250px" class="resize-none w-full" placeholder="Message GPT4 Unlimited..."></textarea>

                    <!-- Send Button -->
                    <button @click="send" :disabled="!prompt" class="w-[30px] h-[30px] bg-custom-dark-blue2 disabled:opacity-50 rounded-[4px] absolute bottom-4 right-4">
                        <Icon icon="mynaui:arrow-up" height="24px" class="m-auto text-white" />
                    </button>
                </div>
            </div>

            <!-- Scroll to Bottom Button -->
            <button @click="scrollToBottom" class="h-[42px] w-[42px] hidden md:grid absolute right-6 bottom-8 bg-custom-dark-blue2 rounded-[4px] shadow-newdrop">
                <Icon icon="ep:bottom" height="32px" class="m-auto text-white" />
            </button>
        </div>
    </div>
</template>

<script>
import { Icon } from '@iconify/vue';

import saveChat from '../../components/popups/saveChat.vue'
import settings from '../../components/popups/settings.vue'

export default {
    name: "Dashboard",
    data(){
        return{
            session: this.$session.get_session(),
            
            show_sidebar: true,
            account_menu: false,

            chats: [],
            chat: null,
            messages: [],

            prompt: "",
            show_typing: false,

            save_chat: false,
            view_settings: false
        }
    },
    async mounted(){
        // Event Listener to close the menu on clicks
        document.addEventListener("click", (e) => {
            if(this.account_menu && !e.target.classList.contains('account-menu')){
                this.account_menu = false;
            }
        })

        this.scrollToBottom()
    },
    watch:{
        show_sidebar: function(value){
            // Change sidebar element visibilty during open and close animation to make it cleaner
            let elms = document.getElementsByClassName('sidebar-elm')
            for(let i =  0; i <= elms.length - 1; i++){
                if(value){
                    setTimeout(() => {
                        elms[i].classList.replace('invisible', 'visible')
                    }, 600);
                }else{
                    elms[i].classList.replace('visible', 'invisible')
                }
            }
        }
    },
    methods: {
        async logout(){
            const res = await this.$apiHandler.get('auth/logout')
            this.$session.clear_session();
            this.$router.push({name: "Login"})
        },
        adjust_input(e){
            let input = document.getElementById('prompt-input');
            input.style.height = '1px';
            input.style.height = input.scrollHeight+'px';
        },
        scrollToBottom(){
            setTimeout(() => {
                let chat_view = document.getElementById('chat-view');
                let chat = document.getElementById('chat');
                chat_view.scrollTo({ left: 0, top: chat_view.scrollHeight, behavior: "smooth" });
                chat.scrollTo({ left: 0, top: chat.scrollHeight, behavior: "smooth" });
            }, 200);
        },
        handleKey(e){
            // Check if CTRL + Enter was pressed to send message
            if(e.key == 'Enter' && e.ctrlKey){
                this.send()
            }
        },
        copy(text){
            navigator.clipboard.writeText(text);
            this.$notification.add({
                title: "Copy to Clipboard",
                text: "the message was copied to your clipboard",
                type: "success"
            })
        },
        async send(){
            this.$gloading.start();
            this.messages.push({type: 'prompt', text: this.prompt})
            this.scrollToBottom()

            this.prompt = "";
            this.show_typing = true;
            setTimeout(() => {
                this.show_typing = false;
                this.messages.push({type: 'response', text: 'Here is my ai response to the question.'})
                this.scrollToBottom()
            }, 2000);

            
            this.$gloading.stop();
        }
    },
    components: {
        Icon,
        saveChat,
        settings
    }
}

</script>