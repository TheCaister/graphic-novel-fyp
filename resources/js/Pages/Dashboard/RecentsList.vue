<template>
    <div class="p-4 text-white flex flex-col items-center">
        <div class="mb-4 text-gray-400">Recents</div>

        <!-- Loop through resultsList. Each result has title, thumbnail and link -->
        <div class="flex flex-col space-y-4 w-full">
            <Link v-for="result in recentsList" :href="result.link" :key="result.id"
                class="shadow-lg rounded-lg flex items-center hover:scale-105 transition-all duration-200">
                <!-- Your existing code here -->
                <img v-if="result.thumbnail" class="w-16 h-16 rounded-lg mr-2" :src="result.thumbnail" :alt="result.title" />
                <!-- Display the first letter of the title -->
                <div v-else class="w-16 h-16 rounded-lg mr-2 flex items-center justify-center text-xl bg-pink-300">
                    {{ result.title.charAt(0) }}
                </div>
                <div>
                    {{ result.title }}
                </div>

            </Link>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import APICalls from '@/Utilities/APICalls';

const recentsListLimit = 10

const recentsList = ref([])

onMounted(async () => {
    updateRecentsList()
})

function updateRecentsList() {
    APICalls.getRecents(recentsListLimit).then(response => {
        // console.log(response.data)
        recentsList.value = response.data
    }).catch(error => {
        console.log(error)
    })
}
</script>