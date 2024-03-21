<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref, onMounted, defineProps, computed } from 'vue'

import vueFilePond from 'vue-filepond';

import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';

import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';
import axios from 'axios';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview,
);

const user = usePage().props.auth.user;

const form = useForm({
    username: user.username,
    email: user.email,
    bio: user.bio,
    upload: ''
});

function submit() {
    form.patch(route('profile.update'))
}

let csrfToken = document.querySelector('meta[name="csrf-token"]').content

function handleFilePondThumbnailProcess(error, file) {
    form.upload = file.serverId;
}

function handleFilePondThumbnailRemove(error, file) {
    form.upload = '';
}

function deleteTempThumbnail() {

    if (form.upload) {

        axios.delete(route('delete-thumbnail', {
            isTemp: "true",
            contentType: "User",
            serverId: form.upload,
        })).catch(error => {
            console.log(error);
        });
    }
}

function deleteExistingThumbnail() {
    if (user.profile_picture !== '') {
        axios.delete(route('delete-thumbnail', {
            isTemp: "false",
            contentType: "User",
            contentId: user.id,
        })).catch(error => {
            console.log(error);
        });
    }
}

const files = computed(() => {
    if (user.profile_picture !== '' && user.profile_picture) {
        return [
            {
                source: user.profile_picture.replace('http://localhost', ''),
                options: {
                    type: 'local',
                },
            },
        ]
    }
    return [];
})
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Profile Information</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Update your account's profile information and email address.
            </p>
        </header>

        <div>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Profile Picture</h2>


            <file-pond name="upload" label-idle="Profile Picture" accepted-file-types="image/jpeg, image/png"
                :files="files" @processfile="handleFilePondThumbnailProcess" @removefile="handleFilePondThumbnailRemove"
                :server="{
                    process: {
                        url: '/upload?media=profile_picture',
                    },
                    revert: {
                        url: '/api/thumbnail?contentType=User&serverId=' + form.upload + '&isTemp=true',
                    },
                    load: {
                        url: '/',
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    remove: (source, load, error) => {
                        deleteExistingThumbnail();

                        load();
                    }
                }" />
        </div>

        <form @submit.prevent="submit" class="mt-6 space-y-6">
            <div>
                <InputLabel for="username" value="Username" />

                <TextInput id="username" type="text" class="mt-1 block w-full" v-model="form.username" required autofocus
                    autocomplete="username" />

                <InputError class="mt-2" :message="form.errors.username" />
            </div>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                    autocomplete="username" />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                    Your email address is unverified.
                    <Link :href="route('verification.send')" method="post" as="button"
                        class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                    Click here to re-send the verification email.
                    </Link>
                </p>

                <div v-show="status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                    A new verification link has been sent to your email address.
                </div>
            </div>


            <div>
                <InputLabel for="email" value="Email" />

                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                    autocomplete="username" />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="bio" value="Bio" />

                <!-- <textarea id="bio" class="mt-1 block w-full" v-model="form.bio" required autocomplete="bio"></textarea> -->

                <TextAreaInput id="bio" class="mt-1 block w-full" v-model="form.bio" required autocomplete="bio"></TextAreaInput>

                <InputError class="mt-2" :message="form.errors.bio" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
