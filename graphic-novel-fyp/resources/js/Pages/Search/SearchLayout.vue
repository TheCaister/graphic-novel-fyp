<template>
    <Head title="Search" />

    <div class="text-white text-lg bg-gray-500 flex flex-col">
        <!-- Search bar with buttons for users, content and elements -->
        <div>

            <select name="
                " id="" class="bg-gray-500">
                <option value="">Users</option>
                <option value="">Content</option>
                <option value="">Elements</option>
            </select>
            <!-- input, with dropdown on the right for 3 options -->
            <input type="text" class="bg-gray-500 border-4 border-gray-300" />
            <button>
                Search
            </button>

        </div>

        <!-- Advanced search -->
        <div>

            <KeepAlive>
                <component :is="AdvancedFiltersComponent" :resultsList="resultsList" />
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
import { Head } from '@inertiajs/vue3';
import { onMounted, computed, inject, ref } from 'vue'
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
    },
    initResultsList: {
        type: Array,
        default: () => []
    }
})

const resultsList = ref([])

resultsList.value = [
    {
        id: 1,
        username: 'test',
        bio: 'Hi I am a test user',
    },
    {
        id: 2,
        username: 'test2',
        bio: 'Hi I am a test user',
    },
    {
        id: 3,
        username: 'test3',
        bio: 'Hi I am a test user',
    },
]

const ResultsViewComponent = computed(() => {
    return SearchUsersResults

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
    return AdvancedUserFilters

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

const form = useForm({
    search: '',
});

function search() {
    if (form.search !== '') {
        router.get(route('search'), {
            search: form.search,
        });
    }
}

function back() {
    router.back();
}
</script>