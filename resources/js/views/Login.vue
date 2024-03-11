<template>
    <div class="w-full h-screen grid relative overflow-hidden">
        <!-- Logo at the Top -->
        <div class="w-full h-fit grid justify-items-center top-8 absolute">
            <img src="../../assets/images/aiLogo.png" alt="AI Logo" class="h-[35px]">
        </div>
        <!-- Login Form -->
        <form @submit.prevent="enter_password ? check() : enter_password = true" class="m-auto w-[320px] h-fit grid gap-6">
            <p class="text-custom-dark-blue1 font-bold text-[32px] text-center truncate md:select-none">{{ type == 'login' ? 'Welcome back' : 'Create you account' }}</p>
            <!-- Email Field -->
            <div class="w-full h-fit grid items-center relative">
                <!-- Email -->
                <input v-model="form.email" type="email" placeholder="Email address" :disabled="enter_password" :class="enter_password ? 'pr-[56px] disabled:opacity-70' : ''">
                <button v-if="enter_password" type="button" @click="enter_password = false" class="right-4 absolute text-custom-dark-blue1 hover:underline" >Edit</button>
            </div>

            <!-- Password Field -->
            <div v-if="enter_password" class="w-full h-fit grid items-center relative">
                <!-- Password -->
                <input v-model="form.password" :type="view_password ? 'text' : 'password'" placeholder="Password" class="pr-[48px]">
                <Icon @click="view_password = !view_password" :icon="view_password ? 'quill:eye' : 'quill:eye-closed'" type="button" height="26px" class="right-4 absolute text-custom-gray cursor-pointer" />
            </div>
            
            <!-- Confirm Password Field -->
            <div v-if="enter_password && type == 'signup'" class="w-full h-fit grid items-center relative">
                <!-- Password -->
                <input v-model="form.confirm_password" :type="view_password ? 'text' : 'password'" placeholder="Confirm Password" class="pr-[48px]">
                <Icon @click="view_password = !view_password" :icon="view_password ? 'quill:eye' : 'quill:eye-closed'" type="button" height="26px" class="right-4 absolute text-custom-gray cursor-pointer" />
            </div>

            <!-- Forgot Password -->
            <a v-if="enter_password && type == 'login'" href="#" class="-mt-2 font-medium text-[14px] text-custom-dark-blue1">Forgot Password?</a>

            <!-- Continue Button -->
            <input type="submit" value="Continue">

            <!-- Change Type -->
            <p class="text-[14px] text-center md:select-none">{{ type == "login" ? "Don't" : "Already" }} have an account? 
                <span @click="type == 'login' ? type='signup' : type='login'" type="button" class="text-custom-dark-blue1 font-medium cursor-pointer">{{ type == 'login' ? 'Sign up' : 'Login' }}</span>
            </p>

            <div class="realtive grid justify-items-center items-center text-[14px] w-full h-fit">
                <div class="w-full border-b-[1px] border-custom-black/10"></div>
                <p class="w-fit absolute bg-white px-4 md:select-none">OR</p>
            </div>

            <!-- Continue With Google -->
            <button type="button" class="w-full h-[52px] grid items-center px-4 border-[1px] border-custom-black/10 rounded-[5px] md:select-none">
                <div class="w-fit h-fit flex items-center gap-4">
                    <img src="../../assets/images/google.png" alt="Google" style="height: 24px;">
                    <p>Continue with Google</p>
                </div>
            </button>
        </form>
        
        <!-- Terms of Use and Privacy Policy at the Bottom -->
        <div class="w-full h-fit grid justify-items-center bottom-8 absolute md:select-none">
            <p class="text-center text-custom-dark-blue1 font-medium">Terms of Use <span class="mx-4">|</span> Privacy Policy</p>
        </div>
    </div>
</template>

<script>

import { Icon } from '@iconify/vue';

export default{
    name: "Login",
    data(){
        return{
            // types: login, signup
            type: 'login',
            enter_password: false,
            view_password: false,

            form: {
                email: null,
                password: null,
                confirm_password: null
            }
        }
    },
    watch: {
        // Reset Values on Type Change
        type: function(value){
            this.enter_password = false
            this.view_password = false

            Object.keys(this.form).forEach(item => {
                this.form[item] = null
            })
        },
        // Check email before allowing the user to enter a password
        enter_password: async function(value){
            if(value){
                this.$gloading.start()
                // Validate Email - Backend does validate but not the "."
                if(!this.form.email || this.form.email.lenth > 5 || !(this.form.email.includes('@') && this.form.email.includes('.'))){
                    this.enter_password = false
                    this.$notification.add({
                        title: "Validation Error",
                        text: "please check the email and try again",
                        type: "warn"
                    })

                    this.$gloading.stop()
                    return
                }

                // Check if the Email Exists and allow continue based on the type
                // email exists and login = contine
                // email exists and signup = stop and alert that user already exists

                // Request Notification Information
                const alert = {
                    title: 'Check Email',
                    default_msg: 'an error occured attempting to validate your email.',
                    text: "",
                    type: "warn"
                }

                // Check email request
                const res = await this.$apiHandler.post('auth/check-email/'+this.type, {email: this.form.email}, {}, alert)
                if(res.status != 200){
                    this.enter_password = false

                    // Check the response code to determine a message
                    if([401, 422].includes(res.status)){
                        alert.text = res.status == 401 ? 'An account with the requested email does not exist.' : 'An account with the requested email already exists.';

                        // Create a notification alert
                        this.$notification.add(alert)
                    }
                }

                this.$gloading.stop();
                return;
            }
        }
    },
    async mounted(){
        if(this.$session.get_token()){
            // Push user to Dashboard - Router will authenticate users and send back if unauthenticated
            this.$router.push({path: 'then' in this.$route.query ? this.$route.query.then : "/a/portal/dashboard"})
        }

        let query = JSON.stringify(this.$route.query)
        query = JSON.parse(query)
        // Update Types
        if('type' in query && ['login', 'signup'].includes(query.type)){
            this.type = query.type;
            // Clear Type from Query
            delete query.type
            this.$router.push({query: query})
        }
    },
    methods: {
        async check(){
            this.$gloading.start()
            const alert = {
                title: this.type == 'login' ? "Login" : "Signup",
            }

            // check request
            const res = await this.$apiHandler.post('auth/check/'+this.type, this.form, {}, alert);

            if(res.status == 200){
                if(this.type == 'login'){
                    // save session token and push to dashboard
                    this.$session.set_token(res.data.token);
                    
                    this.$gloading.stop();
                    this.$router.push({path: 'then' in this.$route.query ? this.$route.query.then : "/a/portal/dashboard"});
                    
                }else{
                    setTimeout(() => {
                        location.reload()
                    }, 2500);
                }
            }else{
                this.$gloading.stop()
            }
        }
    },
    components: {
        Icon
    }
}

</script>