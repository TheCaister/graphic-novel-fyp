<template>
    <div>
        <div ref="modal" class="text-lg bg-black shadow-lg rounded-lg p-8 w-4/5">
            <!-- Search bar with X button -->
            <div>
                <input type="text" v-model="searchQuery" placeholder="Search for elements" />
                <button @click="close">X</button>
            </div>
            <!-- List of element results -->
            <div class="flex">
                <div v-for="element in elementResults" :key="element.element_id">
                    <!-- Display element content here -->
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onClickOutside } from '@vueuse/core'
import { ref, watch } from 'vue'
const emits = defineEmits(['closeElementSearchModal'])

function close() {
    emits('closeElementSearchModal');
};

const modal = ref(null)
const searchQuery = ref('')
const elementResults = ref([])
const timer = ref(null)

elementResults.value = [
    {
        element_id: 1,
        content: {
            content: [
                {
                    content: [
                        {
                            text: 'This is a test element'
                        }
                    ]
                }
            ]
        }
    },
    {
        element_id: 2,
        content: {
            content: [
                {
                    content: [
                        {
                            text: 'This is another test element'
                        }
                    ]
                }
            ]
        }
    }
]

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
        // this.makeApiCall();
        console.log('Making API call...')
    }, 200); // Adjust the delay time (in milliseconds) as needed
})
</script>