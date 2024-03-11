<template>
    <div class="w-full md:h-screen grid md:grid-cols-3">
        <!-- Left Side -->
        <div class="w-full h-[500px] md:h-full grid p-6 md:p-24 bg-gradient-to-r from-custom-dark-blue1 from-25% to-custom-dark-blue2 md:col-span-2">
            <div class="w-full h-fit grid m-auto">
                <p class="text-white font-bold text-[32px] md:select-none">{{ typewriter.value }} <span :class="blink ? 'invisible' : 'visible'">|</span></p>
            </div>
        </div>
        
        <!-- Right Side -->
        <div class="w-full h-fit py-32 md:py-0 md:h-full grid bg-sidebar-bg relative">
            <!-- Logo at the Top -->
            <div class="w-full h-fit grid justify-items-center top-8 absolute">
                <img src="../../../assets/images/aiLogo.png" alt="AI Logo" class="h-[35px]">
            </div>

            <div class="w-full md:w-[55%] h-fit grid gap-12 m-auto p-6 md:p-0">
                <div v-if="show_fields && 'value' in fields[active_field]" class="w-full h-fit grid">
                    <p class="text-[18px] md:text-[22px] text-custom-gray/50">{{ fields[active_field].label }}</p>
                    <div v-if="fields[active_field].code == 'pfp'" class="w-full h-fit grid gap-6">
                        <!-- PFF Uplpoad -->
                        <div class="w-full h-fit grid">
                            <!-- Upload -->
                            <button @click="$refs.pfpUpload.click()" class="w-full h-[52px] flex items-center gap-4 px-4 border-[1px] border-custom-black/10 rounded-[5px]">
                                <input @change="pfpUpload($event)" type="file" accept=".pgn,.jpg,.jpeg" ref="pfpUpload" class="hidden">

                                <Icon icon="material-symbols:upload" height="36px" class="text-custom-dark-blue1" />
                                <p class="text-[20px] truncate">Upload Profile Picture</p>
                            </button>

                            <!-- Image Name -->
                            <p v-if="fields[active_field].value" class="mt-2 text-custom-dark-blue1 font-medium text-[14px] truncate max-w-[80%]">Uploaded | {{ fields[active_field].value.name }}</p>
                        </div>

                        <!-- Show Image -->
                        <div class="w-full h-fit grid">
                            <div class="mx-auto h-[200px] w-[200px] grid rounded-full border-[1px] border-custom-black/10 overflow-hidden">
                                <Icon v-if="!fields[active_field].dataUrl" icon="mdi-light:image" height="100px" class="text-custom-gray/20 m-auto" />
                                <img v-else :src="fields[active_field].dataUrl" alt="Profile Picture" class="m-auto object-cover h-[100%] w-[100%]">
                            </div>
                        </div>
                    </div>

                    <!-- Text Input -->
                    <input v-else v-model="fields[active_field].value" type="text" class="text-[18px] md:text-[20px] w-full">
                </div>

                <!-- Continue Button -->
                <button v-if="show_fields" :disabled="isTyping" @click="next()" class="w-full h-[52px] px-4 text-[18px] md:text-[22px] text-white font-bold bg-custom-dark-blue1 rounded-[5px] disabled:opacity-50">{{ active_field == fields.length - 1 ? 'Enter Portal' : 'Continue' }}</button>
            </div>

            
            
            <!-- Terms of Use and Privacy Policy at the Bottom -->
            <div class="w-full h-fit grid justify-items-center bottom-8 absolute md:select-none">
                <p class="text-center text-custom-dark-blue1 font-medium">Terms of Use <span class="mx-4">|</span> Privacy Policy</p>
            </div>
        </div>
    </div>
</template>

<script>

import { Icon } from '@iconify/vue';

