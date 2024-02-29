<template>
    Results of elements...

    <div class="flex flex-wrap">
        <div v-for="result in props.resultsList" :key="result.id" class="shadow-lg ring-2 ring-blue-500 w-64 h-64 rounded-lg">
            <div>
                <img :src="result.element_thumbnail" alt="">
            </div>
            <div>
                {{ result.element_name }}
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    resultsList: {
        type: Array,
        required: true
    }
})
</script>