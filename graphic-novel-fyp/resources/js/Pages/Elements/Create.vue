<template>
    <h1>
        Create a new element
    </h1>

    <form @submit.prevent="form.post(route('elements.store'))">
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
    methods: {
    }
}

</script>