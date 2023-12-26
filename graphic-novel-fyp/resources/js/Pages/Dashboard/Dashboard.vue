<script setup>
import { Head } from '@inertiajs/vue3';
import UniverseView from './Universe/UniverseView.vue';
import SeriesView from './Series/SeriesView.vue';
import ChapterView from './Chapter/ChapterView.vue';
import PageView from './Page/PageView.vue';
import { ref, defineProps, onMounted, computed } from 'vue'


const props = defineProps({
    dashboardViewType: {
        type: String,
    },
    parentContentId: {
        type: Number,
        default: 0,
    },
});

const dashboardView = ref(props.dashboardViewType)
const parentContentIdNumber = ref(props.parentContentId)

const DashboardViewComponent = computed(() => {
    // Switch statement to return the correct dashboard view
    switch (dashboardView.value) {
        case 'UniverseView':
            return UniverseView
        case 'SeriesView':
            return SeriesView
        case 'ChapterView':
            return ChapterView
        case 'PageView':
            return PageView
        default:
            return UniverseView
    }
})

onMounted(async () => {
    console.log(props.dashboardViewType)
})

function updateDashboard(dashboardViewString, parentContentId) {
   
    parentContentIdNumber.value = parentContentId
    dashboardView.value = dashboardViewString

    console.log("Parent:" + parentContentIdNumber.value)
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
            <component :is="DashboardViewComponent" :parentContentId="parentContentIdNumber" @updateDashboard="updateDashboard" />
        </KeepAlive>

    </div>
</template>


```
