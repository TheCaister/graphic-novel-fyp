<!-- vue boilerplate -->
<template>
    <div class="flex justify-center space-x-10">

        <Link href="/user/main/dashboard">
        <button @click='currentTabComponent = "Dashboard"'
            :class="{ 'font-bold underline': currentTabComponent === 'Dashboard' }">
            Dashboard
        </button>
        </Link>
        <Link href="/user/main/show">
        <button @click='currentTabComponent = "Show"' :class="{ 'font-bold underline': currentTabComponent === 'Show' }">
            Show
        </button>
        </Link>

        <Link href="/user/main/edit">
        <button @click='currentTabComponent = "Edit"' :class="{ 'font-bold underline': currentTabComponent === 'Edit' }">
            Account Settings
        </button>
        </Link>

        <Link href="/user/main/elementsforge">
        <button>
            Elements Forge</button>
        </Link>


    </div>

    <component :is='currentTabComponent' v-bind="currentProperties"></component>
</template>

<script>
import NavLink from '@/Shared/NavLink.vue';
import Dashboard from '../Dashboard.vue';
import Show from './Components/Show.vue';
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
        passed_tab: {
            type: String,
        },
    },

    components: {
        NavLink,
        Dashboard,
        Show,
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
            if (this.currentTabComponent === 'Show') {
                return {
                    user: this.user,
                }
            }
        }
    },

    mounted() {
        if (this.passed_tab) {
            this.currentTabComponent = this.passed_tab;
        }
    },
}
</script>