<template>
    <div class="relative">
        <Link :href='route("universes.show", universe.universe_id)' class="h-full flex items-center">
        <div class="h-64 w-full bg-pink-300 flex justify-center rounded-lg">
            <img v-if="universe.thumbnail" :src="universe.thumbnail" alt="Universe Image"
                class="w-full h-full rounded-lg" />
            <div v-else class="text-white text-xl flex items-center">U{{ universe.universe_id }}</div>
        </div>
        </Link>
        <!-- Create a button on the top right corner -->
        <button class="absolute top-0 right-0 text-white text-2xl mt-4 mr-4">
            <span @click="switchSelectedContent(universe.universe_id);" class="material-symbols-outlined dark">
                pending
            </span>
            <Transition name="fade">
                <DashboardDropdownMenu v-if="selectedUniverse.universe_id === universe.universe_id && isCardMenuOpen"
                    class="absolute z-40" :events="dropDownMenuOptions" @menuItemClick="handleMenuItemClicked"
                    @closeMenu="isCardMenuOpen = false" />
            </Transition>

        </button>
    </div>
</template>

<script setup>

</script>
