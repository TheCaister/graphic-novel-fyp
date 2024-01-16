<template>
    <div class="flex flex-col rounded-md bg-white text-gray-700 text-base font-thin">
            <div v-for="event in events" :key="event.id" @click="menuItemClick(event.eventName)" class="py-4 pl-4 pr-16 whitespace-nowrap text-left hover:bg-gray-200 rounded-md">
                {{ event.text }}
            </div>
    </div>
</template>

<script setup>
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
