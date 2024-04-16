<template>
    <Link Link :href='props.link'>

    <div class="rounded-lg bg-white p-4 card relative">
        <div class="relative rounded-lg">
            <div class="h-full flex items-center">
                <div class="h-64 w-full flex justify-center rounded-lg ">
                    <img v-if="content.thumbnail" :src="content.thumbnail" alt="Content Image"
                        class="rounded-lg object-cover w-full shadow-lg" />
                    <div v-else
                        class="text-white text-xl flex items-center bg-pink-300 w-full rounded-lg justify-center">
                        {{ content.content_name }}</div>
                </div>
            </div>
            <div v-if="props.showTag === true" class="absolute top-0 left-0 text-white mt-4 ml-4">
                <span class="bg-pink-500 p-2 rounded-full shadow-lg">
                    {{content.subheading}}
                </span>
            </div>
        </div>
        <div class="text-gray-800 flex flex-col mb-8">
            <div class="flex items-center">{{ content.content_name }}</div>
            <div v-if="props.showDescription === true"
                class="text-base h-[6rem] font-thin overflow-hidden text-ellipsis">
                {{ content.description }}
            </div>
        </div>

        <button class="absolute top-0 right-0 text-white text-2xl mt-4 mr-4">
                <span @click.prevent="switchSelectedContent(content.content_id);"
                    class="material-symbols-outlined text-pink-300 drop-shadow-[-2px_2px_0_rgba(0,0,0,1)]">
                    pending
                </span>
                <Teleport v-if="isMounted" to="#searchPosition">

                    <Transition name="fade">
                        <DashboardDropdownMenu v-if="selected && isCardMenuOpen" class="absolute z-50"
                            :events="dropDownMenuOptions" @menuItemClick="handleMenuItemClicked"
                            @closeMenu="isCardMenuOpen = false" />
                    </Transition>
                </Teleport>

            </button>
    </div>
    </Link>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import DashboardDropdownMenu from '../Dashboard/DashboardDropdownMenu.vue';

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
    showDescription: {
        type: Boolean,
        default: true,
    },
    dropDownMenuOptions: {
        type: Array,
    },
    showTag: {
        type: Boolean,
        default: true,
    },
})

const isCardMenuOpen = ref(false)
const isMounted = ref(false)

function switchSelectedContent(contentId) {
    emits('switchSelectedContent', contentId);
    isCardMenuOpen.value = true;
}

function handleMenuItemClicked(event) {
    emits('menuItemClick', event);
}

onMounted(() => {
    isMounted.value = true
})

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

.card {
    transition: box-shadow 100ms ease-in-out,
        transform 200ms ease-in-out;
}

.card:hover {
    /* box-shadow: -8px 8px 0px rgb(255, 255, 255); */
    transform: scale(1.02);
}
</style>
