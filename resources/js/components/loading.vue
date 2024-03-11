<template>
    <div class="w-screen h-screen grid bg-custom-gray/5 z-[95] absolute top-0 right-0">
        <div class="w-fit h-fit p-4 bg-white m-auto rounded-[10px] shadow-newdrop">
            <Icon icon="eos-icons:loading" height="52px" class="m-auto text-custom-dark-blue1" />
        </div>
    </div>
</template>

<script>
import { Icon } from '@iconify/vue';

export default {
    name: "Loading",
    components: {
        Icon
    }
}

</script>