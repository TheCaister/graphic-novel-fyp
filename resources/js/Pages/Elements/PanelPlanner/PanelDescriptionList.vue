<template>
    <div class="absolute right-0 top-0 text-white flex flex-col gap-4 overflow-auto  border-4 rounded-lg p-4 border-pink-500 bg-gray-900" style="height: 70vh;">
        <PrimaryButton @click="emits('toggleVisibility')">
            Close
        </PrimaryButton>
        <div v-if="layout.length > 0" class="flex flex-col gap-4">
            <div v-for="item in layout" class="rounded-lg border-4 border-pink-500 p-4 bg-black">
                <div>
                    {{ 'Panel ' + item.i }}
                </div>
                <div>
                    <textarea type="text" v-model="item.text"
                        class="mt-4 text-white bg-black border-2 rounded-lg w-full" placeholder="Type here" />
                </div>
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

const layout = defineModel()

const emits = defineEmits(['toggleVisibility'])

</script>