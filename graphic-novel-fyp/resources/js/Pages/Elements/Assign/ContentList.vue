<template>
    <h1>
        Here is a list of all the content:
    </h1>

    <div class="h-96 overflow-auto">
        <ul>
            <li v-for="content in contentItems" :key="content.content_id">
                <div>
                    <input type="checkbox" :id="'content-' + content.content_id" :value="content.content_id">
                    <label :for="'content-' + content.content_id">{{ content.title }}</label>
                </div>
            </li>
        </ul>
    </div>

</template>

<script setup>
    import {defineProps, ref} from 'vue'

    const props = defineProps({
        parentContent: {
            type: Object,
            required: true
        }
    })

    const contentItems = ref([])

    contentItems.value = [
        {
            content_id: 1,
            title: 'Content 1'
        },
        {
            content_id: 2,
            title: 'Content 2'
        },
        {
            content_id: 3,
            title: 'Content 3'
        },
        {
            content_id: 4,
            title: 'Content 4'
        },
        {
            content_id: 5,
            title: 'Content 5'
        },
        {
            content_id: 6,
            title: 'Content 6'
        },
        {
            content_id: 7,
            title: 'Content 7'
        },
        {
            content_id: 8,
            title: 'Content 8'
        },
        {
            content_id: 9,
            title: 'Content 9'
        },
        {
            content_id: 10,
            title: 'Content 10'
        },
        {
            content_id: 11,
            title: 'Content 11'
        },
        {
            content_id: 12,
            title: 'Content 12'
        },
        {
            content_id: 13,
            title: 'Content 13'
        },
        {
            content_id: 14,
            title: 'Content 14'
        },
        {
            content_id: 15,
            title: 'Content 15'
        },
        {
            content_id: 16,
            title: 'Content 16'
        },
        {
            content_id: 17,
            title: 'Content 17'
        },
        {
            content_id: 18,
            title: 'Content 18'
        },
        {
            content_id: 19,
            title: 'Content 19'
        },
        {
            content_id: 20,
            title: 'Content 20'
        },
        {
            content_id: 21,
            title: 'Content 21'
        },
        {
            content_id: 22,
            title: 'Content 22'
        },
        {
            content_id: 23,
            title: 'Content 23'
        },
        {
            content_id: 24,
            title: 'Content 24'
        },
        {
            content_id: 25,
            title: 'Content 25'
        },
        {
            content_id: 26,
            title: 'Content 26'
        },
        {
            content_id: 27,
            title: 'Content 27'
        },
        {
            content_id: 28,
            title: 'Content 28'
        },
    ]
</script>