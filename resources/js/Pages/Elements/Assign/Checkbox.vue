<template>
    <div class="flex h-24 items-center">
        <button @click="toggleChecked" class="flex items-center border border-pink-500 rounded-md justify-between">

            <div class="flex items-center">

                <img :src="content.thumbnail ? content.thumbnail : '/assets/black_page.jpg'"
                    alt="" class="rounded-full w-16 h-16">
                <!-- label -->
                <div>
                    {{ content.content_name }}
                </div>
            </div>

            <span v-if="checked" class="material-symbols-outlined dark">
                check_circle
            </span>
        </button>
        <Link :href="route('elements.assign', { contentType: content.type, content_id: content.content_id })" v-if="content.type !== 'Page'">
            >>
        </Link>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { defineProps, ref, defineEmits } from 'vue'

const emits = defineEmits(['checked'])

const props = defineProps({
    content:
    {
        type: Object,
        required: true
    }
})

const checked = ref(false)

function toggleChecked() {
    console.log(props.content)
    checked.value = !checked.value
    emits('checked', checked.value)
}

</script>