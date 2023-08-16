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

    <component :is="selected">
        <!-- Render the selected component -->
    </component>
</template>

<script>
import Edit from './SimpleElement/Edit.vue'

export default {
    components: {
        Edit
    },
    data() {
        return {
            selected: 'Edit'
        }
    },
}

</script>