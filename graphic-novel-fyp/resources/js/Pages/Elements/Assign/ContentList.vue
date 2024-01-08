<template>
    <h1>
        Here is a list of all the content:
    </h1>

    <div class="h-96 overflow-auto">
        <ul>
            <li v-for="content in subContentList" :key="content.content_id">
                <div>
                    <input type="checkbox" :id="'content-' + content.content_id" :value="content.content_id">
                    <label :for="'content-' + content.content_id">{{ content.content_name }}</label>
                </div>
            </li>
        </ul>
    </div>

</template>

<script setup>
    import {defineProps, ref} from 'vue'

    const props = defineProps({
        subContentList: {
            type: Array,
            required: true
        }
    })

    const contentItems = ref(props.subContentList)
</script>