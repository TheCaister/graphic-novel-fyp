<template>
    <Link Link :href='props.link'>

        <div class="rounded-lg bg-white p-4 ">
            <div class="relative rounded-lg">
                <div class="h-full flex items-center">
                    <div class="h-64 w-full flex justify-center rounded-lg ">
                        <img v-if="content.thumbnail" :src="content.thumbnail" alt="Content Image"
                            class="rounded-lg object-cover w-full mb-4 shadow-lg hover:shadow-black transition-all duration-200" />
                        <div v-else class="text-white text-xl flex items-center">U{{ content.content_id }}</div>
                    </div>
                </div>
                <button class="absolute top-0 right-0 text-white text-2xl mt-4 mr-4">
                    <span @click.prevent="switchSelectedContent(content.content_id);"
                        class="material-symbols-outlined text-pink-300 drop-shadow-[-2px_2px_0_rgba(0,0,0,1)]">
                        pending
                    </span>
                    <Transition name="fade">
                        <DashboardDropdownMenu v-if="selected && isCardMenuOpen" class="absolute z-40"
                            :events="dropDownMenuOptions" @menuItemClick="handleMenuItemClicked"
                            @closeMenu="isCardMenuOpen = false" />
                    </Transition>
                </button>
            </div>
            <div class="text-gray-800 flex flex-col ">
                <div class="text-sm font-thin text-gray-500">
                    {{ content.subheading }}
                </div>
                <div class="flex items-center">{{ content.content_name }}</div>
                <div v-if="props.showDescription === true" class="text-base h-[6rem] font-thin overflow-hidden text-ellipsis">
                    {{ content.description }}
                </div>
            </div>
        </div>
    </Link>
</template>

<script setup>
import { ref, defineEmits } from 'vue';
import DashboardDropdownMenu from './DashboardDropdownMenu.vue'

const emits = defineEmits(['switchSelectedContent', 'menuItemClick']);

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
    dropDownMenuOptions: {
        type: Array,
    },
    showDescription: {
        type: Boolean,
        default: true,
    }
})

const isCardMenuOpen = ref(false)

function switchSelectedContent(contentId) {
    emits('switchSelectedContent', contentId);
    isCardMenuOpen.value = true;
}

function handleMenuItemClicked(event) {
    emits('menuItemClick', event);
}

</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s, transform 0.2s;
}

.fade-enter-from {
    opacity: 0;
    transform: translateY(-20px);
}

.fade-leave-to {
    opacity: 0;
    transform: translateY(-20px);
}

.fade-enter-to {
    opacity: 1;
    transform: translateY(0);
}

.material-symbols-outlined {
    font-variation-settings:
        'FILL' 0,
        'wght' 500,
        'GRAD' 0,
        'opsz' 40;
    font-size: 42px;
}
</style>
