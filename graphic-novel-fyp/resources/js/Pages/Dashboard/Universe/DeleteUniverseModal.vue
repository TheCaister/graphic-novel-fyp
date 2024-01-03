<template>
    <div>
    <div ref="modal" class="text-lg bg-black shadow-lg rounded-lg p-8 w-4/5">
        <h2 class="text-4xl font-bold text-white ">Are you sure you want to delete this universe: {{
            props.universe.universe_name }}</h2>
            <div class="flex">
                <div class="flex flex-col justify-between w-1/2 ml-8">
                    <div class="flex justify-end">
                        <!-- Button to cancel and button to create -->
                        <button @click="close" type="button"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Cancel
                        </button>
                        <PrimaryButton @click="submit" type="submit"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Delete
                        </PrimaryButton>
                    </div>
                </div>

            </div>
    </div>
    </div>
</template>

<script setup>
import { onClickOutside } from '@vueuse/core'
import { ref, defineProps } from 'vue'
import axios from 'axios';

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
    axios.delete(route('universes.destroy', props.universe.universe_id)).then((response) => {
        close()
        console.log(response);
    }, (error) => {
        console.log(error);
    });
};
</script>