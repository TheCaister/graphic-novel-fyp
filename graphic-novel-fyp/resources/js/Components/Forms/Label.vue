<template>
    <label class="font-bold text-xl md:text-2xl" :for="forString">
        <slot />
    </label>
</template>

<script>
export default {
    props: {
        forString: {
            type: String,
            required: true
        }
    }
}
</script>