<template>
    <div class="flex h-24 items-center">
        <button @click="toggleChecked" class="flex items-center border border-pink-500 rounded-md justify-between">

            <div class="flex items-center">
                <!-- <img src="https://cc-prod.scene7.com/is/image/CCProdAuthor/What-is-Stock-Photography_P1_mobile?$pjpeg$&jpegSize=200&wid=720"
                    alt="" class="rounded-full w-16 h-16">
                
                -->

                <img :src="props.image ? props.image : '/assets/black_page.jpg'"
                    alt="" class="rounded-full w-16 h-16">
                <div>
                    {{ label }}
                </div>
            </div>

            <span v-if="checked === true" class="material-symbols-outlined dark">
                check_circle
            </span>

            <span v-else-if="checked === false" class="material-symbols-outlined dark">
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