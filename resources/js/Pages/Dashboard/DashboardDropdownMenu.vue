<template>
    <div class="flex flex-col rounded-md bg-white text-gray-700 text-base font-thin shadow-lg shadow-black" ref="cardMenu">
        <div v-if="events.length > 0">
            <div v-for="event in events" :key="event.id" @click.prevent="menuItemClick(event.eventName)"
                class="cursor-pointer py-4 pl-4 pr-16 whitespace-nowrap text-left hover:bg-gray-200 rounded-md">
                <div v-if="event.eventName === 'delete'" class="text-red-500">
                    {{ event.text }}
                </div>
                <div v-else>
                    {{ event.text }}
                </div>
            </div>
        </div>
        <div v-else class="cursor-pointer py-4 pl-4 pr-16 whitespace-nowrap text-left hover:bg-gray-200 rounded-md">
            None. You're not an admin!
        </div>
    </div>
</template>

<script setup>
import { defineEmits, ref } from 'vue'
import { onClickOutside } from '@vueuse/core'

const emits = defineEmits(['menuItemClick', 'closeMenu'])

const props = defineProps({
    events: {
        type: Array,
        required: true,
    },
})

const cardMenu = ref(null)

onClickOutside(cardMenu, () => {
    emits('closeMenu')
})

function menuItemClick(eventName) {
    // console.log(eventName)
    emits('menuItemClick', eventName)
    // emits('closeMenu')
}
</script>
