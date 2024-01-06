<template>

    <div class="bg-white">
        <!-- Content title here, with option  to go back... -->
        <div>

        </div>

        <!-- Two lists, one for content, one for elements -->
        <div>
            <div>

            </div>
            <div>
                <ElementsList :elements="{}" />
            </div>
        </div>

        <!-- Button to go back/save -->
        <div>
            <button>
                Back
            </button>

            <button>
                Save
            </button>
        </div>
    </div>

</template>

<script setup>
import ElementsList from './ElementsList.vue';

</script>