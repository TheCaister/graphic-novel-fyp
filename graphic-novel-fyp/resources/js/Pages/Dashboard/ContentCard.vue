<template>
    <div class="relative">
        <Link :href='props.link' class="h-full flex items-center">
        <div class="h-64 w-full bg-pink-300 flex justify-center rounded-lg">
            <img v-if="content.thumbnail" :src="content.thumbnail" alt="Content Image" class="w-full h-full rounded-lg" />
            <div v-else class="text-white text-xl flex items-center">U{{ content.content_id }}</div>
        </div>
        </Link>
        <!-- Create a button on the top right corner -->
        <button class="absolute top-0 right-0 text-white text-2xl mt-4 mr-4">
            <span @click="switchSelectedContent(content.content_id);" class="material-symbols-outlined dark">
                pending
            </span>
            <Transition name="fade">
                <DashboardDropdownMenu v-if="selected && isCardMenuOpen" class="absolute z-40" :events="dropDownMenuOptions"
                    @menuItemClick="handleMenuItemClicked" @closeMenu="isCardMenuOpen = false" />
            </Transition>

        </button>
    </div>
    <p class="text-white pt-4">{{ content.content_name }}</p>
</template>

<script setup>

const props = defineProps({
    content: {
        type: Object,
    },
    link: {
        type: String,
    },
    selected: {
        type: Boolean,
    },
})

</script>
