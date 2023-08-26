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
                <Link :href="route('elements.show', element.element_id)">
                     {{ element.element_id }}

                </Link>
            </li>
        </ul>
    </div>

    <div>
        <Link :href="route('elements.create', {
            contentType: contentType,
            contentId: contentId
        })">
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
        },
        contentType: {
            type: String,
            required: true
        },
        contentId: {
            type: Number,
            required: true
        }
    }
}
</script>