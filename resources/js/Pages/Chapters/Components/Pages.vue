<template>
    <!-- Show details about the page -->
    <div class="bg-white shadow-md rounded-md p-4">
        <p class="text-gray-700 text-base mb-4">Page {{ page.page_number }}</p>

        <!-- Place two transparent overlays on top of the image. If the user clicks on the left half of the image, emit "previousPage". Else, if the user clicks on the right half of the page, emit "nextPage" -->
        <div class="h-full relative">
            <div class="absolute top-0 left-0 w-1/2 h-full" @click="$emit('previousPage')"></div>
            <div class="absolute top-0 right-0 w-1/2 h-full" @click="$emit('nextPage')"></div>
            
            <img :src="`${page.page_image}`" alt="Page" @error="showDefaultPage" class="h-full">

         
        </div>
    </div>
</template>

<script>
export default {
    props: {
        page: Object,
    },
    methods: {
        // Show a black page if the image doesn't load
        showDefaultPage(event) {
            event.target.src = 'https://media.istockphoto.com/id/1142986365/vector/error-with-glitch-effect-on-screen-error-404-page-not-found-flat-design-modern-vector.jpg?s=612x612&w=0&k=20&c=E0Y1GZsaRrEk-a2eW49WKCjRFd9SS1MitZ6Vly1HAs8=';
        },
    },
}
</script>