<template>
    <div class="w-full h-screen grid absolute bg-custom-gray/20 z-[85]">
        <!-- Settings -->
        <div class="w-[90%] md:w-[40%] h-fit m-auto grid bg-white rounded-[15px] shadow-newdrop">
            <!-- Header -->
            <div class="w-full p-6 grid items-center relative">
                <p class="text-[20px] text-custom-dark-blue1 font-semibold select-none">Settings</p>

                <!-- close button -->
                <Icon @click="$emit('close')" icon="material-symbols-light:close" height="36px" class="right-6 absolute text-custom-gray cursor-pointer" />
            </div>
            <hr>

            <!-- Settings Menu -->
            <div class="w-full p-4 grid md:flex gap-4 relative">
                <!-- left side menu -->
                <div class="w-full md:w-[180px] min-w-[180px] h-fit grid gap-1 mb-12 md:mb-0">
                    <button v-for="(item, index) in settings" @click="type = item.code" :class="type == item.code ? 'bg-custom-hover-gray' : ''" class="w-full h-fit flex items-center gap-2 px-2 py-[6px] rounded-[6px] truncate">
                        <div class="h-[18px] w-[18px] min-h-[18px] min-w-[18px]">
                            <Icon :icon="item.icon" height="18px" class="text-custom-black" />
                        </div>
                        <p class="select-none truncate">{{ item.name }}</p>
                    </button>
                </div>

                <!-- settings component -->
                <component :is="(type)" @updateSession="$emit('updateSession')" />
            </div>
        </div>
    </div>
</template>

<script>

import { Icon } from '@iconify/vue';
// settings
import account from './settings/account.vue'
import usage from './settings/usage.vue'

export default {
    name: "Popups - Settings",
    data(){
        return {
            type: "account",
            settings: [
                {
                    code: 'account',
                    name: 'Account',
                    icon: 'solar:user-linear'
                },
                {
                    code: 'usage',
                    name: 'Billing / Usage',
                    icon: 'mingcute:chart-bar-line'
                }
            ]
        }
    },
    components: {
        Icon,
        // settings
        account,
        usage
    }
}

</script>