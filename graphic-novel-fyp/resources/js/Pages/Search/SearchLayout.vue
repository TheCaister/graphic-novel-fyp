<template>
    <Head title="Search" />
    
    <div class="text-white text-lg">
        <!-- Search bar with buttons for users, content and elements -->
        <div>
            Search bar...
        </div>

        <!-- Advanced search -->
        <div>
            Advanced search...
        </div>

        <!-- Sorting, different views -->
        <div>
            Sorting, different views...
        </div>

        <!-- Results here -->
        <KeepAlive>
            <component :is="ResultsViewComponent" :resultsList="resultsList" />
        </KeepAlive>
    </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { onMounted, computed, inject, ref } from 'vue'
import { useForm } from '@inertiajs/vue3';
import SearchContentResults from './SearchContentResults.vue';
import SearchElementsResults from './SearchElementsResults.vue';
import SearchUsersResults from './SearchUsersResults.vue';

const props = defineProps({
    searchType: {
        type: String,
    },
})

const resultsList = ref([])

const ResultsViewComponent = computed(() => {
    // Switch statement to return the correct dashboard view
    switch (props.searchType) {
        case 'content':
            return SearchContentResults
        case 'elements':
            return SearchElementsResults
        case 'users':
            return SearchUsersResults
        default:
            return SearchContentResults
    }
})

const form = useForm({
    search: '',
});

function search(){
    if(form.search !== ''){
        router.get(route('search'), {
            search: form.search,
        });
    }
}

function back(){
    router.back();
}
</script>