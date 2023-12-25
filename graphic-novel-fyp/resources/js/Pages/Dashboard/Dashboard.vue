<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import CreateUniverseModal from './CreateUniverseModal.vue';
import UniverseView from './Universe/UniverseView.vue';
import { ref } from 'vue'


defineProps({
    name: {
        type: String,
    },
    contentType: {
        type: String,
    },
    contentId: {
        type: Number,
    },
});

const isOpen = ref(false)
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex justify-between mb-8">

        <div>
            <PrimaryButton>
                Go back
            </PrimaryButton>

            <PrimaryButton>
                Universe 1
            </PrimaryButton>
        </div>

        <div>
            <span class="material-symbols-outlined dark">
                list
            </span>

            <span class="material-symbols-outlined dark">
                grid_view
            </span>

            <PrimaryButton>
                Dropdown for view type
            </PrimaryButton>
        </div>
    </div>

    <div class="flex flex-wrap relative">

        <UniverseView />

        <button @click="isOpen = true" class="bg-black rounded-lg shadow-md w-2/5 mx-8">
            <div class="w-full h-64 flex items-center justify-center rounded-lg">

                <span class="material-symbols-outlined dark"
                    style="font-size: 10rem; font-variation-settings: 'wght' 100; color: #f9a8d4;">
                    add_circle
                </span>
            </div>
            <p class="text-white pt-4 text-center">Create Universe</p>
        </button>

        <Teleport to="body">
            <Transition name="modal">
                <create-universe-modal v-if="isOpen" @closeModal="isOpen = false"
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60" />
            </Transition>

        </Teleport>

    </div>
</template>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: all 0.25s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
    transform: scale(1.1);
}
</style>
```
