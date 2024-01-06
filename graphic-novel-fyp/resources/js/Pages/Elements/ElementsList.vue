<template>
    <h1>
        Here is a list of all the elements:
    </h1>

    <div>
        <ul>
            <li v-for="element in elements" :key="element.element_id">
                <Link :href="route('elements.edit', element.element_id)">
                    {{ element.content }}
                </Link>
            </li>
        </ul>
    </div>

</template>

<script>
    export default{
        props: {
            elements: {
                type: Array,
                required: true
            }
        }
    }
</script>