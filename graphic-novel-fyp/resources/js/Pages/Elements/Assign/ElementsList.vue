<template>
    <h1>
        Here is a list of all the elements:
    </h1>

    <div>
        <ul>
            <li v-for="element in elements" :key="element.element_id">
                <Link :href="route('elements.edit', element.element_id)">
                    {{ element.content }}
                </Link>
            </li>
        </ul>
    </div>

</template>

<script setup>
    import {defineProps, ref} from 'vue'

    const props = defineProps({
        elements: {
            type: Array,
            required: true
        }
    })

    const elements = ref([])

    elements.value = [
        // some random elements
        {
            element_id: 1,
            content: 'Element 1'
        },
        {
            element_id: 2,
            content: 'Element 2'
        },
        {
            element_id: 3,
            content: 'Element 3'
        },
        {
            element_id: 4,
            content: 'Element 4'
        },
        {
            element_id: 5,
            content: 'Element 5'
        },
        {
            element_id: 6,
            content: 'Element 6'
        },
        {
            element_id: 7,
            content: 'Element 7'
        },
        {
            element_id: 8,
            content: 'Element 8'
        },
        {
            element_id: 9,
            content: 'Element 9'
        },
        {
            element_id: 10,
            content: 'Element 10'
        },
        {
            element_id: 11,
            content: 'Element 11'
        },
        {
            element_id: 12,
            content: 'Element 12'
        },
        {
            element_id: 13,
            content: 'Element 13'
        },
        {
            element_id: 14,
            content: 'Element 14'
        },
        {
            element_id: 15,
            content: 'Element 15'
        },
        {
            element_id: 16,
            content: 'Element 16'
        },
        {
            element_id: 17,
            content: 'Element 17'
        },
        {
            element_id: 18,
            content: 'Element 18'
        },
        {
            element_id: 19,
            content: 'Element 19'
        },
        {
            element_id: 20,
            content: 'Element 20'
        },
        {
            element_id: 21,
            content: 'Element 21'
        },
        {
            element_id: 22,
            content: 'Element 22'
        },
        {
            element_id: 23,
            content: 'Element 23'
        },
        {
            element_id: 24,
            content: 'Element 24'
        },
        {
            element_id: 25,
            content: 'Element 25'
        },
        {
            element_id: 26,
            content: 'Element 26'
        },
    ]
</script>