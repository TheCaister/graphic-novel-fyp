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
const size = ref(0)

const recentsList = ref([
    {
        title: 'Universe 1',
        thumbnail: 'https://via.placeholder.com/150',
        link: '#',
    },
    {
        title: 'Element 1',
        thumbnail: 'https://via.placeholder.com/150',
        link: '#',
    },
    {
        title: 'Series 1',
        thumbnail: 'https://via.placeholder.com/150',
        link: '#',
    }
])

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
    console.log(props.dashboardViewType)
})

function updateDashboard(dashboardViewString, parentContentId) {

    parentContentIdNumber.value = parentContentId
    dashboardView.value = dashboardViewString
}

function goBack() {
    router.visit(route('content.get-parent', { type: dashboardView.value, id: parentContentIdNumber.value }))
}

function updateSize(event){
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

    <div class="flex">

        <TabsWrapper class="text-4xl font-bold text-white flex flex-wrap w-4/5 " :size="size">
            <Tab title="Content">
                <KeepAlive>
                    <component :is="DashboardViewComponent" :parentContentId="parentContentIdNumber"
                        @updateDashboard="updateDashboard" />
                </KeepAlive>
            </Tab>

            <Tab v-if="dashboardView != 'UniverseView'" title="Elements" listSize="-1" @updateSize="updateSize" v-slot="{ update }">
                <KeepAlive>
                    <ElementView @updateSize="update"/>
                </KeepAlive>
            </Tab>
           
        </TabsWrapper>

        <RecentsList class="w-1/5">

        </RecentsList>

     
    </div>
</template>


```
