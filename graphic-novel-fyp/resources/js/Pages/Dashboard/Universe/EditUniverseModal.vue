<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { onClickOutside } from '@vueuse/core'
import { ref, onMounted, defineProps, computed } from 'vue'

import { useForm } from '@inertiajs/vue3';

import vueFilePond from 'vue-filepond';

import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';

import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';
import axios from 'axios';

const emit = defineEmits(['closeModal'])
function close() {
    deleteMedia();
    emit('closeModal');
};

const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview,
);

const modal = ref(null)

const props = defineProps({
    universe: {
        type: Object,
    },
})


const form = useForm({
    universe_name: '',
    upload: '',
});

onClickOutside(modal, () => {
    close()
})

function submit() {
    form.put(route('universes.update', props.universe.universe_id), {
        onFinish: () => {
            form.upload = '';
            close()
        }
    });
};

let csrfToken = document.querySelector('meta[name="csrf-token"]').content

function handleFilePondThumbnailProcess(error, file) {
    form.upload = file.serverId;
}

function handleFilePondThumbnailRemove(error, file) {
    form.upload = '';
}

function deleteMedia() {

    if (form.upload) {
        axios.delete('/api/universes/' + form.upload + '/thumbnail').catch(error => {
            console.log(error);
        });
    }
}

function deleteExistingThumbnail() {
    if (props.universe.media && props.universe.media.length > 0) {
        axios.delete('/api/universes/-1/thumbnail', {
            params: {
                isTemp: "false",
                universeId: props.universe.universe_id,
            }
        }).catch(error => {
            console.log(error);
        });
    }
}

onMounted(() => {
    form.universe_name = props.universe.universe_name
})

const files = computed(() => {

    if (props.universe.media && props.universe.media.length > 0) {

        return [
            {
                source: props.universe.thumbnail.replace('http://localhost', ''),
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
    <div>
        <div ref="modal" class="text-lg bg-black shadow-lg rounded-lg p-8 w-4/5">
            <h2 class="text-4xl font-bold text-white ">Edit Universe</h2>
            <form @submit.prevent="submit">
                <div class="flex">

                    <div class="w-1/2">
                        <Label>Universe Thumbnail</Label>

                        <file-pond name="upload" label-idle="Universe Thumbnail" accepted-file-types="image/jpeg, image/png"
                            :files="files" @processfile="handleFilePondThumbnailProcess"
                            @removefile="handleFilePondThumbnailRemove" :server="{
                                process: {
                                    url: '/upload?media=universe_thumbnail',
                                },
                                revert: {
                                    url: '/api/universes/' + form.upload + '/thumbnail?isTemp=true',
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
                    <div class="flex flex-col justify-between w-1/2 ml-8">
                        <div>
                            <div>
                                <InputLabel for="universe_name" value="Universe name:" />
                                <TextInput id="universe_name" type="text" class="mt-1 block w-full"
                                    v-model="form.universe_name" required autofocus />
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
                            <PrimaryButton type="submit"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Save
                            </PrimaryButton>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</template>