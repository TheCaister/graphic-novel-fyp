<template>
    <h1>
        Here is a list of all the elements:
    </h1>

    <div class="h-96 overflow-auto">
        <TransitionGroup name="list" tag="ul">
            <div v-for="element in elements" :key="element.element_id">
                <TriCheckbox :label="element.element_name" image=""
                    @checked="(event) => check(element.element_id, event)" />
            </div>
        </TransitionGroup>
    </div>
</template>

<script setup>
import { defineProps, ref, defineEmits, onMounted } from 'vue'
import TriCheckbox from './TriCheckbox.vue';

const emits = defineEmits(['elementChecked'])

const props = defineProps({
    elementList: {
        type: Array,
        required: true
    },
    preSelectedElementIds: {
        type: Array,
        required: true
    }
})

const elements = ref(props.elementList)

function check(elementId, event) {

    const selectedElement = elements.value.find(element => element.element_id === elementId)

    selectedElement.checked = event

    // console.log(selectedElement)

    // Rearrange the elements array so that the checked === true elements are at the top of the list, with checked === false following and checked === null at the bottom.
    elements.value.sort((a, b) => {

        // First, we encode the checked value into a number. true = -1, false = 1, null = 0
        const first = a.checked === true ? -1 : a.checked === false ? 1 : null
        const second = b.checked === true ? -1 : b.checked === false ? 1 : null

        // In the first section, if ONLY first or second is null, the null value will be at the bottom of the list. If both are not null, we continue to the next section and compare by value.
        return (first === null) - (second === null) || +(first > second) || -(first < second)
    })

    emits('elementChecked', {
        element_id: selectedElement.element_id,
        checked: selectedElement.checked
    })
}

onMounted(() => {
    // Set all elements to checked === null
    elements.value.forEach(element => {
        element.checked = null
    })
})
</script>

<style scoped>
.list-enter-active,
.list-leave-active {
    transition: all 0.5s ease;
}

.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateX(30px);
}

.list-move {
    transition: transform 0.5s ease;
}
</style>