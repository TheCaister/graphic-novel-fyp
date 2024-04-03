<template>
   <div class="absolute right-0 top-0 text-white">
            <button @click="showPanelDescriptionList = !showPanelDescriptionList">
                x
            </button>
            <div v-for="item in layout" class="rounded-lg border-4 border-pink-500 p-4">
                <div>
                    {{ 'Panel ' + item.i }}
                </div>
                <div>
                    <input type="text" v-model="item.text" class="text-black" placeholder="Type here" />

                </div>
            </div>
        </div>
</template>

<script setup>

const layout = defineModel()

const emits = defineEmits(['toggleVisibility'])

</script>