<template>
    <Head title="Search" />

    <div class="text-white text-lg bg-gray-500 flex flex-col">
        <!-- Search bar with buttons for users, content and elements -->
        <div>

            <select v-model="form.searchType" class="bg-gray-500">
                <option value="users">Users</option>
                <option value="content">Content</option>
                <option value="elements">Elements</option>
            </select>
            <!-- input, with dropdown on the right for 3 options -->
            <input type="text" class="bg-gray-500 border-4 border-gray-300" v-model="form.search" />
            <button @click="search">
                Search
            </button>

        </div>

        <!-- big button -->
        <div>
            <button @click="test">
                Print form
            </button>
        </div>

        <!-- Advanced search -->
        <div>

            <KeepAlive>
                <component :is="AdvancedFiltersComponent" @update-advanced-search="updateAdvancedSearch" />
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
import { onMounted, computed, inject, ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3';
import SearchContentResults from './SearchContentResults.vue';
import SearchElementsResults from './SearchElementsResults.vue';
import SearchUsersResults from './SearchUsersResults.vue';
import AdvancedContentFilters from './AdvancedContentFilters.vue';
import AdvancedElementFilters from './AdvancedElementFilters.vue';
import AdvancedUserFilters from './AdvancedUserFilters.vue';

const props = defineProps({
    searchType: {
        type: String,
        default: 'users'
    },
    initResultsList: {
        type: Array,
        default: []
    }
})

const resultsList = ref(props.initResultsList)
// const searchType = ref(props.searchType)

const form = useForm({
    search: '',
    searchType: props.searchType,
    // In advanced, it's a bunch of 
    // key : something like boolean to toggle that search option
    advanced: {},
});

const ResultsViewComponent = computed(() => {
    // return SearchUsersResults

    // Switch statement to return the correct dashboard view
    switch (props.searchType) {
        case 'content':
            return SearchContentResults
        case 'elements':
            return SearchElementsResults
        case 'users':
            return SearchUsersResults
        default:
            return SearchUsersResults
    }
})

const AdvancedFiltersComponent = computed(() => {
    // Switch statement to return the correct dashboard view
    switch (props.searchType) {
        case 'content':
            return AdvancedContentFilters
        case 'elements':
            return AdvancedElementFilters
        case 'users':
            return AdvancedUserFilters
        default:
            return AdvancedUserFilters
    }
})



function search() {
    if (form.search !== '') {
        // router.get(route('search'), {
        //     search: form.search,
        // });

        form.get(route('search'), {
            onFinish: () => {
                console.log('success')
                form.search = ''
            }
        })
    }
}

function back() {
    router.back();
}

function test() {
    console.log(form)
}

function updateAdvancedSearch({ name, value }) {
    if (value === null) {
        delete form.advanced[name];
    } else {
        form.advanced[name] = value;
    }
    console.log(form.advanced);
}
</script>