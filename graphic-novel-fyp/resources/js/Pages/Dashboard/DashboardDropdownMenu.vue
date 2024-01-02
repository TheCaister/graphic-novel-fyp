<template>
    <div class="flex flex-col border-4">
        <div class="flex flex-col">
            <div v-for="event in events" :key="event.id" @click="menuItemClick(event.eventName)">
                    {{ event.text }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
    events: {
        type: Array,
        required: true,
    },
})

const emit = defineEmits(['menuItemClick'])

function menuItemClick(eventName) {
    emit('menuItemClick', eventName)
}
</script>
