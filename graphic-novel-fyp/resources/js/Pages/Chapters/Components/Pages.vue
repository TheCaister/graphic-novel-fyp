<template>
    <!-- Show details about the page -->
    <div class="bg-white shadow-md rounded-md p-4">
        <p class="text-gray-700 text-base mb-4">Page {{ page.page_number }}</p>

        <!-- Place two transparent overlays on top of the image. If the user clicks on the left half of the image, emit "previousPage". Else, if the user clicks on the right half of the page, emit "nextPage" -->
        <div class="h-full relative">
            <div class="absolute top-0 left-0 w-1/2 h-full" @click="$emit('previousPage')"></div>
            <div class="absolute top-0 right-0 w-1/2 h-full" @click="$emit('nextPage')"></div>
            <img :src="`https://pm1.aminoapps.com/7375/5c45e9a5626c875433b0fff3a30c7b7aaa354a8cr1-1286-1037v2_uhq.jpg`"
                alt="" class="h-full">
        </div>
    </div>
</template>

<script>
export default {
    props: {
        page: Object,
    }
}
</script>