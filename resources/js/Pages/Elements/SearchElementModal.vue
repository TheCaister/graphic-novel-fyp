<template>
    <div>
        <div ref="modal" class="text-lg bg-gray-800 shadow-lg rounded-lg py-8 px-16 w-4/5">
            <!-- Search bar with X button -->
            <div class="flex gap-4">
                <!-- <input type="text" v-model="searchQuery" placeholder="Search for elements" /> -->
                <TextInput id="series_title" type="text" class="mt-1 block w-full" v-model="searchQuery"
                    placeholder="Search for an element" required autofocus />
                <InputError class="mt-2" message="" />
                <!-- <button @click="close" class="text-3xl text-pink-500">X</button> -->
            </div>
            <!-- List of element results -->
            <div class="flex flex-wrap m-8 justify-center">
                <button v-for="element in elementResults" :key="element.element_id"
                    class="flex flex-col items-center w-64 m-4 p-4 bg-gray-600 shadow-lg rounded-lg hover:bg-gray-700 hover:scale-105 hover:shadow-xl transition duration-300"
                    @click="emits('closeElementSearchModal', element)">
                    <!-- Display element content here -->

                    <img v-if="element.element_thumbnail" :src="element.element_thumbnail" alt="Content Image"
                        class="rounded-full w-16 h-16" />
                    <div v-else
                        class="text-white text-xl flex items-center bg-pink-300 justify-center rounded-full w-16 h-16">
                        </div>

                    <div>
                        <div class="text-white">
                            {{ element.element_name }}
                        </div>
                        <div class="text-gray-400 text-sm">
                            {{ element.derived_element_type.substring(element.derived_element_type.lastIndexOf('\\') +
                    1) }}
                        </div>
                    </div>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onClickOutside } from '@vueuse/core'
import { ref, watch } from 'vue'
import APICalls from '@/Utilities/APICalls';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
const emits = defineEmits(['closeElementSearchModal'])

function close() {
    emits('closeElementSearchModal');
};

const modal = ref(null)
const searchQuery = ref('')
const elementResults = ref([])
const timer = ref(null)



onClickOutside(modal, () => {
    close()
})

watch(searchQuery, () => {
    // Clear the previous timer if it exists
    if (timer.value) {
        clearTimeout(timer.value);
    }

    // Set a new timer to delay the API call
    timer.value = setTimeout(() => {
        // console.log('Making API call...')

        // Call the API
        APICalls.searchElements(searchQuery.value, 8)
            .then(response => {
                // console.log(response.data)
                elementResults.value = response.data
            })
            .catch(error => {
                console.log(error)
            })

        // console.log(elementResults.value)
    }, 200);
})
</script>