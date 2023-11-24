<template>
    <h1>Element</h1>

    <!-- Created at... -->
    <p>Created at: {{ new Date(element.created_at) }}</p>

    <!-- Owner id -->
    <p>Owner id: {{ element.owner_id }}</p>

    <div>
        <p>{{ element.content }}</p>
    </div>

    <div>
        {{ allContent() }}
    </div>

    <!-- Edit button -->
    <div>
        <Link :href="route('elements.edit', element.element_id)">
        <PrimaryButton>Edit</PrimaryButton>
        </Link>

        <PrimaryButton @click="destroy(element.element_id)"
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</PrimaryButton>

        <Link href="#" @click="back">
            <PrimaryButton>Back</PrimaryButton>
        </Link>
    </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia'

export default {
    props: {
        element: {
            type: Object,
            required: true
        },
        universes: {
            type: Array,
            default: []
        },
        series: {
            type: Array,
            default: []
        },
        chapters: {
            type: Array,
            default: []
        },
        pages: {
            type: Array,
            default: []
        },
    },

    methods: {
        allContent() {
            return {
                universe: this.universes,
                series: this.series,
                chapters: this.chapters,
                pages: this.pages,
            }
        },

        destroy(element_id) {
            if (confirm('Are you sure you want to delete this element?')) {
                Inertia.delete(route('elements.destroy', element_id))
            }
        },
        back() {
            window.history.back();
        },
    }
}
</script>