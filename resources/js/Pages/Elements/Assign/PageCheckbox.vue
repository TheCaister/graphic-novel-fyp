<template>
    <div >
        <button @click="toggleChecked"
            :class="['w-full',
            'min-h-64', 'border-2', 'p-2', 'relative', 'rounded-md', 'border-pink-500', { 'bg-pink-500': checked === true }]">

            <div class="flex flex-col gap-2 justify-center">

                <img :src="content.content_thumbnail ? content.content_thumbnail : '/assets/black_pixel.png'" alt=""
                    class="rounded-md h-64">

                <div>
                    {{ content.content_name }}
                </div>

            </div>
        </button>

    </div>
</template>

<script setup>
import { defineProps, ref, defineEmits } from 'vue'

const checked = defineModel()

const props = defineProps({
    content:
    {
        type: Object,
        required: true
    }
})

function toggleChecked() {
    checked.value = !checked.value
    console.log(props.content)
}

</script>

<style scoped>
.material-symbols-outlined.dark {
    color: rgb(176, 43, 43);
    /* set font size */
    font-size: 2.5rem;
}

.material-symbols-outlined.white {
    color: white;
    /* set font size */
    font-size: 2.5rem;
}
</style>