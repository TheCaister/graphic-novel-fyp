<template>
    <div class="tabs flex flex-col">
        <ul class="flex">
            <li v-for="title in tabTitles" :key="title" @click="selectedTitle = title">
                {{ title }}

            </li>
        </ul>
         <slot/>
    </div>
</template>

<script setup>

import {ref, provide, useSlots} from 'vue'

const slots = useSlots()

console.log(slots.default())

const tabTitles = ref(slots.default().map((tab) => tab.props.title))
// const tabTitles = ref(['Universe', 'Series', 'Chapter', 'Page'])
const selectedTitle = ref(tabTitles.value[0])

provide('selectedTitle', selectedTitle)
</script>