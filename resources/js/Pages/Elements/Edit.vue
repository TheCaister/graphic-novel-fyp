<template>
    <h1>
        Edit element
    </h1>

    <form @submit.prevent="form.put(route('elements.update', element.element_id))">
        <!-- Create a dropdown to select the type of element you want to create -->
        <select>
            <option value="basic">Basic</option>
            <option value="mindmap">MindMap</option>
            <option value="timeline">Timeline</option>
        </select>

        <div>
            <div v-if="selected === 'Edit'">
                <SimpleEditor v-model="form.content" />
            </div>
            <div v-else>
                <p>Not implemented yet</p>
            </div>
        </div>


        <!-- Make a button to go back and a button to create/save changes -->
        <div>
            <!-- <PrimaryButton @click="goBack()">Back</PrimaryButton> -->
            <PrimaryButton>>Save</PrimaryButton>
        </div>
    </form>
</template>

<script>
import SimpleEditor from '@/Components/Editors/SimpleEditor.vue';
import Edit from './SimpleElement/Edit.vue'
import { useForm } from '@inertiajs/vue3'

export default {
    components: {
        Edit,
        SimpleEditor
    },
    props: {
        element: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            selected: 'Edit',
            form: useForm({
                title: '',
                image: '',
                type: 'test',
                content: null,
                hidden: false,
            }),
        }
    },
    mounted() {
        this.form.title = this.element.title
        this.form.image = this.element.image
        this.form.type = this.element.type
        this.form.hidden = this.element.hidden

        this.form.content = JSON.parse(this.element.content)
    },
}

</script>