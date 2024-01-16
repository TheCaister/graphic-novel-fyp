<template>
    <div class="flex flex-col text-xl">
        <ul class="flex justify-center gap-10 mb-8">
            <li v-for="title in tabTitles" :key="title" @click="selectedTitle = title" class="flex">
                    <div v-if="title === selectedTitle" class="text-pink-400 border-b-2 border-pink-400">
                        {{ title }}
                    </div>
                    <div v-else class="text-gray-400 font-thin">
                        {{ title }}
                    </div>


                <span v-if="title === 'Elements'"
                    class="ml-2 inline-flex items-center justify-center h-6 w-6 rounded-full bg-red-500">
                    <span class="text-white text-xs font-thin">{{ props.size }}</span>
                </span>

            </li>
        </ul>
        <slot />
    </div>
</template>

<script setup>

import { ref, provide, useSlots } from 'vue'

const slots = useSlots()

const props = defineProps({
    size: {
        type: Number,
        required: true
    }
})


const tabTitles = ref(slots.default().filter(vnode => typeof vnode.children !== 'string').map((tab) => tab.props.title))
const selectedTitle = ref(tabTitles.value[0])

provide('selectedTitle', selectedTitle)
</script>

<style>
.swipe-enter-active,
.swipe-leave-active {
  transition: transform 0.5s;
}

.swipe-enter,
.swipe-leave-to {
  transform: translateX(100%);
}
</style>