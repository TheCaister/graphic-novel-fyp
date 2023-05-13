<!-- Shared pagination asset -->

<template>
    <!-- Going through each link in paginator -->
    <!-- Pulling URL from each link by accessing link.url -->
    <!-- If the URL doesn't exist, just turn it into a span -->
    <!-- Component allows you to change the element using :is -->
    <Component :is="link.url ? 'Link' : 'span'" v-for="link in links" :href="link.url" v-html="link.label"
        class="px-3" />
</template>

<script>
export default {
    props: {
        links: Array,
    },
}
</script>