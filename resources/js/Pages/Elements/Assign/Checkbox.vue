<template>
    <div class="flex h-24 items-center">
        <button @click="toggleChecked"  :class="['flex', 'flex-grow', 'items-center', 'border-2', 'p-4', 'rounded-md', 'justify-between', 'border-pink-500', {'bg-pink-500': checked === true}]">

            <div class="flex items-center">

                <img :src="content.content_thumbnail ? content.content_thumbnail : '/assets/black_pixel.png'"
                    alt="" class="rounded-full w-12 h-12 mr-4">
                <!-- label -->
                <div>
                    {{ content.content_name }}
                </div>
            </div>

            <span v-if="checked === true" class="material-symbols-outlined dark">
                check_circle
            </span>
        </button>
        
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { defineProps, ref, defineEmits } from 'vue'

const checked = defineModel()

// const emits = defineEmits(['checked'])

const props = defineProps({
    content:
    {
        type: Object,
        required: true
    }
})

// const checked = ref(false)

function toggleChecked() {
    // console.log(props.content)
    checked.value = !checked.value
    // emits('checked', checked.value)
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