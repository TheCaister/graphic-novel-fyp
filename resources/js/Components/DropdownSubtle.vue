<template>

<div class="text-white">
<!-- Selected option here + Prompt -->

    <div @click="isDropDownVisible = !isDropDownVisible">
        <!-- {{ selectedOption.hasOwnProperty('name') ? selectedOption.name : "Select an option" }} -->
        Projects
    </div>
    <!-- All available options -->
    <!-- <Transition name="slide-fade"> -->
        <div class="flex flex-col" v-if="isDropDownVisible">
            <div v-for="option in options" :key="option.id"
            @click="toggleOptionSelect(option)">
                {{ option.name }}
            </div>
        </div>
    <!-- </Transition> -->

</div>
</template>

<script setup>
import { ref} from 'vue';

// const selectedOption = ref({})

const isDropDownVisible = ref(false)

const props = defineProps({
    options: {
        type: Array,
        required: true,
    },
})

const emits = defineEmits(['optionSelected'])

function toggleOptionSelect(option){
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
    transition: all .3s ease;
}

.slide-fade-leave-active {
    transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}

</style>