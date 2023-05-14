<template>
    <Head title="Create User" />

    <h1>Create New User</h1>

    <!-- You don't need this -->
    <!-- <form method="post" action="/"> -->

    <!-- @submit.prevent - prevent the default action, set the alternative action to do -->
    <form @submit.prevent="submit">
        <div>
            <label for="name">Name</label>
            <input v-model="form.name" type="text" name="name" id="name" />
            <div v-if="errors.name" v-text="errors.name"></div>
        </div>

        <div>
            <label for="email">Email</label>
            <input v-model="form.email" type="email" name="email" id="email" />
            <div v-if="errors.email" v-text="errors.email"></div>

        </div>

        <div>
            <label for="password">Password</label>
            <input v-model="form.password" type="password" name="password" id="password" />
            <div v-if="errors.password" v-text="errors.password"></div>

        </div>

        <button type="submit" class="bg-blue-400 text-white rounded py-2 px-4">Create</button>
    </form>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { reactive } from 'vue';
// import { Inertia } from '@inertiajs/inertia';
import { router } from '@inertiajs/vue3';

defineProps({
    errors: Object,
});

// This form is reactive, so it will update automatically
let form = reactive({
    name: '',
    email: '',
    password: '',
});

let submit = () => {
    // Inertia.post();
    router.post('/users', form);
};

</script>