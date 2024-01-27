<template>

<div class="text-white">
<!-- Selected option here + Prompt -->

    <div>
        {{ selectedOption  || "Select an option" }}
    </div>
    <!-- All available options -->
    <div class="flex flex-col">
        <div v-for="option in options" :key="option.id">
            {{ option.name }}
        </div>
    </div>

</div>
</template>

<script setup>
import { ref} from 'vue';

const selectedOption = ref(null)


const props = defineProps({
    options: {
        type: Array,
        required: true,
    },
})

</script>