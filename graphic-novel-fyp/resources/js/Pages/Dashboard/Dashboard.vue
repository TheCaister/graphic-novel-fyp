<script setup>
import { Head } from '@inertiajs/vue3';
import UniverseView from './Universe/UniverseView.vue';
import { ref, defineProps } from 'vue'


const props = defineProps({
    contentType: {
        type: String,
    },
    contentId: {
        type: Number,
    },
});

const contentList = ref([])

function updateContentList() {
    console.log('updateContentList')
    console.log(contentList)
}
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex justify-between mb-8">

        <div>
            <PrimaryButton>
                Go back
            </PrimaryButton>

            <PrimaryButton>
                Universe 1
            </PrimaryButton>
        </div>

        <div>
            <span class="material-symbols-outlined dark">
                list
            </span>

            <span class="material-symbols-outlined dark">
                grid_view
            </span>

            <PrimaryButton>
                Dropdown for view type
            </PrimaryButton>
        </div>
    </div>

    <div class="flex flex-wrap relative">

        <!-- Make dynamic -->
        <KeepAlive>
            <component :is="contentType" :contentId="contentId" />
        </KeepAlive>

        <UniverseView />

    </div>
</template>


```
