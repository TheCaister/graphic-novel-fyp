<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { onClickOutside } from '@vueuse/core'
import { ref, onMounted, defineProps, computed } from 'vue'
import APICalls from '@/Utilities/APICalls';

import { useForm } from '@inertiajs/vue3';

import AddAdminFormInput from '../AdminMention/AddAdminFormInput.vue'



import vueFilePond from 'vue-filepond';

import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';

import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';


let csrfToken = document.querySelector('meta[name="csrf-token"]').content

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
const filepondPages = ref(null)

const form = useForm({
    series_id: '',
    chapter_title: '',
    chapter_number: 0,
    chapter_notes: '',
    // scheduled_publish: '',
    pages: [],
    moderators: [],
    upload: '',
});

const genres = ref([
])

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
    form.series_id = props.parentContentIdNumber

    form.moderators = form.moderators.map(moderator => moderator.id)


    form.post(route('chapters.store'), {
        onFinish: () => {
            form.upload = '';
            close()
        }
    });
};

function setFormToLatestChapterNumber() {
    APICalls.getLatestChapterNumber(props.parentContentIdNumber).then(
        response => {
            form.chapter_number = Number(response.data) + 1
            // form.chapter_number = response.data + 1


            // console.log(Number(response.data))
        }
    ).catch(error => console.log(error))
}

function handleFilePondThumbnailProcess(error, file) {
    // Update the form with the file ids
    form.upload = file.serverId;
}

function handleFilePondThumbnailRemove(error, file) {
    form.upload = '';
}

function handleFilePondPagesProcess(error, file) {
    // Update the form with the file ids
    // console.log(file)
    form.pages.push(file.serverId);
}

function handleFilePondPagesRemoveFile(serverId) {

    // in form.pages, remove the page with same serverId as serverId
    const index = form.pages.findIndex((item) => item.serverId === serverId);
    if (index !== -1) {
        form.pages.splice(index, 1);
    }


    axios.delete(route('delete-thumbnail', {
        isTemp: "true",
        contentType: "Page",
        serverId: serverId,
    })).catch(error => {
        console.log(error);
    });
}

function handleFilePondPagesReorderFiles(files) {
    // console.log(form.pages)

    // // Clear form.pages
    form.pages = files.map(file => file.serverId);

    // console.log(files.map(file => file.serverId));
}

function addEmptyPage() {
    console.log('Adding empty page...')
    filepondPages.value.addFile('/assets/black_pixel.png', {index: filepondPages.value.getFiles().length})    // filepondPages.value.addFile()

}

function deleteTempThumbnail() {

    if (form.upload) {

        axios.delete(route('delete-thumbnail', {
            isTemp: "true",
            contentType: "Chapter",
            serverId: form.upload,
        })).catch(error => {
            console.log(error);
        });
    }

    if (form.pages) {
        // form.pages.forEach(element => {
        //     axios.delete('/api/pages/' + element).catch(error => {
        //         console.log(error);
        //     });
        // });

        form.pages.forEach(element => {
            axios.delete(route('delete-thumbnail', {
                isTemp: "true",
                contentType: "Page",
                serverId: form.upload,
            })).catch(error => {
                console.log(error);
            });
        });
    }
}

onMounted(() => {
    APICalls.getAllGenres().then(response => {
        genres.value = response.data
    }).catch(error => console.log(error))

    setFormToLatestChapterNumber()

    // form.chapter_number = getNextChapterNumber()
}
)
</script>


<template>
    <div>
        <div ref="modal" class="text-lg bg-black shadow-lg rounded-lg p-8 w-4/5">
            <h2 class="text-4xl font-bold text-white ">Create Chapter</h2>
            <form @submit.prevent="submit">
                <div class="flex">

                    <div class="w-1/2">
                        <!-- <PrimaryButton @click="console.log(form)">
                            Check form
                        </PrimaryButton> -->

                        <Label>Chapter Thumbnail</Label>
                        <!-- <ImageLabel /> -->

                        <file-pond stylePanelAspectRatio="1" name="upload" label-idle="Chapter Thumbnail"
                            accepted-file-types="image/jpeg, image/png" @processfile="handleFilePondThumbnailProcess"
                            @removefile="handleFilePondThumbnailRemove" :server="{
                                process: {
                                    url: '/upload?media=chapter_thumbnail',
                                },
                                // revert: {
                                //     url: '/api/chapter/' + form.upload + '/thumbnail',
                                // },

                                revert: {
                                    url: '/api/thumbnail?contentType=Chapter&serverId=' + form.upload + '&isTemp=true',
                                },
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            }" />
                    </div>
                    <div class="flex flex-col justify-between w-1/2 ml-8 overflow-y-auto h-[80vh]">
                        <div>
                            <InputLabel for="chapter_title" value="Chapter title:" />
                            <div class="flex">

                                <div>
                                    <InputLabel for="chapter_number" value="Chapter number:" class="hidden" />
                                    <input id="chapter_number" type="number"
                                        class="mt-1 block w-20 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                        v-model="form.chapter_number" required autofocus />
                                    <InputError class="mt-2" message="" />
                                </div>
                                <div>

                                    <TextInput id="chapter_title" type="text" class="mt-1 block w-full"
                                        v-model="form.chapter_title" required autofocus />
                                    <InputError class="mt-2" message="" />
                                </div>

                            </div>

                            <div>
                                <InputLabel for="chapter_notes" value="Description:" />
                                <TextAreaInput id="chapter_notes" class="mt-1 block w-full" v-model="form.chapter_notes"
                                    autofocus></TextAreaInput>
                                <InputError class="mt-2" message="" />
                            </div>

                            <div>
                                <AddAdminFormInput v-model="form.moderators" />
                            </div>

                            <div>
                                <PrimaryButton @click.prevent="addEmptyPage" class="my-4">
                                Add Empty Page
                            </PrimaryButton>

                                <file-pond id="test" ref="filepondPages" name="upload" label-idle="Pages" allow-multiple="true"
                                    allow-reorder="true" @processfile="handleFilePondPagesProcess"
                                    @reorderfiles="handleFilePondPagesReorderFiles"
                                    accepted-file-types="image/jpeg, image/png" :server="{
                                        url: '/upload?media=pages',
                                        revert: (uniqueFileId, load, error) => {
                                            handleFilePondPagesRemoveFile(uniqueFileId)

                                            // Should call the load method when done, no parameters required
                                            load();
                                            // error('oh my goodness');
                                        },
                                        headers: {
                                            'X-CSRF-TOKEN': csrfToken,
                                        }
                                    }" styleItemPanelAspectRatio="1.414" />
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
                                Create
                            </PrimaryButton>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</template>

<!-- <style> -->

<style>
#test .filepond--item {
    width: calc(33% - 0.5em);
}
</style>

<!-- </style> -->
