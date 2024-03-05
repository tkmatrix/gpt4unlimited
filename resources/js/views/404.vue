<template>
    <div class="w-full h-screen p-4 md:p-0 grid md:grid-cols-2 overflow-hidden relative">
        
        <div class="w-fit h-fit m-auto grid gap-6">
            <p class="font-bold text-[18px] md:text-[22px] text-custom-dark-blue">404 Not Found</p>

            <div class="w-fit h-fit grid gap-4">
                <p class="font-bold text-custom-dark-blue text-[26px] md:text-[42px]">Sorry... The Page Does Not Exist</p>
                <p class="text-custom-gray text-[14px] md:text-[18px]">Looks like the page can't be found. Maybe it was <br> moved or renamed.</p>

                <div class="w-fit h-fit flex items-center gap-4 mt-8">
                    <router-link :to="{name: 'Home'}" class="uppercase py-4 px-8 w-fit border text-[12px] md:text-[14px] font-semibold text-custom-purple border-custom-light-purple border-opacity-50 rounded-[3px] hover:text-custom-orange hover:border-custom-orange">Back to Homepage</router-link>
            
                    <button @click="$router.back" class="uppercase py-4 px-8 w-fit border text-[12px] md:text-[14px] font-semibold text-custom-dark-blue border-custom-dark-blue border-opacity-50 rounded-[3px]">Back</button>
                </div>
            </div>
        </div>

        <img src="../../assets/images/404.svg" alt="404 Not Found" class="">
    </div>
</template>

<script>

export default{
    name: "404 Page Not Found"
}

</script>