export default {
    name: "Account Setup",
    data(){
        return{
            blink: false,
            typewriter: {
                value: "",
                data: "",
                istyping: false
            },

            show_fields: false,
            active_field: -1,
            fields: [
                {
                    label: "Full Name",
                    code: "name",
                    typewrite: "First we need your name.",
                    required: true,
                    value: null
                },
                {
                    label: "Main Use Case",
                    code: "use_case",
                    typewrite: "What is the main use case for this account? Work, School, Personal, Copy Writing.... You can be as detailed as you want.",
                    required: true,
                    value: null
                },
                {
                    label: "Profile Picture",
                    code: "pfp",
                    typewrite: "You're done! You can upload a profil picture here or continue into your account.",
                    dataUrl: null,
                    required: false,
                    value: null
                },
                {
                    code: "billing_notification",
                    typewrite: "Before you enter let me explain how billing works... You will be billed per your monthly usage; see Terms of Use: Billing.",
                },
                {
                    code: "billing_notification",
                    typewrite: "But dont worry... You're first month is FREE!! You can even set budget limits per month and be alerted when you are reaching them.",
                }
            ]
        }
    },
    mounted(){
        // Continues Blink
        setInterval(() => {
            this.blink = !this.blink
        }, 200)

        this.typewriter.data = "Welcome to your GPT4 Unlimited account setup!";
        setTimeout(() => {
            this.typewriter.data = "Please add in some additional information to customize the AI to your needs!";

            setTimeout(() => {
                this.show_fields = true;
                this.active_field = 0;
            }, 7500)
        }, 4000)
    },
    watch: {
        'typewriter.data': async function(value){
            if(value){
                this.typewriter.value = ""
                await this.write(value)
            }
        },
        active_field: function(value){
            this.typewriter.data = this.fields[value].typewrite
        }
    },
    methods: {
        pfpUpload(e){
            const file = e.target.files[0];
            if(file){
                const pfpIndex = this.fields.findIndex(obj => obj.code == 'pfp')
                this.fields[pfpIndex].value = Object.assign(file, {})

                const reader = new FileReader();
                const self  = this;

                reader.addEventListener("load", function () {
                    self.fields[pfpIndex].dataUrl = reader.result;
                })

                reader.readAsDataURL(file);
            }
        },
        async write(value){
            const itterate = value.split("");
            
            let i = 0;

            let audio = new Audio(window.location.protocol+'//'+window.location.host+'/storage/audio/typing.mp3')
            audio.currentTime = 0;
            audio.volume = 0.50;
            audio.loop = true;
            audio.play();

            this.isTyping = true;
            let interval = setInterval(() => {
                if(!(i <= (itterate.length - 1))){
                    audio.pause();
                    this.isTyping = false
                    clearInterval(interval)
                    return;
                }

                this.typewriter.value = this.typewriter.value+itterate[i];
                i++;
            }, 75)
        },
        next(){
            // Validate Fields
            let data = this.fields[this.active_field]
            if('value' in data && !data.value && data.required){
                this.$notification.add({
                    title: "Validation Error",
                    text: "this field is required, please enter an answer.",
                    type: "warn"
                })
                
                return
            }

            // // Last Step
            if(this.active_field == this.fields.length - 1){
                return this.save_data()
            }

            // Next Step
            this.active_field++
        },
        async save_data(){
            this.$gloading.start()

            let data = {}
            // build data object based on the fields
            this.fields.filter(obj => 'value' in obj).forEach(obj => {
                if('value' in obj){
                    data[obj.code] = obj.value
                }
            })

            // Account Profile Picture
            const pfp_alert = {
                title: "Account Profile Picture"
            }
            const upload_pfp = await this.$apiHandler.form('account/picture', {profile_picture: data.pfp}, {}, pfp_alert)

            // Account Setup
            const setup_alert = {
                title: "Account Setup",
                default_msg: "we were unable to complete setting up your account at this time."
            }

            // remove pfp as to not update the pfp is in the table with the object
            delete data.pfp
            const setup_res = await this.$apiHandler.post('account/setup', data, {}, setup_alert)
            this.$gloading.stop()

            if(setup_res.status == 200){
                this.$router.push({name: "Dashboard"})
            }
        }
    },
    components: {
        Icon
    }
}

</script>