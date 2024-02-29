<template>
    Results of users...

    <!-- Loop through resultsList -->
    <div class="flex">
        <div v-for="result in props.resultsList" :key="result.id" class="shadow-lg ring-2 ring-blue-500">
            <Link>
                <div>
                    {{ result.username }}
                </div>
                <div v-if="result.bio && result.bio.trim() !== ''">
                    {{ result.bio }}
                </div>
                <div v-else>
                    No bio
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