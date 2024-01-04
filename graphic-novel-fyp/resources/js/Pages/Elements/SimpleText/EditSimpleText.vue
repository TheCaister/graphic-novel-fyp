<template>
<SimpleEditor v-model="form.content" class="bg-white" />
</template>

<script setup>
import SimpleEditor from '@/Components/Editors/SimpleEditor.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
});
</script>