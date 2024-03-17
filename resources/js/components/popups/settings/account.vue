<template>
    <div class="w-full h-fit grid gap-4 pb-12">
        <!-- account name -->
        <div class="w-full h-fit grid gap-2 pb-4 border-b-[1px] border-custom-black/10">
            <p>Account Name</p>
            
            <div class="w-full h-fit grid items-center relative">
                <div class="w-fit h-fit flex items-center gap-2">
                    <!-- profile picture -->
                    <div class="w-[52px] h-[52px] min-w-[52px] min-h-[52px] grid overflow-hidden rounded-full border-[1px] border-custom-black/10 relative group">
                        <!-- hover upload image -->
                        <button @click="$refs.pfpUpload.click()" class="w-full h-full grid top-0 left-0 absolute bg-custom-gray/30 z-[5] invisible group-hover:visible">
                            <Icon icon="bi:camera-fill" height="26px" class="text-white m-auto" />
                        </button>
                        <!-- upload button -->
                        <input @change="pfpUpload($event)" type="file" accept=".pgn,.jpg,.jpeg" ref="pfpUpload" class="hidden">

                        <!-- pfp -->
                        <profile_picture :link="session.profile_picture" icon_height="32px" :height="'52px'" />
                    </div>

                    <!-- name -->
                    <p>{{ session.name }}</p>
                </div>

                <!-- logout button -->
                <button @click="logout" class="right-0 absolute px-4 py-[4px] border-[1px] border-custom-black/10 rounded-[4px] hover:bg-custom-hover-gray">Log Out</button>
            </div>
        </div>
        
        <!-- email -->
        <div class="w-full h-fit grid gap-2 pb-4 border-b-[1px] border-custom-black/10">
            <p>Email</p>
            
            <!-- new email form -->
            <form v-if="update_email" @submit.prevent="updateEmail" class="w-fit flex items-center gap-6">
                <!-- new email -->
                <input v-model="new_email" type="email" class="!h-[36px] w-full md:w-[300px]">

                <div class="w-fit h-fit flex items-center gap-2">
                    <!-- close button -->
                    <Icon type="button" @click="update_email = false; new_email = '';" icon="material-symbols-light:close" height="32px" class="text-custom-red cursor-pointer" />

                    <Icon @click="$refs.update_email_submit.click()" icon="material-symbols-light:check" height="32px" class="text-custom-green cursor-pointer" />
                    <input ref="update_email_submit" type="submit" class="hidden">
                </div>
            </form>


            <div v-else class="w-full h-fit grid items-center relative">
                <!-- email -->
                <p>{{ session.email }}</p>

                <!-- update email button -->
                <button @click="new_email = session.email; update_email = true" class="right-0 absolute px-4 py-[4px] border-[1px] border-custom-black/10 rounded-[4px] hover:bg-custom-hover-gray">Update Email</button>
            </div>
        </div>
        
        <!-- password -->
        <div class="w-full h-fit grid gap-2 pb-4 border-b-[1px] border-custom-black/10">
            <p>Password</p>
            
            <!-- new password form -->
            <form v-if="update_password" @submit.prevent="updatePassword" class="w-fit flex items-center gap-6">
                <div class="w-full h-fit grid items-center relative">
                    <!-- password email -->
                    <input v-model="new_password" :type="show_password ? 'text' : 'password'" class="!h-[36px] w-full md:w-[300px] pr-[48px]">
                    
                    <Icon @click="show_password = !show_password" :icon="show_password ? 'quill:eye' : 'quill:eye-closed'" height="24px" class="right-2 absolute text-custom-black cursor-pointer absolute" />
                </div>

                <div class="w-fit h-fit flex items-center gap-2">
                    <!-- close button -->
                    <Icon type="button" @click="update_password = false; new_password = '';" icon="material-symbols-light:close" height="32px" class="text-custom-red cursor-pointer" />

                    <Icon @click="$refs.update_password_submit.click()" icon="material-symbols-light:check" height="32px" class="text-custom-green cursor-pointer" />
                    <input ref="update_password_submit" type="submit" class="hidden">
                </div>
            </form>

            <!-- update password button -->
            <button v-else @click="update_password = true" class="w-fit px-4 py-[4px] border-[1px] border-custom-black/10 rounded-[4px] hover:bg-custom-hover-gray">Update Password</button>
        </div>
        
        <!-- google account -->
        <div class="w-full h-fit grid gap-2 pb-4">
            <p class="text-custom-black/70">Select sign in with google at the login screen to connect your google account.</p>

            <div type="button" :class="session.google_connected ? 'text-custom-green border-custom-green' : 'text-custom-orange border-custom-orange'" class="w-fit px-4 h-[34px] grid items-center px-4 border-[1px] rounded-[5px] md:select-none">
                <div class="w-fit h-fit flex items-center gap-4 truncate">
                    <img src="../../../../assets/images/google.png" alt="Google" style="height: 18px;">
                    <p class="whitespace-nowrap truncate">{{ session.google_connected ? 'Connected' : 'Not Connected' }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Icon } from '@iconify/vue';
import profile_picture from '../../profile_picture.vue';

export default {
    name: "Account Settings",
    data() {
        return {
            session: this.$session.get(),

            update_email: false,
            new_email: "",
            
            update_password: false,
            show_password: false,
            new_password: "",

        }
    },
    async mounted (){

    },
    methods: {
        async updateSession(){
            await this.$session.valid();
            this.session = await this.$session.get();
            this.$emit('updateSession');
            
        },
        async logout(){
            this.$gloading.start();

            const res = await this.$apiHandler.get('auth/logout/all')
            this.$session.clear();

            this.$gloading.stop();
            this.$router.push({name: "Login"})
        },
        async pfpUpload(e){
            this.$gloading.start();

            const file = e.target.files[0];
            if(file){
                // Account Profile Picture
                const pfp_alert = {
                    title: "Account Profile Picture"
                }

                const upload_pfp = await this.$apiHandler.form('account/picture', {profile_picture: file}, {}, pfp_alert);
            }
            
            // reload session information
            await this.updateSession();
            this.$gloading.stop();
        },
        async updateEmail(){
            this.$gloading.start();

            if(!this.new_email){
                this.$notification.add({
                    title: "Validation Error",
                    text: "an email address is required.",
                    type: "warn"
                });

                this.$gloading.stop();
                return;
            }

            const email_alert = {
                title: "Update Email"
            };

            const res = await this.$apiHandler.post('account/update-email', {email: this.new_email}, {}, email_alert);

            if(res.status == 200){
                // update session information
                await this.updateSession();
                // clear new email and set update email to false
                this.update_email = false;
                this.new_email = "";
            }

            this.$gloading.stop();
        },
        async updatePassword(){
            this.$gloading.start();

            if(!this.new_password){
                this.$notification.add({
                    title: "Validation Error",
                    text: "an password is required.",
                    type: "warn"
                });

                this.$gloading.stop();
                return;
            }

            const password_alert = {
                title: "Update Password"
            };

            const res = await this.$apiHandler.post('account/update-password', {password: this.new_password}, {}, password_alert);

            if(res.status == 200){
                // clear new email and set update email to false
                this.update_password = false;
                this.new_password = "";
            }

            this.$gloading.stop();
        }
    },
    components: {
        Icon,
        profile_picture
    }
}

</script>