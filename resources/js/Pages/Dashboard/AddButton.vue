<template>
    <div class="mx-8">
        <div class="w-full h-64 flex items-center justify-center rounded-lg">

            <button>
                <span class=" material-symbols-outlined transition-all duration-300 hover:scale-105"
                    style="font-size: 10rem; font-variation-settings: 'wght' 100; color: #f9a8d4;">
                    add_circle
                </span>

                <p class="text-white pt-4 text-center">{{ props.label }}</p>
            </button>
        </div>
    </div>
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