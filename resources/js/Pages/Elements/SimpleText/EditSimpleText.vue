<template>
    <div class="bg-white">
        <SimpleEditor v-model="element.content"/>
        <!-- <SimpleEditor  :modelValue="element.content" @update:modelValue="updateContent"/> -->
    </div>
</template>

<script setup>
import SimpleEditor from '@/Components/Editors/SimpleEditor.vue';
import { watch, onMounted } from 'vue'

const props = defineProps({
    element: {
        type: Object,
        required: true
    }
})

// Define the emits for the component
const emit = defineEmits(['updateElement', 'updateChildrenElementIDs'])

watch(props.element, (element) => {

    updateChildrenElementIDs()

    emit('updateElement', element)

})

onMounted(() => {

    console.log('newly mounted, baby')

    updateChildrenElementIDs()
})

function updateContent(content) {
    emit('updateElement', content)
}

function updateChildrenElementIDs() {
    if (props.element.content && props.element.content.content) {
        let mentions = extractMentions(props.element.content.content);
        let childrenElementIDs = []

        // Go through mentions, then extract attrs.id.element_id and add to a children array
        mentions.forEach(mention => {
            childrenElementIDs.push(mention.attrs.id.element_id)
        })

        emit('updateChildrenElementIDs', childrenElementIDs)
    }
}

function extractMentions(obj) {
    let mentions = [];

    // Going through each key in the object
    for (let key in obj) {
        // If the value is an object, call the function recursively
        if (typeof obj[key] === 'object' && obj[key] !== null) {
            mentions = mentions.concat(extractMentions(obj[key]));
            // If the key is 'type' and the value is 'mention', add the object to the mentions array
        } else if (key === 'type' && obj[key] === 'mention') {
            mentions.push(obj);
        }
    }

    return mentions;
}


</script>