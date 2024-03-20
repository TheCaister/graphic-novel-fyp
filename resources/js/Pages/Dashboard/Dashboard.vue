<script setup>
import { Head, router } from '@inertiajs/vue3';
import UniverseView from './Universe/UniverseView.vue';
import SeriesView from './Series/SeriesView.vue';
import ChapterView from './Chapter/ChapterView.vue';
import PageView from './Page/PageView.vue';
import ElementView from './Element/ElementView.vue';
import RecentsList from './RecentsList.vue';
import TabsWrapper from './TabsWrapper.vue';
import Tab from './Tab.vue';
import { ref, defineProps, onMounted, computed, provide } from 'vue'
import APICalls from '@/Utilities/APICalls'
import DropdownSubtle from '@/Components/DropdownSubtle.vue';

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
const siblingContentList = ref([])
const size = ref(0)

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

provide('dashboardView', dashboardView.value)
provide('parentContentId', parentContentIdNumber.value)

onMounted(async () => {
    updateSiblingContentDropdown()
})

function updateDashboard(dashboardViewString, parentContentId) {
    updateSiblingContentDropdown()

    parentContentIdNumber.value = parentContentId
    dashboardView.value = dashboardViewString
}

function updateSiblingContentDropdown() {

    let type = null

    switch (dashboardView.value) {
        case 'UniverseView':
            return
        case 'SeriesView':
            type = 'universe'
            break
        case 'ChapterView':
            type = 'series'
            break
        case 'PageView':
            type = 'chapter'
            break
        default:
            return
    }

    if (dashboardView.value !== 'UniverseView') {
        console.log('getting sibling...')
        APICalls.getSiblingContent(type, props.parentContentId).then(response => {
            siblingContentList.value = response.data
            // console.log(response)
        }).catch(error => console.log(error))
    }

}



function goBack() {
    router.visit(route('content.get-parent', { type: dashboardView.value, id: parentContentIdNumber.value }))
}

function goToContent(option){

    let id = option.id

    switch(props.dashboardViewType){
        case 'SeriesView':
            router.visit(route('universes.show', id))
            break
        case 'ChapterView':
            router.visit(route('series.show', id))
            break
        case 'PageView':
            router.visit(route('chapters.show', id))
            break
        default:
            return
    }
}

function updateSize(event) {
    console.log('successfully updated element list size')
    size.value = event
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


            <!-- Dropdown for anything other than universe view -->
            <div v-if="dashboardView !== 'UniverseView'">
                <!-- We have the view type as well as the parent content id -->
                <!-- This means if we're in a series view, we have the universe type and id -->
                <!-- In this case, we'll just display all the universes that the user has access to -->
                <!-- If we're in chapter view, we have the series' details. -->
                <!-- From here, we need to get its 'siblings' by going up another level (to universes) and calling series() -->

                <!-- similar case for page view, except with chapters -->

                <!-- Now, if we're in a series view, we want to display dropdown of other universes -->
                <!-- Chapter view, other series -->
                <!-- Page view,  other chapters -->
                <!-- <PrimaryButton>
                    Universe 1
                </PrimaryButton> -->

                <DropdownSubtle :options="siblingContentList" @option-selected="goToContent"/>
            </div>
        </div>

        <!-- <div>
            <span class="material-symbols-outlined dark">
                list
            </span>

            <span class="material-symbols-outlined dark">
                grid_view
            </span>

            <PrimaryButton>
                Dropdown for view type
            </PrimaryButton>
        </div> -->
    </div>

    <div class="flex">

        <TabsWrapper class="text-4xl font-bold text-white flex flex-wrap w-4/5 " :size="size">
            <Tab title="Content">
                <KeepAlive>



                    <component :is="DashboardViewComponent" :parentContentId="parentContentIdNumber" />

                </KeepAlive>

                <!-- <component :is="DashboardViewComponent" :parentContentId="parentContentIdNumber"
                            @updateDashboard="updateDashboard" /> -->
            </Tab>

            <Tab v-if="dashboardView != 'UniverseView'" title="Elements" listSize="-1" @updateSize="updateSize"
                v-slot="{ update }">
                <KeepAlive>
                    <ElementView @updateSize="update" />
                </KeepAlive>
            </Tab>

        </TabsWrapper>

        <RecentsList class="w-1/5">

        </RecentsList>


    </div>
</template>


```
