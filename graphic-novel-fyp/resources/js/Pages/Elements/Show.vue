<template>

    <h1>Element</h1>

    <!-- Created at... -->
    <p>Created at: {{ new Date(element.created_at) }}</p>

    <!-- Owner id -->
    <p>Owner id: {{ element.owner_id }}</p>

    <div>
        <p>{{ element.content }}</p>
    </div>

    <!-- Edit button -->
    <div>
        <Link :href="route('elements.edit', element.element_id)">
            <PrimaryButton>Edit</PrimaryButton>
        </Link>
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