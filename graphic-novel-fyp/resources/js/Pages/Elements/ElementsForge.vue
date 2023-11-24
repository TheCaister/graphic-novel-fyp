<template>
    <!-- Display awesome title: Welcome to the Element Forge... with this as the background image: https://i.pinimg.com/originals/f0/05/28/f00528a9404e2959c3c5e5e440f1761a.png -->
    <div>
        <div class="bg-white shadow-lg rounded-lg p-10 flex flex-col gap-5">
            <div>
                <p class="font-bold text-2xl md:text-3xL">Welcome to the Element Forge</p>
            </div>
        </div>

        <div>
            <ElementSearch />
        </div>

        <!-- Display all universes -->
        <div class="flex flex-col gap-5">
            <div v-if="universes.length === 0">
                <!-- Button to create universe -->
                <Link :href="route('universes.create')">
                Create a universe
                </Link>
            </div>

            <div v-else>
                <ul>
                    <li v-for="universe in universes">
                        <div class="flex">
                            <Link :href="route('forge.show', {
                                contentType: 'universes',
                                contentId: universe.universe_id
                            })">
                            {{ universe.universe_id }}
                            </Link>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import ElementSearch from '@/Components/Search/ElementSearch.vue'

export default {
    props: {
        universes: {
            type: Array,
            required: true
        }
    },
    components: {
        ElementSearch
    }
}

</script>