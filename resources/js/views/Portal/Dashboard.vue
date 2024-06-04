<template>
    <!-- Save Chat Popup - reload chats on session update -->
    <saveChat v-if="save_chat" @close="save_chat = false" @updateSession="session = $session.get(); view_chats = false; view_chats = true;" />
    <!-- Settings Popup -->
    <settings v-if="view_settings" @close="view_settings = false" @updateSession="session = $session.get()" />

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
            <Icon v-if="!this.$route.params.chat" @click="save_chat = true" icon="circum:save-down-2" height="26px" class="right-2 absolute text-custom-gray cursor-pointer" />
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
                    <button @click="$router.replace({name: $route.name, params: {}})" :disabled="show_typing" class="w-full h-fit grid items-center px-2 py-[4px] hover:bg-custom-hover-gray rounded-[8px] absolute top-0">
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
                    <div v-if="view_chats" class="w-full min-h-fit h-fit max-h-full mt-8 grid pr-2 relative overflow-y-auto">
                        <!-- Timeframes -->
                        <div v-for="(group, gindex) in Object.keys(session.chats)" :class="gindex == 0 ? '' : 'mt-6'" class="w-full h-fit grid gap-[2px]">
                            <!-- Header -->
                            <p class="px-2 text-[14px] text-custom-gray opacity-70 pb-2">{{ group }}</p>

                            <!-- Chats -->
                            <button v-for="(chat, index) in session.chats[group]" @click="$router.push({params: {chat: chat.id}})" :disabled="show_typing" :class="$route.params.chat == chat.id ? 'bg-custom-hover-gray' : 'hover:bg-custom-hover-gray'" class="w-full h-[36px] grid items-center px-2 py-[4px] rounded-[8px] relative pr-[36px] group">
                                <p class="text-[14px] text-left h-fit font-semibold truncate select-none">{{ chat.name }}</p>

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
                            <!-- Upgrade -->
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
                            <profile_picture :link="session.profile_picture" :add_class="['account-menu']" />

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
        <div v-if="ready" id="chat" class="w-full h-full grid md:justify-items-center pt-[100px] px-4 md:p-6 relative">
            <!-- desktop header - hidden on mobile -->
            <div class="w-full h-fit hidden md:grid items-center relative">
                <div class="w-fit h-fit flex items-center gap-6 text-[24px]">
                    <!-- chat name -->
                    <div v-if="chat">
                        <!-- update name form -->
                        <form v-if="edit_name" @submit.prevent="updateName" class="w-fit h-fit flex items-center gap-6">
                            <!-- new name -->
                            <input v-model="new_name" type="text" style="height: 36px; width: 350px; font-size: 18px;">

                            <div class="w-fit h-fit flex items-center gap-6">
                                <!-- close button -->
                                <button type="button" @click="edit_name = false; new_name = '';"><Icon icon="material-symbols-light:close" height="26px" class="text-custom-red" /></button>
                                <!-- save button -->
                                <button><Icon icon="codicon:check" height="26px" class="text-custom-green" /></button>
                            </div>
                        </form>

                        <!-- edit name button -->
                        <button v-else @click="new_name = chat; edit_name = true;" :disabled="show_typing" class="w-fit h-fit flex items-center gap-2 text-custom-dark-blue1">
                            <!-- chat name -->
                            <p class="text-[24px] whitespace-nowrap">{{ chat }}</p>
                            <Icon icon="jam:write" height="20px" class="text-custom-black" />
                        </button>
                    </div>
                    <p v-else class="w-full text-left font-medium">GPT4 Unlimited <span class="opacity-50">v0.1 Beta</span></p>
                </div>

                <!-- Save Button -->
                <button v-if="!this.$route.params.chat" @click="save_chat = true;" :disabled="show_typing" class="w-[32px] h-[32px] grid bg-white rounded-[4px] border-[1px] border-custom-black/10 right-0 absolute">
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
                    <div v-if="message.role == 'assistant'" class="min-w-[28px] min-h-[28px] w-[28px] h-[28px] grid bg-custom-dark-blue2 rounded-full select-none">
                        <Icon icon="simple-icons:openai" height="18px" class="m-auto text-white" />
                    </div>
                    <!-- User Profile Picture -->
                    <profile_picture v-else :link="session.profile_picture" icon_height="18px" height="28px" />

                    <div class="w-full h-fit grid gap-1 group">
                        <!-- Name -->
                        <p class="text-[17px] font-bold text-custom-dark-blue1 select-none">{{ message.role == 'user' ? session.name : 'GPT4 Unlimited' }}</p>
                        <!-- Text -->
                        <MdPreview :modelValue="message.content" language="en-US" />

                        <!-- Message Actions -->
                        <div class="w-fit h-fit flex itmes-center gap-4 invisible group-hover:visible">
                            <!-- Copy to clipboard -->
                            <Icon @click="copy(message.content)" icon="iconoir:paste-clipboard" height="16px" class="m-auto hover:text-custom-dark-blue1 text-custom-gray/50 cursor-pointer" />
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

                <div class="w-full h-fit grid pb-8 md:pb-0">
                    <!-- Prompt Input -->
                    <div class="w-full h-fit mt-8 relative">
                        <MdEditor v-model="prompt" :disabled="show_typing" @keydown="handleKey" :preview="false" autoDetectCode language="en-US" id="prompt-input" class="!min-h-[50px] !h-fit !max-h-[250px] !overflow-y-auto" />

                        <!-- Send Button -->
                        <div class="w-fit h-[52px] grid items-center absolute bottom-0 right-4">
                            <button @click="send" :disabled="!prompt" class="w-[30px] h-[30px] bg-custom-dark-blue2 disabled:opacity-50 rounded-[4px]">
                                <Icon icon="mynaui:arrow-up" height="24px" class="m-auto text-white" />
                            </button>
                        </div>
                    </div>
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

