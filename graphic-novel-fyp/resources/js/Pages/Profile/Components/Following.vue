<template>
    <!-- Load all followings if there are any -->
    <div v-if="followingLoaded">
        <div v-for="following in followings" :key="following.followers_id">
            <h1 class="text-3xl font-bold mb-2">
                <Link :href="`/profile/${following.id}`">{{ following.username }}</Link>
            </h1>

            <!-- Show number of series and followers -->
            <div class="text-sm text-gray-500">
                {{ following.series_count }} series
            </div>
            <div class="text-sm text-gray-500">
                {{ following.followers_count }} followers
            </div>

        </div>
    </div>
</template>
    
<script>


export default {

    data() {
        return {
            followings: [],
            followingLoaded: false,
        }
    },

    mounted() {
        axios.get('/api/followings', {
            params: {
                user_id: this.$parent.$parent.$attrs.auth.user.id,
            }
        }).then(response => {
            this.followings = response.data
            this.followingLoaded = true

            console.log(this.following)
        }).catch(error => console.log(error))
    },
}
</script>