<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('user.resetPassword'));
};
</script>

<script>
// Set the layout of the page to null
export default {
    layout: null,
};
</script>


<template>
    <Head title="Forgot Password" />
    <GuestLayout>



        <template v-slot:form>
            <h1 class="text-4xl font-bold mb-8 text-white">Reset password</h1>
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                Reset your password here.
            </div>
            <div v-if="status" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ status }}
            </div>
           
            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" value="Email" />
                    <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus
                        autocomplete="email" />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>
                <div class="mt-4">
                    <InputLabel for="password" value="Password" />
                    <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required
                        autocomplete="new-password" />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>
                <div class="mt-4">
                    <InputLabel for="password_confirmation" value="Confirm Password" />
                    <TextInput id="password_confirmation" type="password" class="mt-1 block w-full"
                        v-model="form.password_confirmation" required autocomplete="new-password" />
                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                </div>
                <div class="flex items-center justify-end mt-4">
                    <Link :href="route('login')"
                        class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 pr-5">
                    Back to login
                    </Link>
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Reset Password
                    </PrimaryButton>
                </div>
            </form>
        </template>

        <template v-slot:image>
            <div class="h-screen"
                style="background-image: url('https://img.freepik.com/free-vector/hand-drawing-illustration-safety-concept_53876-15586.jpg?size=626&ext=jpg&ga=GA1.1.576194574.1703272769&semt=ais');">
            </div>
        </template>
    </GuestLayout>
</template>
