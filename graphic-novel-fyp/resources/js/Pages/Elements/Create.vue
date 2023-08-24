<template>
    <h1>
        Create a new element
    </h1>

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
        <PrimaryButton>Back</PrimaryButton>
        <PrimaryButton @click="submit()">>Save</PrimaryButton>
    </div>
</template>

<script>
import SimpleEditor from '@/Components/Editors/SimpleEditor.vue';
import Edit from './SimpleElement/Edit.vue'

export default {
    components: {
        Edit,
        SimpleEditor
    },
    data() {
        return {
            selected: 'Edit',
            form: {
                title: '',
                description: '',
                image: '',
                content: '',
            }
        }
    },
    methods: {
        submit() {
            // Submit the form
            console.log(this.form);
            this.form.content = '';
        }
    }
}

</script>