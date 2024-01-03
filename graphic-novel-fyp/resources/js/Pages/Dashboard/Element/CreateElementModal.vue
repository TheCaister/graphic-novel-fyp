<script setup>
import { onClickOutside } from '@vueuse/core'
import { ref, inject, onMounted, computed } from 'vue'

const emit = defineEmits(['closeModal'])
function close() {
    emit('closeModal');
};

const dashboardView = inject('dashboardView')
const parentContentId = inject('parentContentId')

const parentContentType = computed(() => {
    switch (dashboardView.value) {
        case 'UniverseView':
            return 'universe'
        case 'SeriesView':
            return 'series'
        case 'ChapterView':
            return 'chapter'
        case 'PageView':
            return 'page'
        default:
            return 'universe'
    }
})

const modal = ref(null)

onClickOutside(modal, () => {
    close()
})

onMounted(() => {
    console.log(dashboardView)
    console.log(parentContentId)
})



</script>

<template>
    <div>
        <div ref="modal" class="text-lg bg-black shadow-lg rounded-lg p-8 w-4/5">

            <div class="flex flex-col gap-10">
                <h2 class="text-4xl font-bold text-white ">Select type of element to create:</h2>
                <!-- <Link :href="route('')" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Simple Text
                </Link>
                <Link :href="route('')"  class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Mind Map
                </Link>
                <Link :href="route('')"  class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Panel Planner
                </Link> -->

                <Link :href="route('elements.create',
                    {
                        elementType: 'simpleText',
                        contentId: parentContentId,
                        contentType: parentContentType
                    })" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Simple Text
                </Link>
                <Link :href="route('elements.create')"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Mind Map
                </Link>
                <Link :href="route('elements.create')"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Panel Planner
                </Link>
            </div>
        </div>
    </div>
</template>