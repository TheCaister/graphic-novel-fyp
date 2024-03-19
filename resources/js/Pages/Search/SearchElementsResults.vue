<template>

    <div class="flex space-x-8 p-8 flex-wrap">
        <button @click="console.log(props.resultsList)">
                Print form
            </button>
        <div v-for="element in props.resultsList" :key="element.element_id" class="shadow-lg w-96">
            <!-- <Link :href="route('elements.show', result.element_id)">
                <div>
                    <img :src="result.element_thumbnail" alt="">
                </div>
                <div>
                    {{ result.element_name }}
                </div>
            </Link> -->

            <content-card-detailed :content="{
            content_id: element.element_id,
            content_name: element.element_name,
            subheading: getElementTypeText(element.derived_element_type),
            thumbnail: element.element_thumbnail,
        }" :link="route('elements.show', element.element_id)"
        :selected="false" :drop-down-menu-options="[]"
                @switch-selected-content="" @menu-item-click="" />


            <!-- <ContentCard :content="{
            content_id: universe.universe_id,
            content_name: universe.universe_name,
            thumbnail: universe.thumbnail
        }" :link="route('universes.show', universe.universe_id)"
                :selected="universe.universe_id === selectedUniverse.universe_id" :drop-down-menu-options="dropDownMenuOptions.filter(option =>
            !option.needsAdmin || (option.needsAdmin && universe.can_edit)
        )" @switch-selected-content="switchSelectedContent" @menu-item-click="handleMenuItemClicked" /> -->
        </div>
    </div>
</template>

<script setup>

import ContentCard from '../Dashboard/ContentCard.vue';
import ContentCardDetailed from '../Dashboard/ContentCardDetailed.vue';

const props = defineProps({
    resultsList: {
        type: Array,
        required: true
    }
})

function getElementTypeText(text){
    switch(text){
        case 'App\\Models\\SimpleTextElement':
            return 'Simple Text'
        case 'App\\Models\\MindmapElement':
            return 'Mindmap'
        case 'App\\Models\\PanelPlannerElement':
            return 'Panel Planner'
    }
}
</script>