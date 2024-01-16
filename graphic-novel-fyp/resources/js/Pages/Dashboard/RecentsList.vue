<template>
    <div class="border-red-500 border p-4 text-white flex flex-col items-center">
        <div class="mb-4">Recents</div>

        <!-- Loop through resultsList. Each result has title, thumbnail and link -->
        <div class="flex flex-col space-y-4 w-full">
            <Link  v-for="result in recentsList" :href="result.link" :key="result.id" class="shadow-lg ring-2 ring-blue-500 flex items-center">
                <img class="w-16 rounded-lg mr-2" :src="result.thumbnail" :alt="result.title" />
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
    console.log(props.recentsList)

    updateRecentsList()
})

function updateRecentsList(){
    APICalls.getRecents(recentsListLimit).then(response => {
        console.log(response.data)
        recentsList.value = response.data
    }).catch(error => {
        console.log(error)
    })
}
</script>