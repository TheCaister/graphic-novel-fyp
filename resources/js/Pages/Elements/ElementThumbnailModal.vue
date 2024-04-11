<script setup>
import { onClickOutside } from '@vueuse/core'
import { ref, defineProps, computed } from 'vue'

import { useForm } from '@inertiajs/vue3';

import vueFilePond from 'vue-filepond';

import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';

import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';
import axios from 'axios';

const emit = defineEmits(['closeModal', 'saveThumbnail'])
function close() {
    deleteTempThumbnail();
    emit('closeModal');
};

const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview,
);

const modal = ref(null)

const props = defineProps({
    element: {
        type: Object,
    },
})


const form = useForm({
    upload: '',
});

onClickOutside(modal, () => {
    close()
})

let csrfToken = document.querySelector('meta[name="csrf-token"]').content

function handleFilePondThumbnailProcess(error, file) {
    form.upload = file.serverId;
}

function handleFilePondThumbnailRemove(error, file) {
    form.upload = '';
}

function deleteTempThumbnail() {

    if (form.upload) {
        console.log('yes, there is upload present...')
        console.log(form.upload)

        axios.delete(route('delete-thumbnail', {
            isTemp: "true",
            contentType: "Element",
            serverId: form.upload,
        })).catch(error => {
            console.log(error);
        });
    }
}

function deleteExistingThumbnail() {
    if (props.element.element_thumbnail !== '') {
        axios.delete(route('delete-thumbnail', {
            isTemp: "false",
            contentType: "Element",
            contentId: props.element.element_id,
        })).catch(error => {
            console.log(error);
        });
    }
}

function save(){
    emit('saveThumbnail', form.upload)
    form.upload = '';
    close()
}

const files = computed(() => {
    if (props.element.element_thumbnail !== '' && props.element.element_thumbnail) {
        return [
            {
                source: props.element.element_thumbnail.replace(/^(http|https):\/\/[^/]+/, ''),
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
        <div ref="modal" class="text-lg bg-black shadow-lg rounded-lg p-8 w-1/2">
            <h2 class="text-4xl font-bold text-white ">Edit Thumbnail</h2>
            <!-- <form @submit.prevent="submit"> -->
                <form>
                <div class="flex flex-col items-center">

                    <div class="w-full">
                        <Label>Element Thumbnail</Label>

                        <file-pond stylePanelAspectRatio="0.6" name="upload" label-idle="Element Thumbnail" accepted-file-types="image/jpeg, image/png"
                            :files="files" @processfile="handleFilePondThumbnailProcess"
                            @removefile="handleFilePondThumbnailRemove" :server="{
                                process: {
                                    url: '/upload?media=element_thumbnail',
                                },
                                revert: {
                                    url: '/api/thumbnail?contentType=Element&serverId=' + form.upload + '&isTemp=true',
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
                    <div class="flex w-1/2">

                        <button @click="close" type="button"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Cancel
                        </button>

                        <!-- Button for save -->
                        <button @click="save" type="button" class="bg-pink-500 text-white font-bold py-2 px-4 rounded">
                            Save
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</template>