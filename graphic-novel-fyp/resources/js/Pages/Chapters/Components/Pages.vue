<template>
    <!-- Show details about the page -->
    <div class="bg-white shadow-md rounded-md p-4">
        <p class="text-gray-700 text-base mb-4">Page {{ page.page_number }}</p>

        <!-- Show the page image, stretching it to the height of the window -->
        <img :src="`https://pm1.aminoapps.com/7375/5c45e9a5626c875433b0fff3a30c7b7aaa354a8cr1-1286-1037v2_uhq.jpg`" alt="" class="h-full">
    </div>

</template>

<script>
    export default {
        props: {
            page: Object,
        }
    }
</script>