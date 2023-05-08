<template>
    <!-- href automatically passed through, component inherits attributes not registered as props -->
    <Link class="text-blue-500 hover:underline" :class="{ 'font-bold underline': active }">
    <slot />
    </Link>
</template>

<script>


export default {


    props: {
        active: {
            type: Boolean,
        },
    },
};
</script>


