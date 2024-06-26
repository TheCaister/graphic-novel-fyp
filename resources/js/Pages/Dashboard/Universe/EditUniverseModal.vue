<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { onClickOutside } from '@vueuse/core'
import AddAdminFormInput from '../AdminMention/AddAdminFormInput.vue'
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
    deleteTempThumbnail();
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
    moderators: [],
});

onClickOutside(modal, (event) => {

    // In MentionList.vue, each button has
    // and id mention-list. This is to
    // prevent the modal from closing when
    // the user clicks on the mention list
    if (event.target.id !== "mention-list") {
        close()
    }
})

function submit() {
    // console.log(form)
    // if (form.moderators && form.moderators.length > 0) {
    //     form.moderators = form.moderators.map(moderator => moderator.id)
    // }

    form.moderators = form.moderators.map(moderator => moderator.id)


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

function deleteTempThumbnail() {

    if (form.upload) {

        axios.delete(route('delete-thumbnail', {
            isTemp: "true",
            contentType: "Universe",
            serverId: form.upload,
        })).catch(error => {
            console.log(error);
        });
    }
}

function deleteExistingThumbnail() {
    // if (props.universe.media && props.universe.media.length > 0) {
    if (props.universe.thumbnail !== '') {
        axios.delete(route('delete-thumbnail', {
            isTemp: "false",
            contentType: "Universe",
            contentId: props.universe.universe_id,
        })).catch(error => {
            console.log(error);
        });
    }
}

onMounted(() => {
    // console.log(props.universe)
    form.universe_name = props.universe.universe_name
    form.moderators = props.universe.moderators
})

const files = computed(() => {
    if (props.universe.thumbnail !== '') {
        return [
            {
                source: props.universe.thumbnail.replace(/^(http|https):\/\/[^/]+/, ''),
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

                        <file-pond stylePanelAspectRatio="1" name="upload" label-idle="Universe Thumbnail" accepted-file-types="image/jpeg, image/png"
                            :files="files" @processfile="handleFilePondThumbnailProcess"
                            @removefile="handleFilePondThumbnailRemove" :server="{
                                process: {
                                    url: '/upload?media=universe_thumbnail',
                                },
                                revert: {
                                    url: '/api/thumbnail?contentType=Universe&serverId=' + form.upload + '&isTemp=true',
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
                        <div class="flex flex-col gap-4">
                            <div>
                                <InputLabel for="universe_name" value="Universe name:" />
                                <TextInput id="universe_name" type="text" class="mt-1 block w-full"
                                    v-model="form.universe_name" required autofocus />
                                <InputError class="mt-2" message="" />
                            </div>

                            <div>
                                <AddAdminFormInput v-model="form.moderators"/>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <!-- Button to cancel and button to create -->
                            <button @click="close" type="button"
                                class="text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </button>
                            <button type="submit"
                                class="bg-pink-500 text-white font-bold py-2 px-4 rounded-full">
                                Save
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</template>