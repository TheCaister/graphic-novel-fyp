<template>

    <Head title="Search" />

    <div class="text-white text-lg flex flex-col p-8 items-center">
        <!-- Search bar with buttons for users, content and elements -->
        <form @submit.prevent="search" class="flex items-center mb-8 h-10 w-1/2">

            <select v-model="form.searchType" @change="testing" class="bg-gray-600 rounded-l-lg border-r-2 border-gray-600 ">
                <option value="users">Users</option>
                <option value="content">Content</option>
                <option value="elements">Elements</option>
            </select>
            <!-- input, with dropdown on the right for 3 options -->
            <input type="text" class=" flex-grow h-full border-gray-600 text-sm focus:outline-none bg-transparent placeholder-gray-300 rounded-r-lg" v-model="form.search" />

            <!-- <PrimaryButton @click="search" class="ml-8">Search</PrimaryButton> -->
            <PrimaryButton  class="ml-8">Search</PrimaryButton>

        </form>

        <!-- big button -->
        <!-- <div class="text-white">
            <button @click="console.log(form.advanced)">
                Print form
            </button>
        </div> -->

        <!-- Advanced search -->
        <div class="w-3/4">
            <KeepAlive>
                <component :is="AdvancedFiltersComponent" v-model="form.advanced"
                    @update-advanced-search="updateAdvancedSearch" />
            </KeepAlive>
        </div>

        <div>
            <KeepAlive>
                <component :is="ResultsViewComponent" :resultsList="resultsList" />
            </KeepAlive>
        </div>


    </div>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3';
import { onMounted, computed, ref, onUpdated } from 'vue'
import { useForm } from '@inertiajs/vue3';
import SearchContentResults from './SearchContentResults.vue';
import SearchElementsResults from './SearchElementsResults.vue';
import SearchUsersResults from './SearchUsersResults.vue';
import AdvancedContentFilters from './AdvancedContentFilters.vue';
import AdvancedElementFilters from './AdvancedElementFilters.vue';
import AdvancedUserFilters from './AdvancedUserFilters.vue';

const props = defineProps({
    initResultsList: {
        type: Array,
        default: []
    },
    searchParams: {
        type: Object,
        default: {}
    },
    errors: {
        type: Object,
    },
    auth: {
        type: Object,
    },
    ziggy: {
        type: Object,
    }
})

const resultsList = ref(props.initResultsList)
const currentSearchType = ref(props.searchParams.searchType)

const form = useForm({
    search: '',
    searchType: '',
    // In advanced, it's a bunch of 
    // key : something like boolean to toggle that search option
    advanced: {},
    limit: 10,
});

const ResultsViewComponent = computed(() => {
    // return SearchUsersResults

    // Switch statement to return the correct dashboard view
    // switch (form.searchType) {
    //     case 'content':
    //         return SearchContentResults
    //     case 'elements':
    //         return SearchElementsResults
    //     case 'users':
    //         return SearchUsersResults
    //     default:
    //     // return SearchUsersResults
    // }

    switch (currentSearchType.value) {
        case 'content':
            return SearchContentResults
        case 'elements':
            return SearchElementsResults
        case 'users':
            return SearchUsersResults
        default:
        // return SearchUsersResults
    }
})

const AdvancedFiltersComponent = computed(() => {
    // Switch statement to return the correct dashboard view
    switch (form.searchType) {
        case 'content':
            return AdvancedContentFilters
        case 'elements':
            return AdvancedElementFilters
        case 'users':
            return AdvancedUserFilters
        default:
        // return AdvancedUserFilters
    }
})

function testing(event){
    // console.log(event)
   // The value of the selected option
   let selectedOption = event.target.value;

// Do something with selectedOption
console.log(selectedOption);
}



function search() {

    console.log(form.advanced)

    // form.get(route('search'), {
    //     onFinish: () => {
    //         console.log('success')
    //         form.search = ''
    //     }
    // })


    form.post(route('search',
        {
            'searchType': form.searchType,
            'search': form.search
        }
    ), {
        onFinish: () => {
            resultsList.value = []
            currentSearchType.value = form.searchType
            console.log('success')
            // form.search = ''
        }
    })
    // if (form.search !== '') {
    //     // router.get(route('search'), {
    //     //     search: form.search,
    //     // });


    // }

    // clear the advanced search filters
    // form.advanced = {};
}

function back() {
    router.back();
}

function updateAdvancedSearch({ name, value }) {
    if (value === null) {
        delete form.advanced[name];
    } else {
        form.advanced[name] = value;
    }
    console.log(form.advanced);
}

onMounted(() => {
    console.log('mounting')
    form.search = props.searchParams.search;
    form.searchType = props.searchParams.searchType;
    form.advanced = props.searchParams?.advanced || {};
    resultsList.value = props.initResultsList;
})

onUpdated(() => {
    currentSearchType.value = props.searchParams.searchType;

    resultsList.value = props.initResultsList;
})
</script>