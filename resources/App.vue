<template>
    <Notifications />
    <Loading v-if="$gloading.loading" />

    <router-view class="z-[85]" />
</template>

<script>

import Loading from './js/components/loading.vue'
import Notifications from './js/components/notifications/notifications.vue'

export default {
    name: "App",
    data() {
        return {

        }
    },
    components: {
        Loading,
        Notifications
    }
}
</script>

<style>
/**Font from google font */
#app {
    font-family: 'Nunito', sans-serif;
}

body {
    /* background-color: #F5F5F5; */
}

@media (-webkit-device-pixel-ratio: 1.10) {
    :root {
        zoom: 0.90;
    }
}

/* Chrome, Safari, Edge, Opera */
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Firefox */
input[type="number"] {
    -moz-appearance: textfield;
}

input[type="text"]:focus {
    outline: none !important;
    --tw-ring-color: transparent !important;
}
</style>