<!-- vue template -->
<template>
    <div class="flex">
        <div class="p-4">
            <img src="" alt="">
        </div>
        <div class="p-4 text-white">
            <p>Username: {{ user.username }}</p>
            <p>Bio: {{ user.bio }}</p>
            <p>Number of series: {{ user.series_count }}</p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        user: {
            type: Object,
        },
    },
}
</script>