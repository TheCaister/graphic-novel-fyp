<template>
    <div>
        <div ref="modal" class="text-lg bg-gray-800 shadow-lg rounded-lg p-8 w-4/5">
            <!-- Search bar with X button -->
            <div>
                <input type="text" v-model="searchQuery" placeholder="Search for elements" />
                <button @click="close" class="text-3xl text-pink-500">X</button>
            </div>
            <!-- List of element results -->
            <div class="flex flex-wrap m-8 justify-center">
                <button v-for="element in elementResults" :key="element.element_id" class="flex flex-col items-center w-32 m-4 p-4 bg-gray-600 shadow-lg rounded-lg hover:bg-gray-700 hover:scale-105 hover:shadow-xl transition duration-300" @click="emits('closeElementSearchModal', element)">
                    <!-- Display element content here -->
                    <!-- image -->
                    <img src="https://cc-prod.scene7.com/is/image/CCProdAuthor/What-is-Stock-Photography_P1_mobile?$pjpeg$&jpegSize=200&wid=720"
                        alt="" class="rounded-full w-16 h-16">

                    <div>
                        <div class="text-white">
                            {{ element.element_name }}
                        </div>
                        <div class="text-gray-400 text-sm">
                            {{ element.derived_element_type.substring(element.derived_element_type.lastIndexOf('\\') + 1) }}
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