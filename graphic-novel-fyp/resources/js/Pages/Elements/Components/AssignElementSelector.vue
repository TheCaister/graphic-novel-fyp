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
                <div class="flex">
                    <input type="checkbox" :id="element.element_id" :name="element.element_id" :value="element.element_id">
                    <label :for="element.element_id">
                        {{ element.element_id }}
                        <img src="" alt="Page image">
                    </label>

                    <Link :href="route('elements.show', element.element_id)">
                    >
                    </Link>
                </div>

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