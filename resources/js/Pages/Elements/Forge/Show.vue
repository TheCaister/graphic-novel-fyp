<template>
    <div>
        <ElementSearch />
    </div>

    <!-- Show selectedContent -->
    <div>
        <p>
            {{ selectedContent.type }}: {{ selectedContent.name }}
        </p>

        <p>
            {{ selectedContent.description }}
        </p>
    </div>

    <div v-if="selectedContent.type === 'universes'">
        <ul>
            <li v-for="content in subContentList">
                <div class="flex">
                    <Link :href="route('forge.show', {
                        contentType: 'series',
                        contentId: content.series_id
                    })">
                    {{ content.series_id }}
                    </Link>
                </div>
            </li>
        </ul>
    </div>

    
    <div v-else-if="selectedContent.type === 'series'">
        <ul>
            <li v-for="content in subContentList">
                <div class="flex">
                    <Link :href="route('forge.show', {
                        contentType: 'chapters',
                        contentId: content.chapter_id
                    })">
                    {{ content.chapter_id }}
                    </Link>
                </div>
            </li>
        </ul>
    </div>

    <div v-else-if="selectedContent.type === 'chapters'">
        <ul>
            <li v-for="content in subContentList">
                <div class="flex">
                    <Link :href="route('forge.show', {
                        contentType: 'pages',
                        contentId: content.page_id
                    })">
                    {{ content.page_id }}
                    </Link>
                </div>
            </li>
        </ul>
    </div>

    <div v-else>
        <ul>
            <li v-for="content in subContentList">
            </li>
        </ul>
    </div>
</template>

<script>
import ElementSearch from '@/Components/Search/ElementSearch.vue'

export default {
    components: {
        ElementSearch
    },
    props: {
        selectedContent: {
            type: Object,
            required: true
        },
        content: {
            type: Object,
            required: true
        },
        subContentList: {
            type: Array,
            default: []
        },
    },
}
</script>