<template>
    <div class="w-full h-screen grid absolute bg-custom-gray/20 z-[85]">
        <!-- Save Chat -->
        <div class="w-[90%] md:w-[40%] h-fit m-auto grid bg-white rounded-[15px] shadow-newdrop">
            <!-- Header -->
            <div class="w-full p-6 grid items-center relative">
                <p class="text-[20px] text-custom-dark-blue1 font-semibold select-none">Save New Chat</p>

                <!-- close button -->
                <Icon @click="$emit('close')" icon="material-symbols-light:close" height="36px" class="right-6 absolute text-custom-gray cursor-pointer" />
            </div>
            <hr>

            <form @submit.prevent="save" class="w-full p-6 grid md:flex items-center gap-4 relative">
                <!-- chat name -->
                <input v-model="name" type="text" placeholder="Enter a name for the chat.." class="w-full" >

                <!-- save chat button -->
                <input :disabled="!name" type="submit" value="Continue" class="w-full md:w-fit h-[48px] md:h-full text-white font-bold px-4 rounded-[6px] !bg-custom-dark-blue2 disabled:opacity-50">
            </form>
        </div>
    </div>
</template>

<script>
import { Icon } from '@iconify/vue';

export default {
    name: "Popups - Save Chat",
    data(){
        return {
            name: ""
        }
    },
    methods: {
        async save() {
            this.$gloading.start();

            const res = await this.$apiHandler.post('chat/save-session', {name: this.name}, {}, {title: "Save Chat"});
            this.$gloading.stop();
            
            if(res.status == 200){
                this.$router.replace({name: this.$route.name, params: {chat: res.data.id}});
                location.replace('/a/portal/dashboard/'+res.data.id)
            }
        }
    },
    components: {
        Icon
    }
}

</script>