<template>
    <label class="font-bold text-xl md:text-2xl">{{ label }}</label>
    <input 
    v-bind="$attrs"
    :placeholder="label" class="border-2 border-black rounded-md p-2" :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)">
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
