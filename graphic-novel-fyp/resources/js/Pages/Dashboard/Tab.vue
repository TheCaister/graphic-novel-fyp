<template>
    <div class="tab-content" v-show="title == selectedTitle">
        <slot/>
    </div>

</template>

<script setup>
import { inject, defineProps } from 'vue'

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
})

const selectedTitle = inject('selectedTitle')


</script>