<template>
    <div :class="cust_classes" class="grid rounded-full overflow-hidden relative">
        <!-- profile picture -->
        <img v-if="link" :src="link" alt="Account Profile Picture" :class="cust_classes" class="m-auto object-cover select-none">
        <!-- no profile picture -->
        <div v-else :class="cust_classes" class="grid bg-custom-dark-blue2 m-auto">
            <Icon icon="solar:user-linear" :height="icon_height" :class="add_class" class="m-auto text-white" />
        </div>
    </div>
</template>

<script>
import { Icon } from '@iconify/vue';

export default {
    name: "User Profile Picture",
    props: {
        link: {
            type: String,
            default: null
        },
        height: {
            type: String,
            default: "32px"
        },
        icon_height: {
            type: String,
            default: "22px"
        },
        add_class: {
            type: Array,
            default: []
        }
    },
    data(){
        return{
            cust_classes: []
        }
    },
    async mounted(){
        this.cust_classes = [
            ...(this.add_class),
            `min-w-[${this.height}] min-h-[${this.height}] w-[${this.height}] h-[${this.height}]`
        ]
    },
    components: {
        Icon
    }
}

</script>