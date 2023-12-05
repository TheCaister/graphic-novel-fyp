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
import APICalls from '@/Utilities/APICalls'
import { usePage } from '@inertiajs/vue3'

export default {

    data() {
        return {
            followings: [],
            followingLoaded: false,
        }
    },

    mounted() {

        const page = usePage()
        const authId = page.props.auth.user.id
    },
}
</script>