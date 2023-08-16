<template>
    <h1>Here is a list of content to choose from</h1>

    <!-- Check if contentList is empty -->
    <div v-if="contentList.length === 0">
        <p>There is no content to display</p>
    </div>

    <div v-else>
        <div v-if="contentType === 'series'">
            <div v-for="content in contentList" :key="content.series_id">
                <input type="checkbox" :id="content.series_id" :name="content.series_title" :value="content.series_id">
                <label :for="content.series_id">
                    {{ content.series_title }}
                    <!-- Make an arrow that point to a link -->
                    <Link :href="`/elements/assign?type=series&content_id=${content.series_id}`">
                    >
                    </Link>

                    <img :src="content.series_thumbnail" alt="Series thumbnail">
                </label>


            </div>
        </div>

        <div v-if="contentType === 'chapters'">
            <h2>
                Series Title: {{ parentContent.series_title }}
            </h2>

            <Link :href="`/elements/assign?type=universes&content_id=${parentContent.universe_id}`">
            To Previous Series
            </Link>

            <div v-for="content in contentList" :key="content.chapter_id">
                <input type="checkbox" :id="content.chapter_id" :name="content.chapter_title" :value="content.chapter_id">
                <label :for="content.chapter_id">
                    {{ content.chapter_title }}
                    <Link :href="`/elements/assign?type=chapters&content_id=${content.chapter_id}`">
                    >
                    </Link>
                    <img :src="content.chapter_thumbnail" alt="Chapter thumbnail">
                </label>



            </div>

            <Link :href="`/elements/assign?type=universes&content_id=${parentContent.universe_id}`">
            To Previous Series
            </Link>
        </div>

        <div v-if="contentType === 'pages'">
            <h2>
                Chapter Title: {{ parentContent.chapter_title }}
            </h2>

            <Link :href="`/elements/assign?type=series&content_id=${parentContent.series_id}`">
            To Previous Chapters
            </Link>

            <div v-for="content in contentList" :key="content.page_id">
                <input type="checkbox" :id="content.page_id" :name="content.page_id" :value="content.page_id">
                <label :for="content.page_id">
                    {{ content.page_id }}
                    <img :src="content.page_image" alt="Page image">
                </label>


            </div>

            <Link :href="`/elements/assign?type=series&content_id=${parentContent.series_id}`">
            To Previous Chapters
            </Link>
        </div>

    </div>
</template>

<script>
export default {
    props: {
        parentContent: {
            type: Object,
            required: true
        },
        contentType: {
            type: String,
            required: true
        },
        contentList: {
            type: Array,
            required: true
        }
    }
}
</script>