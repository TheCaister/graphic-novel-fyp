<template>
    <div class="text-white absolute left-0 top-0 flex flex-col gap-4 overflow-auto" style="height: 70vh;">
        <PrimaryButton @click="emits('toggleVisibility')">
                Close
            </PrimaryButton>

            <PrimaryButton @click="console.log(layout)">
                Check
            </PrimaryButton>
        <div v-for="item in layout" class="rounded-lg relative border-4 border-pink-500 p-4 bg-black">
            <div>
                <!-- Do something like Panel + item.i -->
                {{ 'Panel ' + item.i }}
            </div>
            <div v-for="element in item.elements">
                {{ element.element_name }}

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