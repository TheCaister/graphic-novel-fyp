<!-- Boilerplate -->
<template>
    <div class="avatar">
        <Link :link="link">
            <img :src="src" :alt="alt" :title="title" class="rounded-full h-10 w-10 object-cover" />
        </Link>

    </div>
</template>

<script>


export default {
    props: {
        src: {
            type: String,
            required: true,
        },
        alt: {
            type: String,
            required: false,
            default: '',
        },
        title: {
            type: String,
            required: false,
            default: '',
        },
        link: {
            type: String,
            required: false,
            default: '',
        },
    },
}
</script>