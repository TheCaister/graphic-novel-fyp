<script setup>
import { Head, router } from '@inertiajs/vue3';
import UniverseView from './Universe/UniverseView.vue';
import SeriesView from './Series/SeriesView.vue';
import ChapterView from './Chapter/ChapterView.vue';
import PageView from './Page/PageView.vue';
import { ref, defineProps, onMounted, computed } from 'vue'
import APICalls from '@/Utilities/APICalls';


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

function goBack() {

    // Say we're on a chapter view. We want to get back to the series view. On the chapter view, we have the series type and id. We'll use this to get the series view.
    // APICalls.getParentContent(dashboardView.value, parentContentIdNumber.value).then(response => {
    //     // let parentContent = ''

    //     // switch (response.data.view) {
    //     //     case 'UniverseView':
    //     //         router.visit('/')
    //     //     case 'SeriesView':
    //     //         parentContent = 'universes'
    //     //         break;
    //     //     case 'ChapterView':
    //     //         parentContent = 'series'
    //     //         break;
    //     //     case 'PageView':
    //     //         parentContent = 'chapters'
    //     //         break;
    //     //     default:
    //     //         router.visit('/')

    //     // }

    //     // console.log(response.data)

    //     // console.log(response.data)
    //     // router.visit(route(parentContent + '.show', response.data.parentid))
    //     // updateDashboard(response.data.view, response.data.parentid)
    // }).catch(error => console.log(error))
    console.log("Parent:" + parentContentIdNumber.value)
    // APICalls.getParentContent(dashboardView.value, parentContentIdNumber.value)
    router.visit(route('content.get-parent', { type: dashboardView.value, id: parentContentIdNumber.value }))
    // We need to update dashboardView and parentContentIdNumber. This is by passing in the current parentContentIdNumber and dashboardView value.
}
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex justify-between mb-8">

        <div>
            <div v-if="dashboardView != 'UniverseView'">
                <PrimaryButton @click="goBack()">
                    Go back
                </PrimaryButton>
            </div>
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
            <component :is="DashboardViewComponent" :parentContentId="parentContentIdNumber"
                @updateDashboard="updateDashboard" />
        </KeepAlive>

    </div>
</template>


```
