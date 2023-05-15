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
            <div v-if="form.errors.name" v-text="form.errors.name"></div>
        </div>

        <div>
            <label for="email">Email</label>
            <input v-model="form.email" type="email" name="email" id="email" />
            <div v-if="form.errors.email" v-text="form.errors.email"></div>

        </div>

        <div>
            <label for="password">Password</label>
            <input v-model="form.password" type="password" name="password" id="password" />
            <div v-if="form.errors.password" v-text="form.errors.password"></div>

        </div>

        <button type="submit" class="bg-blue-400 text-white rounded py-2 px-4">Create</button>
    </form>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { reactive } from 'vue';
// import { Inertia } from '@inertiajs/inertia';
import { router } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';

defineProps({
    errors: Object,
});

// This form is reactive, so it will update automatically
// let form = reactive({
//     name: '',
//     email: '',
//     password: '',
// });

// useForm returns a form object with the same properties as the form above
// but it also has some methods and properties that we can use
let form = useForm({
    name: '',
    email: '',
    password: '',
});

let submit = () => {
    // Inertia.post();
    form.post('/users', form);
};

</script>