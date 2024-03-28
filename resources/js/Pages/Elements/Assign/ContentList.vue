<template>
    <!-- <h1>
        CONTENT
    </h1> -->

    <div class="overflow-auto mt-8">

        <div v-for="content in contentModel" :key="content.content_id" class="flex relative items-center">
            <Checkbox class="flex-grow" :content="content" v-model="content.checked" />
            <Link :href="route('elements.assign', { contentType: content.type, content_id: content.content_id })"
                v-if="content.type !== 'Page'" class="absolute right-16 text-2xl"
                :class="{ 'text-black': content.checked === true }">
            >>
            </Link>
        </div>

    </div>
</template>

<script setup>
import Checkbox from './Checkbox.vue';

const contentModel = defineModel()
</script>
