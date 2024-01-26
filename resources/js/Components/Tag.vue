<template>

    <div class="flex rounded-full bg-green-500">
        <p>
            {{ label }}
        </p>

        <button @click="closeTag">
            X
        </button>
    </div>


</template>

<script setup>

// import defineemits
import { defineEmits} from 'vue'

const props = defineProps({
    label: {
        type: String,
        required: true
    },
})

const emits = defineEmits(['closeTag'])

function closeTag(){
    emits('closeTag')
}

</script>