<template>
    <div>
        <div ref="modal" class="text-lg bg-black shadow-lg rounded-lg p-8 w-4/5 flex flex-col items-center text-white">

            <span class="material-symbols-rounded text-red-400" style="font-size:80px">
                warning
            </span>

            <div>
                Delete {{ props.universe.universe_name }}?
            </div>

            <div>
                Do you really want to delete this universe? This action cannot be undone.
            </div>

            <div class="flex justify-end gap-16">
                <button @click="close" type="button" class="hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Cancel
                </button>
                <button @click="submit" type="submit" class="bg-red-500 text-white font-bold py-2 px-4 rounded">
                    Delete
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onClickOutside } from '@vueuse/core'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const emit = defineEmits(['closeModal'])
function close() {
    emit('closeModal');
};

const modal = ref(null)

const props = defineProps({
    universe: {
        type: Object,
    },
})

onClickOutside(modal, () => {
    close()
})

function submit() {

    router.delete(route('universes.destroy', props.universe.universe_id), {
        onSuccess: () => {
            console.log('closing...')
            close()
        },
        onError: () => {
            console.log(error);
        }
    })
};
</script>

<style scoped>
.material-symbols-rounded {
    font-variation-settings:
        'FILL' 0,
        'wght' 500,
        'GRAD' 0,
        'opsz' 40;
    /* color: red; */
}
</style>