import profile_picture from '../../components/profile_picture.vue'
import saveChat from '../../components/popups/saveChat.vue'
import settings from '../../components/popups/settings.vue'
import { MdPreview, MdEditor  } from 'md-editor-v3';

export default {
    name: "Dashboard",
    data(){
        return{
            ready: false,
            session: this.$session.get(),
            
            view_chats: true,

            show_sidebar: true,
            account_menu: false,

            new_name: "",
            edit_name: false,
            // chat is the chat name
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

        const chat = this.$route.params.chat;
        await this.getChat(chat ? chat : 'session')
        
        this.ready = true;
        this.scrollToBottom();
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
        },
        '$route.params.chat': async function (value){
            if(!value){
                this.chat = null;
            }

            this.prompt = "";
            await this.getChat(value ? value : 'session')
        }
    },
    methods: {
        async logout(){
            this.$gloading.start();

            const res = await this.$apiHandler.get('auth/logout/all')
            this.$session.clear();

            this.$gloading.stop();
            this.$router.push({name: "Login"})
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
        async updateName(){
            this.$gloading.start();
            const new_name = this.new_name;
            this.edit_name = false;
            this.new_name = "";

            const res = await this.$apiHandler.put('chat/new-name/'+this.$route.params.chat, {name: new_name})
            if(res.status == 200){
                this.chat = new_name;

                // update session to update sidebar chat name
                await this.$session.valid();
                this.session = this.$session.get();
            }

            this.$gloading.stop();
        },
        async getChat(chat = 'session'){
            this.$gloading.start();

            const res = await this.$apiHandler.get('chat/get/'+chat)
            // route had a chat id that the user cannot access clear it and get the session chat
            if(chat != 'session' && res.status >= 400){
                this.$router.replace({name: this.$route.name, params: {}});
            }else if(res.status == 200){
                this.messages = res.data.chat.messages

                if(chat != 'session'){
                    this.chat = res.data.chat.name
                }
                this.scrollToBottom()
            }

            this.$gloading.stop();
        },
        async send(){
            // no loading because when showing typing is set to true all inputs and buttons are disabled
            // check to make sure a prompt is entered
            this.show_typing = true;

            if(!this.prompt || !this.prompt.trim()){
                this.$notification.add({
                    title: "Validation Error",
                    text: "please add a prompt to proceed.",
                    type: "warn"
                });
                
                this.show_typing = false;
                return;
            }

            const prompt = this.prompt;
            this.messages.push({role: 'user', content: prompt})
            this.scrollToBottom()

            this.prompt = "";
            
            const request_alert = {title: "GPT4 Unlimited"}

            const res = await this.$apiHandler.post('chat/new-prompt/'+(this.$route.params.chat ? this.$route.params.chat : 'session'), {prompt: prompt}, {}, request_alert)
            if(res.status == 200){
                this.show_typing = false;
                this.messages.push(res.data.assistant_response)
                this.scrollToBottom()
            }
        }
    },
    components: {
        Icon,
        profile_picture,
        saveChat,
        settings,
        MdPreview,
        MdEditor
    }
}

</script>