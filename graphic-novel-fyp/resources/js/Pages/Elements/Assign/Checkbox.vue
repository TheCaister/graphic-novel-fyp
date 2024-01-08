<template>
    <div class="flex h-24 items-center">
        <button @click="toggleChecked" class="flex items-center border border-pink-500 rounded-md justify-between">

            <div class="flex items-center">
                <!-- image -->
                <img src="https://cc-prod.scene7.com/is/image/CCProdAuthor/What-is-Stock-Photography_P1_mobile?$pjpeg$&jpegSize=200&wid=720"
                    alt="" class="rounded-full w-16 h-16">
                <!-- label -->
                <div>
                    {{ label }}
                </div>
            </div>

            <span v-if="checked" class="material-symbols-outlined dark">
                check_circle
            </span>
        </button>
        <button>
            >>
        </button>
    </div>
</template>

<script setup>
import { defineProps, ref, defineEmits } from 'vue'

const emits = defineEmits(['checked'])

const props = defineProps({
    image: {
        type: String,
    },
    label: {
        type: String,
        required: true
    }
})

const checked = ref(false)

function toggleChecked() {
    checked.value = !checked.value
    emits('checked', checked.value)
}

</script>