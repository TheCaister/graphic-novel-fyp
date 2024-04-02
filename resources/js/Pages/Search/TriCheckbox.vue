<template>
    <div class="flex h-24 items-center">
        <button @click="toggleChecked"
            :class="[
                'flex',
                'items-center',
                'rounded-md',
                'justify-between',
                'w-full',
                'p-3',
                'border-2',
                checked === true ? 'border-green-500' : checked === false ? 'border-red-500' : 'border-gray-500'
            ]">

            <div class="flex items-center">
                {{ label }}
            </div>

            <span v-if="checked === true" class="material-symbols-outlined dark">
                check_circle
            </span>

            <!-- Else if... -->
            <span v-else-if="checked === false" class="material-symbols-outlined dark">
                remove
            </span>
        </button>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const emits = defineEmits(['checked'])

const props = defineProps({
    image: {
        type: String,
    },
    preChecked: {
        type: Boolean,
        default: null
    },
    label: {
        type: String,
        required: true
    }
})

const checked = ref(props.preChecked)

function toggleChecked() {

    console.log(checked.value)

    if (checked.value === null) {
        checked.value = true
    } else if (checked.value === true) {
        checked.value = false
    } else if (checked.value === false) {
        checked.value = null
    }
    emits('checked', checked.value)
}

</script>