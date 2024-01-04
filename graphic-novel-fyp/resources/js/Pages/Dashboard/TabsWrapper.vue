<template>
    <div class="flex flex-col">
        <ul class="flex justify-center gap-10">
            <li v-for="title in tabTitles" :key="title" @click="selectedTitle = title">
                <div v-if="title === selectedTitle" class="text-pink-500">
                    {{ title }}
                </div>
                <div v-else>
                    {{ title }}
                </div>
             

            </li>
        </ul>
         <slot/>
    </div>
</template>

<script setup>

import {ref, provide, useSlots} from 'vue'

const slots = useSlots()

// console.log(slots.default())


// console.log(slots.default().filter(vnode => vnode.type.toString() !== "Symbol('v-cmt')"))
console.log(slots.default().filter(vnode => typeof vnode.children !== 'string'))


const tabTitles = ref(slots.default().filter(vnode => typeof vnode.children !== 'string').map((tab) => tab.props.title))
const selectedTitle = ref(tabTitles.value[0])

provide('selectedTitle', selectedTitle)
</script>