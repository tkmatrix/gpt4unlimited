<template>
    <div class="w-screen min-h-screen h-fit md:h-screen grid md:overflow-hidden p-8 md:p-24 relative">
        <!-- Panda Image -->
        <img id="panda" src="../../../assets/images/pandadown.png" alt="Panda" class="hidden md:block absolute rotate-[105deg] -left-[225px] top-[150px] scale-50">

        <!-- Logo at top -->
        <img src="../../../assets/images/aiLogo.png" alt="AI Logo" class="h-[35px] mx-auto md:mx-0 md:top-8 md:left-1/2 md:absolute">

        <div class="mt-24 md:mt-96 w-full h-fit grid md:gap-6 gap-12 relative mb-12 md:mb-0">
            <p class="text-[26px] md:text-[32px] text-custom-dark-blue font-medium select-none bg-white md:w-[50%]">{{ typewriter.value }} <span :class="blink ? 'invisible' : 'visible'">|</span></p>

            <!-- Dynamic Fields -->
            <div v-if="show_fields && 'value' in fields[active_field]" class="w-full md:w-[45%] h-fit grid md:mt-8">
                <p class="text-[18px] md:text-[22px] text-custom-gray/50">{{ fields[active_field].label }}</p>
                <!-- Upload Button -->
                <div v-if="fields[active_field].code == 'pfp'" class="w-full h-fit grid md:grid-cols-2 gap-6">
                    <!-- PFF Uplpoad -->
                    <div class="w-full h-fit grid">
                        <!-- Upload -->
                        <button @click="$refs.pfpUpload.click()" class="w-full h-[52px] flex items-center gap-4 px-4 border-[1px] border-custom-black/10 rounded-[5px]">
                            <input @change="pfpUpload($event)" type="file" accept=".pgn,.jpg,.jpeg" ref="pfpUpload" class="hidden">

                            <Icon icon="material-symbols:upload" height="36px" class="text-custom-dark-blue" />
                            <p class="text-[20px] truncate">Upload Profile Picture</p>
                        </button>

                        <!-- Image Name -->
                        <p v-if="fields[active_field].value" class="mt-2 text-custom-dark-blue font-medium text-[14px] truncate max-w-[80%]">Uploaded | {{ fields[active_field].value.name }}</p>
                    </div>

                    <!-- Show Image -->
                    <div class="w-full h-fit grid md:-mt-[34px]">
                        <div class="mx-auto h-[200px] w-[200px] grid rounded-full border-[1px] border-custom-black/10 overflow-hidden">
                            <Icon v-if="!fields[active_field].dataUrl" icon="mdi-light:image" height="100px" class="text-custom-gray/20 m-auto" />
                            <img v-else :src="fields[active_field].dataUrl" alt="Profile Picture" class="m-auto object-cover h-[100%] w-[100%]">
                        </div>
                    </div>
                </div>

                <!-- Text Input -->
                <input v-else v-model="fields[active_field].value" type="text" class="text-[18px] md:text-[20px] w-full md:w-[50%]">
            </div>
            
            <!-- Continue Button -->
            <button v-if="show_fields" :disabled="isTyping" @click="next()" class="w-full md:w-fit h-[52px] px-4 text-[18px] md:text-[22px] text-white font-bold bg-custom-dark-blue rounded-[5px] disabled:opacity-50">{{ active_field == fields.length - 1 ? 'Enter Portal' : 'Continue' }}</button>
        </div>

        <!-- Terms of Use and Privacy Policy at the Bottom -->
        <div class="w-full h-fit grid justify-items-center select-none bottom-8 absolute">
            <p class="text-center text-custom-dark-blue font-medium">Terms of Use <span class="mx-4">|</span> Privacy Policy</p>
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
        }, 3000)
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
                    console.log(self.fields, this.fields)
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
            this.fields.filter(obj => 'value' in obj).forEach(obj => {
                if('value' in obj){
                    data[obj.code] = obj.value
                }
            })

            let form_data = new FormData()
            Object.keys(data).forEach(key => {
                if(key != 'pfp'){
                    form_data.append(key, data[key])
                }
            })

            console.log(data)

            this.$gloading.stop()
        }
    },
    components: {
        Icon
    }
}

</script>