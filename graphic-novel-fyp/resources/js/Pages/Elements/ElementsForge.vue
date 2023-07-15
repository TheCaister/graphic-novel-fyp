<template>
    <!-- Display awesome title: Welcome to the Element Forge... with this as the background image: https://i.pinimg.com/originals/f0/05/28/f00528a9404e2959c3c5e5e440f1761a.png -->
    <div class="bg-no-repeat bg-cover bg-center h-screen flex flex-col justify-center items-center"
        style="background-image: url('https://i.pinimg.com/originals/f0/05/28/f00528a9404e2959c3c5e5e440f1761a.png');">
        <div class="bg-white shadow-lg rounded-lg p-10 flex flex-col gap-5">
            <div>
                <p class="font-bold text-2xl md:text-3xL">Welcome to the Element Forge</p>
            </div>
        </div>
    </div>

    <div class="border-2 border-black">
        <SimpleEditor/>
    </div>
    
</template>

<script>
import SimpleEditor from '../../Components/Editors/SimpleEditor.vue'

export default {
    components: {
        SimpleEditor
    }
}
</script>