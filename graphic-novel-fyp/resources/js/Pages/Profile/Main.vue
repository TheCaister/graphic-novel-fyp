<!-- vue boilerplate -->
<template>
    <div class="flex justify-center space-x-10">

        <button @click='currentTabComponent = "Dashboard"'
            :class="{ 'font-bold underline': currentTabComponent === 'Dashboard' }">
            Dashboard
        </button>

        <button @click='currentTabComponent = "Comments"'
            :class="{ 'font-bold underline': currentTabComponent === 'Comments' }">
            Comments
        </button>

        <button @click='currentTabComponent = "Following"'
            :class="{ 'font-bold underline': currentTabComponent === 'Following' }">
            Following
        </button>

        <button @click='currentTabComponent = "Show"' :class="{ 'font-bold underline': currentTabComponent === 'Show' }">
            Profile Page
        </button>

        <button @click='currentTabComponent = "Edit"'
            :class="{ 'font-bold underline': currentTabComponent === 'Edit' }">
            Account Settings
        </button>

    </div>

    <component :is='currentTabComponent' v-bind="currentProperties"></component>
</template>

<script>
import NavLink from '@/Shared/NavLink.vue';
import Dashboard from '../Dashboard.vue';
import Comments from './Comments.vue';
import Show from './Components/Show.vue';
import Following from './Components/Following.vue';
import AccountSettings from './Components/AccountSettings.vue';
import Edit from './Edit.vue';

export default {
    props: {
        series: {
            type: Array,
        },
        user: {
            type: Object,
        },
    },

    components: {
        NavLink,
        Dashboard,
        Comments,
        Show,
        Following,
        AccountSettings,
        Edit,
    },

    data() {
        return {
            currentTabComponent: 'Dashboard',
        }
    },

    computed: {
        currentProperties() {
            if(this.currentTabComponent === 'Show') {
                return {
                    user: this.user,
                    follower_count: this.follower_count,
                }
            }
        }
    },
}
</script>