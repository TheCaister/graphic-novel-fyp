<template>
    <div class="text-white absolute left-0 top-0 flex flex-col gap-4 overflow-auto" style="height: 70vh;">
        <PrimaryButton @click="emits('toggleVisibility')">
                Close
            </PrimaryButton>
        <div v-for="item in layout" class="rounded-lg relative border-4 border-pink-500 p-4 bg-black">
            <div>
                <!-- Do something like Panel + item.i -->
                {{ 'Panel ' + item.i }}
            </div>
            <div v-for="element in item.elements" class="mt-4 flex items-center justify-between px-4">

                <div class="flex items-center">
                    <img :src="element.element_thumbnail ? element.element_thumbnail : '/assets/black_page.jpg'" alt=""
                        class="rounded-full w-12 h-12 mr-4">
                        <div class="text-2xl" :class="{ 'text-black': checked === true }">
                        {{ element.element_name  }}
                    </div>
                </div>

                <button @click="emits('removeElement', item, element)">
                    X
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>

const layout = defineModel()

const emits = defineEmits(['removeElement', 'toggleVisibility'])

</script>