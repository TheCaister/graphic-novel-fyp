<template>
    <h1>
        Here is a list of all the elements:
    </h1>

    <div class="h-96 overflow-auto">
        <ul>
            <li v-for="element in elements" :key="element.element_id">
                <div>
                    <input type="checkbox" :id="'element-' + element.element_id" :value="element.element_id">
                    <label :for="'element-' + element.element_id">{{ element.element_name }}</label>
                </div>
            </li>
        </ul>
    </div>

</template>

<script setup>
    import {defineProps, ref} from 'vue'

    const props = defineProps({
        elementList: {
            type: Array,
            required: true
        },
        preSelectedElementIds: {
            type: Array,
            required: true
        }
    })

    const elements = ref(props.elementList)
</script>