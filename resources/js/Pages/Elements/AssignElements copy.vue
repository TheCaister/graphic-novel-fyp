<template>
    <h1>Assign Elements</h1>
    <!-- The selected content is... -->
    <h2>The selected content is: {{ selectedContent }}</h2>

    <!-- Give details  -->

    <!-- Put contentselector and assignelementselector side by side -->
    <div class="flex flex-row">
        <div class="w-1/2">
            <ContentSelector :contentList="subContentList" :contentType="computedSubContentType"
                :parentContent="content" v-model:value="selectedContentCheckboxes" />
        </div>
        <div class="w-1/2">
            <AssignElementSelector :elements="elements" :contentType="selectedContent.type" :contentId="computedContentID"
                v-model:value="selectedElementsCheckboxes" />
        </div>
    </div>

    <div>
        <!-- <Link href="#" @click="back"> -->
        <PrimaryButton @click="saveAssignment">Save</PrimaryButton>
        <!-- </Link> -->
    </div>
</template>

<script>
import ContentSelector from '@/Pages/Elements/Components/ContentSelector.vue'
import AssignElementSelector from '@/Pages/Elements/Components/AssignElementSelector.vue'
import { useForm } from '@inertiajs/vue3'
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
        },
        subContentList: {
            type: Array,
            default: []
        }


    },
    data() {
        return {
            selectedContentCheckboxes: [],
            selectedElementsCheckboxes: [],
        }
    },
    components: {
        ContentSelector,
        AssignElementSelector
    },
    computed: {
        // computedSubContentList() {
        //     // Go through a switch statement to set subContentList
        //     switch (this.selectedContent.type) {
        //         case 'universes':
        //             return this.content.series
        //         case 'series':
        //             return this.content.chapters
        //         case 'chapters':
        //             return this.subContentList = this.content.pages
        //         default:
        //             return []
        //     }
        // },
        computedSubContentType() {
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
        computedContentID() {
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

    },
    methods: {
        saveAssignment(){

            // Create  a form object with the selectedContentCheckboxes and selectedElementsCheckboxes with useForm
            const form = useForm({
                selectedContent: this.selectedContentCheckboxes,
                selectedElements: this.selectedElementsCheckboxes
            })



            form.post(route('elements.assignStore'), {
                preserveScroll: true,
                onSuccess: () => {
                    // Clear the comment field
                    // this.form.comment_content = "";
                    // Emit the commentCreated event
                    // this.$emit('commentCreated');
                }
            });
        }
    },
    mounted() {
        console.log(this.selectedContent)
        // console.log(this.content)
        // console.log(this.elements)
        // console.log(this.subContentList)
    }
}
</script>