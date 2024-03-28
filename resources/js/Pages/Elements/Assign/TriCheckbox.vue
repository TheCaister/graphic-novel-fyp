<template>
    <div class="flex h-24 items-center">
        <button @click="toggleChecked" :class="['flex', 'flex-grow', 'items-center', 'border-2', 'p-4', 'rounded-md', 'justify-between', 'border-pink-500', {'bg-pink-500': checked === true}]">

            <div class="flex items-center">
                <!-- <img src="https://cc-prod.scene7.com/is/image/CCProdAuthor/What-is-Stock-Photography_P1_mobile?$pjpeg$&jpegSize=200&wid=720"
                    alt="" class="rounded-full w-16 h-16">
                
                -->

                <img :src="props.image ? props.image : '/assets/black_page.jpg'"
                    alt="" class="rounded-full w-12 h-12 mr-4">
                <div class="text-2xl" :class="{'text-black': checked === true}">
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
    </div>
</template>

<script setup>
const checked = defineModel()

const props = defineProps({
    image: {
        type: String,
    },
    label: {
        type: String,
        required: true
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

</style>