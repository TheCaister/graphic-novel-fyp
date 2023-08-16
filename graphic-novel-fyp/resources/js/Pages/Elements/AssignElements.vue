<template>
    <h1>Assign Elements</h1>
    <!-- The selected content is... -->
    <h2>The selected content is: {{ selectedContent }}</h2>

    <!-- Give details  -->

    <!-- Put contentselector and assignelementselector side by side -->
    <div class="flex flex-row">
        <div class="w-1/2">
            <ContentSelector :contentList="computedSubContentList" :contentType="computedSubContentType" :parentContent="content"/>
        </div>
        <div class="w-1/2">
            <AssignElementSelector :elements="elements" />
        </div>
    </div>
</template>

<script>
import ContentSelector from '@/Pages/Elements/Components/ContentSelector.vue'
import AssignElementSelector from '@/Pages/Elements/Components/AssignElementSelector.vue'

export default {
    props: {
        selectedContent: {
            type: Object,
            required: true
        },
        content: {
            type: Object,
            required: true
        },
        elements: {
            type: Array,
            required: true
        }


    },
    data() {
        return {
            subContentList: [],

        }
    },
    components: {
        ContentSelector,
        AssignElementSelector
    },
    computed: {
        computedSubContentList(){
            // Go through a switch statement to set subContentList
            switch (this.selectedContent.type) {
                case 'universes':
                    return this.content.series
                case 'series':
                    return this.content.chapters
                case 'chapters':
                    return this.subContentList = this.content.pages
                default:
                    return []
            }
        },
        computedSubContentType(){
            switch (this.selectedContent.type) {
                case 'universes':
                    return 'series'
                case 'series':
                    return 'chapters'
                case 'chapters':
                    return 'pages'
                default:
                    return ''
            }
        }

    }
}
</script>