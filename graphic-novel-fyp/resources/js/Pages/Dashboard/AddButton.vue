<template>
    <button class="bg-black rounded-lg shadow-md w-2/5 mx-8">
        <div class="w-full h-64 flex items-center justify-center rounded-lg">

            <span class="material-symbols-outlined dark"
                style="font-size: 10rem; font-variation-settings: 'wght' 100; color: #f9a8d4;">
                add_circle
            </span>
        </div>
        <p class="text-white pt-4 text-center">{{ props.label }}</p>
    </button>
</template>

<script setup>

const props = defineProps(
    {
        label: {
            type: String,
        },  
    }
)
</script>