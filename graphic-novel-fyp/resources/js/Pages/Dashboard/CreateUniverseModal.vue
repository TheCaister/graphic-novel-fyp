<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { onClickOutside } from '@vueuse/core'
import { ref } from 'vue'

import { useForm } from '@inertiajs/vue3';

import vueFilePond from 'vue-filepond';

import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';

import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';



const emit = defineEmits(['closeModal'])
function close() {
    emit('closeModal');
};

const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview,
);

const modal = ref(null)

const form = useForm({
            universe_name: '',
            // universe_thumbnail: '',
        });

onClickOutside(modal, () => {
    close()
})

function submit() {
    form.post(route('universes.store'), {
        onFinish: () => {
            console.log(form.universe_name)
            close()}
    });
};
</script>


<template>
    <div>
        <div ref="modal" class="text-lg bg-black shadow-lg rounded-lg p-8 w-4/5">
            <h2 class="text-4xl font-bold text-white ">Create Universe</h2>
            <form @submit.prevent="form.post(route('universes.store'))">
                <div class="flex">

                    <div class="w-1/2">
                        <!-- <img src="https://media.gettyimages.com/id/1428545051/photo/chureito-pagoda-and-mt-fuji-at-sunset.jpg?s=612x612&w=gi&k=20&c=rocg5X4hPst-eOzmceAG3qOr5WV03JRhZzzqjy5qMv8="
                            alt=""> -->

                        <Label>Universe Thumbnail</Label>
                        <!-- <ImageLabel /> -->

                        <file-pond name="upload" label-idle="Universe Thumbnail" accepted-file-types="image/jpeg, image/png"
                            @processfile="handleFilePondThumbnailProcess" @removefile="handleFilePondThumbnailRemove"
                            :server="{
                                process: {
                                    url: '/upload?media=series_thumbnail',
                                },
                                revert: {
                                    url: '/api/series/' + this.form.upload + '/thumbnail',
                                },
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            }" />
                    </div>
                    <div class="flex flex-col justify-between w-1/2 ml-8">
                        <!-- <InputLabel for="email" value="Email" />
                        <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus
                            autocomplete="username" />
                        <InputError class="mt-2" :message="form.errors.email" /> -->
                        <div>
                            <div>
                                <InputLabel for="universe_name" value="Universe name:" />
                                <TextInput id="universe_name" type="text" class="mt-1 block w-full" v-model="form.universe_name" required autofocus />
                                <InputError class="mt-2" message="" />
                            </div>

                            <div>
                                <InputLabel for="admins" value="Invite admins (Optional):" />
                                <TextInput id="admins" type="text" class="mt-1 block w-full" autofocus />
                                <InputError class="mt-2" message="" />
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <!-- Button to cancel and button to create -->
                            <button @click="close" type="button"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </button>
                            <PrimaryButton type="submit"  @click="submit"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Create
                            </PrimaryButton>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</template>