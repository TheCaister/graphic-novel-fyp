<template>
    <!-- <h1>
        ELEMENTS
    </h1> -->

    <button @click="console.log(elements)">
        Check
    </button>

    <div class=" mt-8 h-96 overflow-auto">
        <TransitionGroup name="list" tag="ul" class="flex flex-col gap-4">
            <div v-for="element in elements" :key="element.element_id">
                <TriCheckbox :label="element.element_name" :image="element.element_thumbnail" v-model:checked="element.checked" v-model:alreadyAttached="element.alreadyAttached"/>
            </div>
        </TransitionGroup>
    </div>
</template>

<script setup>
import { defineEmits, watch } from 'vue'
import TriCheckbox from './TriCheckbox.vue';

const emits = defineEmits(['elementChecked'])

const elements = defineModel()

watch(elements.value, () => {
    reorderElements()
})

// This places the checked === true elements at the top of the list, with checked === false following and checked === null at the bottom.
function reorderElements() {
    // Rearrange the elements array so that the checked === true elements are at the top of the list, with checked === false following and checked === null at the bottom.
    elements.value.sort((a, b) => {

        // First, we encode the checked value into a number. true = -1, false = 1, null = 0
        const first = a.checked === true ? -1 : a.checked === false ? 1 : null
        const second = b.checked === true ? -1 : b.checked === false ? 1 : null

        // In the first section, if ONLY first or second is null, the null value will be at the bottom of the list. If both are not null, we continue to the next section and compare by value.
        return (first === null) - (second === null) || +(first > second) || -(first < second)
    })
}
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