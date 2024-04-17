<template>
    <div class="overflow-auto mt-8">

        <!-- <div v-for="content in contentModel" :key="content.content_id" class="flex relative items-center">
            <Checkbox class="flex-grow" :content="content" v-model="content.checked" />
            <Link :href="route('elements.assign', { contentType: content.type, content_id: content.content_id })"
                v-if="content.type !== 'Page'" class="absolute right-16 text-2xl"
                :class="{ 'text-black': content.checked === true }">
            >>
            </Link>
        </div> -->

        <div v-if="contentModel.length > 0">
            <div v-if="contentModel[0].type !== 'Page'">
                <div v-for="content in contentModel" :key="content.content_id" class="flex relative items-center">
                    <Checkbox class="flex-grow" :content="content" v-model="content.checked" />
                    <Link :href="route('elements.assign', { contentType: content.type, content_id: content.content_id })"
                        class="absolute right-16 text-2xl"
                        :class="{ 'text-black': content.checked === true }">
                    <p class="bg-gray-900 p-2 rounded-lg text-white border-2 hover:bg-gray-700">>></p>
                    </Link>
                </div>
            </div>
            <div v-else class="flex flex-col flex-wrap gap-4 w-full justify-between">
                <div v-for="content in contentModel" :key="content.content_id" >
                    <PageCheckbox :content="content" v-model="content.checked"
                    />
                </div>
            </div>
        </div>
        <div class="flex justify-center" v-else>
            No content.
        </div>
    </div>
</template>

<script setup>
import Checkbox from './Checkbox.vue';
import PageCheckbox from './PageCheckbox.vue';

const contentModel = defineModel()
</script>
