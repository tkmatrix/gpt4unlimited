<template>
    <div class="w-full md:h-screen grid md:grid-cols-3">
        <!-- Left Side -->
        <div class="w-full h-[500px] md:h-full grid p-6 md:p-24 bg-custom-dark-blue md:col-span-2">
            <div class="w-full h-fit grid m-auto">
                <p class="text-white font-bold text-[32px] select-none">{{ typewriter.value }} <span :class="blink ? 'invisible' : 'visible'">|</span></p>
            </div>
        </div>
        
        <!-- Right Side -->
        <div class="w-full h-fit py-32 md:py-0 md:h-full grid bg-sidebar-bg relative">
            <!-- Logo at the Top -->
            <div class="w-full h-fit grid justify-items-center top-8 absolute">
                <img src="../../assets/images/aiLogo.png" alt="AI Logo" class="h-[35px]">
            </div>

            <div class="m-auto w-[55%] grid h-fit gap-8">
                <p class="text-custom-dark-blue text-center font-bold text-[32px] truncate select-none">Get Started</p>

                <div class="w-full h-fit grid grid-cols-2 gap-4">
                    <button @click="$router.push({name: 'Login'})" class="w-full h-[42px] text-white font-bold text-[18px] rounded-[5px] bg-custom-dark-blue border-[1px] border-custom-dark-blue">Login</button>
                    <button @click="$router.push({name: 'Login', query: {type: 'signup'}})" class="w-full h-[42px] text-custom-dark-blue font-bold text-[18px] bg-white rounded-[5px] border-[1px] border-custom-dark-blue">Sign up</button>
                </div>
            </div>

            <!-- Terms of Use and Privacy Policy at the Bottom -->
            <div class="w-full h-fit grid justify-items-center bottom-8 absolute select-none">
                <p class="text-center text-custom-dark-blue font-medium">Terms of Use <span class="mx-4">|</span> Privacy Policy</p>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "Home",
    data() {
        return{
            blink: false,
            typewriter: {
                value: "",
                data: "",
                istyping: false
            },
        }
    },
    mounted() {
        // Continues Blink
        setInterval(() => {
            this.blink = !this.blink
        }, 200)

        this.typewriter.data = "Experience the TRUE POWER of GPT4...";
        setTimeout(() => {
            this.typewriter.data = "Unlimited access to the best GPT4 models refined speficically for you!";

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
        }
    },
    methods: {
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
        }
    }
}

</script>