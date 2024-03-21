<template>

    <div class="text-gray-400  px-4">
        <!-- Selected option here + Prompt -->

        <div @click="isDropDownVisible = !isDropDownVisible" class="hover:cursor-pointer flex items-center">
            <span>{{ props.label }}</span>
            <svg class="w-4 h-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M6.293 7.293a1 1 0 0 1 1.414 0L10 9.586l2.293-2.293a1 1 0 1 1 1.414 1.414l-3 3a1 1 0 0 1-1.414 0l-3-3a1 1 0 0 1 0-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </div>
        <!-- All available options -->
        <Transition name="slide-fade">
            <div class="flex flex-col items-center absolute z-50" v-if="isDropDownVisible">
                <div v-for="option in options" :key="option.id" @click="toggleOptionSelect(option)"
                    class="hover:cursor-pointer">
                    {{ option.name }}
                </div>
            </div>
        </Transition>

    </div>
</template>

<script setup>
import { ref } from 'vue';

const isDropDownVisible = ref(false)

const props = defineProps({
    label: {
        type: String,
        required: true,
        default: 'Projects'
    },
    options: {
        type: Array,
        required: true,
    },
})

const emits = defineEmits(['optionSelected'])

function toggleOptionSelect(option) {
    // selectedOption.value = option
    emits('optionSelected', option)
    isDropDownVisible.value = false
}

</script>

<style scoped>
/* Create a slide-fade transition for the select options */
.slide-fade-enter,
.slide-fade-leave-to,
.slide-fade-enter-active {
    /* transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
    opacity: 0;
    transform: translateY(-10px); */
}

.slide-fade-leave-active {
    transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
    opacity: 0;
    transform: translateY(-10px);
}
</style>