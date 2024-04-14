<template>
    <div class="text-white absolute left-0 top-0 flex flex-col gap-4 overflow-auto" style="height: 70vh;">
        <PrimaryButton @click="emits('toggleVisibility')">
            Close
        </PrimaryButton>
        <div v-if="layout.length > 0" class="flex flex-col gap-4">
            <div v-for="item in layout" class="rounded-lg relative border-4 border-pink-500 p-4 bg-black">
                <div>
                    <!-- Do something like Panel + item.i -->
                    {{ 'Panel ' + item.i }}
                </div>
                <button @click="router.visit(route('elements.edit', element.element_id))" v-for="element in item.elements" class="mt-4 flex items-center justify-between px-4 w-full">
                    <div class="flex items-center">
                        <img :src="element.element_thumbnail ? element.element_thumbnail : '/assets/black_pixel.png'"
                            alt="" class="rounded-full w-12 h-12 mr-4">
                        <div class="text-2xl" >
                            {{ element.element_name }}
                        </div>
                    </div>
                    <button @click.stop="emits('removeElement', item, element)">
                        X
                    </button>
                </button>
            </div>
        </div>
        <div v-else>
            <div class="text-center">
                No panels.
            </div>
        </div>
    </div>
</template>

<script setup>

import { router } from '@inertiajs/vue3';

const layout = defineModel()



const emits = defineEmits(['removeElement', 'toggleVisibility'])

</script>