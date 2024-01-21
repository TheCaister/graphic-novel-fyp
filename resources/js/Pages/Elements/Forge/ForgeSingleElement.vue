<template>
    <!-- Display element -->
    <div>
        <div>
            <p>
                {{ element.element_id }}
            </p>
        </div>

        <!-- Two buttons for edit and delete -->
        <div>
            <Link :href="route('elements.edit', element.element_id)">
            Edit
            </Link>
            <form>
                <button type="submit">
                    Delete
                </button>
            </form>
        </div>

        <!-- Button that toggles between hidden and not hidden -->
        <Transition name="fade">
            <div v-if="element.hidden">
                <button type="submit">
                    Unhide
                </button>
            </div>
            <div v-else>
                <button type="submit">
                    Hide
                </button>
            </div>
        </Transition>
    </div>
</template>

<script>
export default {
    props: {
        element: {
            type: Object,
            required: true
        }
    }
}
</script>