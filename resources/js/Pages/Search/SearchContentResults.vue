<template>
    Results of content...

    <div class="flex">
        <div v-for="result in props.resultsList" :key="result.id" class="shadow-lg ring-2 ring-blue-500">
            <Link :href="result.link">
                <div>
                    {{ result.title }}
                </div>
            </Link>
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