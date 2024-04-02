<template>
    <div class="flex h-24 items-center relative">
        <button @click="toggleChecked"
            :class="['flex', 'flex-grow', 'items-center', 'border-2', 'p-4', 'rounded-md', 'justify-between', 'border-pink-500', { 'bg-pink-500': checked === true }]">

            <div class="flex items-center">
                <!-- <img src="https://cc-prod.scene7.com/is/image/CCProdAuthor/What-is-Stock-Photography_P1_mobile?$pjpeg$&jpegSize=200&wid=720"
                    alt="" class="rounded-full w-16 h-16">
                
                -->

                <img v-if="props.includeImage" :src="props.image ? props.image : '/assets/black_page.jpg'" alt=""
                    class="rounded-full w-12 h-12 mr-4">
                <div class="text-2xl" :class="{ 'text-black': checked === true }">
                    {{ label }}
                </div>
            </div>

            <span v-if="checked === true" class="material-symbols-outlined dark">
                check_circle
            </span>

            <span v-else-if="checked === false" class="material-symbols-outlined white">
                remove
            </span>
        </button>

        <Transition name="fade">
            <div class="absolute top-2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-green-400 text-black px-3 py-1 rounded-full text-sm"
                v-if="alreadyAttached === true">
                Already Attached
            </div>
        </Transition>
    </div>
</template>

<script setup>
const checked = defineModel('checked')
const alreadyAttached = defineModel('alreadyAttached')

const props = defineProps({
    image: {
        type: String,
        default: null
    },
    label: {
        type: String,
        required: true
    },
    includeImage:{
        type: Boolean,
        default: true
    }
})

function toggleChecked() {

    if (checked.value === null) {
        checked.value = true
    } else if (checked.value === true) {
        checked.value = false
    } else if (checked.value === false) {
        checked.value = null
    }
}

</script>


<style scoped>
.material-symbols-outlined.dark {
    color: black;
    /* set font size */
    font-size: 2.5rem;
}

.material-symbols-outlined.white {
    color: white;
    /* set font size */
    font-size: 2.5rem;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity .2s;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}
</style>