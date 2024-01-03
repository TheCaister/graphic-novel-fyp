<script setup>
import { Head, router } from '@inertiajs/vue3';
import UniverseView from './Universe/UniverseView.vue';
import SeriesView from './Series/SeriesView.vue';
import ChapterView from './Chapter/ChapterView.vue';
import PageView from './Page/PageView.vue';
import ElementView from './Element/ElementView.vue';
import TabsWrapper from './TabsWrapper.vue';
import Tab from './Tab.vue';
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
}

function goBack() {
    router.visit(route('content.get-parent', { type: dashboardView.value, id: parentContentIdNumber.value }))
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

    <div class="text-gray-500 flex justify-center">

        <div>
            <button>Content</button>
            <button>Elements</button>
        </div>
    </div>

    <div >

        <TabsWrapper class="text-4xl font-bold text-white flex flex-wrap ">
            <Tab title="Content">

                <KeepAlive>
                    <component :is="DashboardViewComponent" :parentContentId="parentContentIdNumber"
                        @updateDashboard="updateDashboard" />
                </KeepAlive>


            </Tab>

            <Tab title="Elements">
                <ElementView/>
            </Tab>
        </TabsWrapper>

        <!-- Make dynamic -->


    </div>
</template>


```
