<script setup>
import { onClickOutside } from '@vueuse/core'
import { ref, defineProps } from 'vue'

import { useForm } from '@inertiajs/vue3';

import vueFilePond from 'vue-filepond';

import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';

import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';



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

const form = useForm({
    chapter_id: '',
    upload: '',
});

const props = defineProps({
    parentContentIdNumber: {
        type: Number,
        required: true
    },
})

onClickOutside(modal, () => {
    close()
})

function submit() {

    form.chapter_id = props.parentContentIdNumber

    form.post(route('comic-pages.store'), {
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

    console.log(form.upload)

    if (form.upload) {
        axios.delete('/api/pages/' + form.upload).catch(error => {
            console.log(error);
        });
    }
}

</script>


<template>
    <div>
        <div ref="modal" class="text-lg bg-black shadow-lg rounded-lg p-8 w-4/5">
            <h2 class="text-4xl font-bold text-white ">Create Page</h2>
            <form @submit.prevent="submit">
                <div class="flex flex-col">

                    <div>
                        <Label>Page</Label>

                        <file-pond stylePanelAspectRatio="0.3" name="upload" label-idle="Page Image" accepted-file-types="image/jpeg, image/png"
                            @processfile="handleFilePondThumbnailProcess" @removefile="handleFilePondThumbnailRemove"
                            :server="{
                                process: {
                                    url: '/upload?media=pages',
                                },
                                revert: {
                                    url: '/api/pages/' + form.upload,
                                },
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            }" />
                    </div>

                    <div class="flex">
                        <!-- Button to cancel and button to create -->
                        <button @click="close" type="button"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Cancel
                        </button>
                        <PrimaryButton type="submit"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Create
                        </PrimaryButton>
                    </div>

                </div>


            </form>
        </div>
    </div>
</template>