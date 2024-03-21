<template>
    <label class="font-bold text-xl md:text-2xl">{{ label }}</label>
    <textarea v-bind="$attrs" :placeholder="label" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"></textarea>
</template>

<script>
export default {
    props: {
        label: {
            type: String,
            default: ''
        },
        modelValue: {
            type: [String, Number],
            default: ''
        }
    }
}
</script>
