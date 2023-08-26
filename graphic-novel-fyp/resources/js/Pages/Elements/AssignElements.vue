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
            <AssignElementSelector :elements="elements" :contentType="selectedContent.type" :contentId="computedContentID" />
        </div>
    </div>
</template>

<script>
import ContentSelector from '@/Pages/Elements/Components/ContentSelector.vue'
import AssignElementSelector from '@/Pages/Elements/Components/AssignElementSelector.vue'
import APICalls from '@/Utilities/APICalls'

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
            default: []
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
        },
        computedContentID(){
            switch (this.selectedContent.type) {
                case 'universes':
                    return this.content.universe_id
                case 'series':
                    return this.content.series_id
                case 'chapters':
                    return this.content.chapter_id
                case 'pages':
                    return this.content.page_id
                default:
                    return 0
            }
        }
        // retrievedElements(){
        //     let content_id;

        //     switch(this.selectedContent.type){
        //         case 'universes':
        //             content_id = this.selectedContent.universe_id;
        //             break;
        //         case 'series':
        //             content_id = this.selectedContent.series_id;
        //             break;
        //         case 'chapters':
        //             content_id = this.selectedContent.chapter_id;
        //             break;
        //         default:
        //             content_id = 0;


        //     }

        //     APICalls.getElements(this.selectedContent.type, content_id).then(response => {
        //         this.elements = response.data.elements;
        //         console.log(this.elements);
        //     }).catch(error => {
        //         console.log(error);
        //     })
        // }

    }
}
</script>