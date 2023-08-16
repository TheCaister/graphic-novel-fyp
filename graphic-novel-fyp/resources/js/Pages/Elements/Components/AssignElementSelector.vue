<template>
    <h1>
        Here is a nice list of elements
    </h1>

    <div v-if="elements.length === 0">
        <p>
            No elements found
        </p>
    </div>

    <div v-else>
        <ul>
            <li v-for="element in elements">
                {{ element.element_id }}
            </li>
        </ul>
    </div>

    <div>
        <Link :href="route('elements.create')">
            Add new element
        </Link>
    </div>
</template>

<script>
    export default {
        props: {
            elements: {
                type: Array,
                required: true
            }
        }
    }
</script>