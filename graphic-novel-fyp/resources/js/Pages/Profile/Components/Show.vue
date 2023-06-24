<!-- vue template -->
<template>
    <!-- Make a div that contains the user's profile picture, along with their username, bio, number of series and followers, and a follow button -->
    <div class="flex">
        <div class="p-4">
            <img src="" alt="">
        </div>
        <div class="p-4">
            <p>Username: {{ user.username }}</p>
            <p>Bio: {{ user.bio }}</p>
            <p>Number of series: {{ user.series_count }}</p>
            <p>Number of followers: {{ follower_count }}</p>
            <button>Follow</button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        user: {
            type: Object,
        },
        follower_count: {
            type: Number,
        },
    },
}
</script>