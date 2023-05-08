<template>
    <!-- href automatically passed through, component inherits attributes not registered as props -->
    <Link class="text-blue-500 hover:underline" :class="{ 'font-bold underline': active }">
    <slot />
    </Link>
</template>

<script>
import { Link } from '@inertiajs/vue3';


export default {

    components: {
        Link,
    },

    props: {
        active: {
            type: Boolean,
        },
    },
};
</script>