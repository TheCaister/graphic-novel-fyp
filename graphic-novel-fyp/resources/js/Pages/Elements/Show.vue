<template>

    <h1>Element</h1>

    <!-- Created at... -->
    <p>Created at: {{ element.created_at }}</p>

    <!-- Owner id -->
    <p>Owner id: {{ element.owner_id }}</p>

    <div>
        <p>{{ element.content }}</p>
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