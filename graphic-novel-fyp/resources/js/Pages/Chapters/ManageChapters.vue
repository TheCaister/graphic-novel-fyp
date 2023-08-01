<template>
    <!-- Display every chapter -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        <div v-for="chapter in chapters">
            <div class="relative flex flex-col items-center justify-center bg-gray-200 rounded-md p-5" :key="chapter.chapter_id">
                <div class="flex flex-col absolute top-0 right-0 p-2">
                    <Link :href="`/chapters/${chapter.chapter_id}/edit`">
                        <PrimaryButton>Edit Chapter</PrimaryButton>
                    </Link>
                    
                    <!-- Make button to send delete request -->
                    <PrimaryButton>Delete Chapter</PrimaryButton>
                </div>

                <div class="flex flex-col items-center">
                    <p class="text-2xl font-bold">Chapter {{ chapter.chapter_number }}</p>
                    <p class="text-lg font-semibold">{{ chapter.chapter_title }}</p>
                </div>

                <div class="flex flex-col items-center gap-5">
                    <img :src="chapter.chapter_thumbnail" :alt="chapter.chapter_thumbnail" class="rounded-md shadow-md" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        // chapter is an object
        chapters: {
            type: Array,
        },
    },
}
</script>