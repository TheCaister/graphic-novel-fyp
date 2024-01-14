<template>
    Results of users...

    <!-- Loop through resultsList -->
    <div class="flex">
        <div v-for="result in props.resultsList" :key="result.id" class="shadow-lg ring-2 ring-blue-500">
            <div>
                {{ result.username }}
            </div>
            <div>
                {{ result.bio }}
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