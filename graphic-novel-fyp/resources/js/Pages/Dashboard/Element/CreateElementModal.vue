<script setup>
import { onClickOutside } from '@vueuse/core'
import { ref } from 'vue'

const emit = defineEmits(['closeModal'])
function close() {
    emit('closeModal');
};

const modal = ref(null)

onClickOutside(modal, () => {
    close()
})

</script>

<template>
    <div>
        <div ref="modal" class="text-lg bg-black shadow-lg rounded-lg p-8 w-4/5">

            <div class="flex flex-col gap-10">
                <h2 class="text-4xl font-bold text-white ">Select type of element to create:</h2>
                <Link class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Simple Text
                </Link>
                <Link class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Mind Map
                </Link>
                <Link class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Panel Planner
                </Link>
            </div>
        </div>
    </div>
</template>