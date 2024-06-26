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
// import FilePondPluginFilePoster from 'filepond-plugin-file-poster';

import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';
// Import the plugin styles
// import 'filepond-plugin-file-poster/dist/filepond-plugin-file-poster.css';


let csrfToken = document.querySelector('meta[name="csrf-token"]').content

const emit = defineEmits(['closeModal'])
function close() {
    deleteTempThumbnail();
    deleteTempPages();
    emit('closeModal');
};

const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview,
    // FilePondPluginFilePoster,
);

const modal = ref(null)
const filepondPages = ref(null)

const form = useForm({
    chapter_title: '',
    chapter_number: '',
    chapter_notes: '',
    pages: [],
    moderators: [],
    upload: '',
});

const props = defineProps({
    chapter: {
        type: Object,
    },
})

const files = computed(() => {

    if (props.chapter.chapter_thumbnail !== '' && props.chapter.chapter_thumbnail !== null) {

        return [
            {
                source: props.chapter.chapter_thumbnail.replace(/^(http|https):\/\/[^/]+/, ''),
                options: {
                    type: 'local',
                },
            },
        ]
    }
    return [];
})

onClickOutside(modal, (event) => {
    if (event.target.id !== "mention-list") {
        close()
    }
})

function formatForm() {
    form.pages.forEach((page, index) => {
        try {
            if (page.getMetadata('pageId') !== undefined) {
                form.pages[index] = {
                    pageId: page.getMetadata('pageId')
                };
            }
            else {
                form.pages[index] = {
                    serverId: page.serverId
                };
            }
        } catch (error) {

            try {
                form.pages[index] = {
                    pageId: page.options.metadata.pageId
                };
            }
            catch (error) {
                form.pages[index] = {
                    serverId: page.serverId
                };
            }
        }
    }
    )
}

function submit() {
    formatForm();

    form.moderators = form.moderators.map(moderator => moderator.id)


    form.put(route('chapters.update', props.chapter.chapter_id), {
        onFinish: () => {
            form.upload = '';
            close()
        }
    });

};

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
}

function deleteTempPages() {
    formatForm();

    form.pages.forEach(page => {
        if (page.serverId) {
            axios.delete(route('delete-thumbnail', {
                isTemp: "true",
                contentType: "Page",
                serverId: page.serverId,
            })).catch(error => {
                console.log(error);
            });
        }
    }
    )
}

function deleteExistingThumbnail() {
    if (props.chapter.chapter_thumbnail !== '') {
        axios.delete(route('delete-thumbnail', {
            isTemp: "false",
            contentType: "Chapter",
            contentId: props.chapter.chapter_id,
        })).catch(error => {
            console.log(error);
        });
    }
}

function deleteExistingPage(source, load) {
    // // in form.pages, get the page with same source as source
    const pageIndexToBeDeleted = form.pages.findIndex(page => page.source === source);

    let pageId = '';

    try {
        pageId = form.pages[pageIndexToBeDeleted].getMetadata('pageId');
    }
    catch (error) {
        pageId = form.pages[pageIndexToBeDeleted].options.metadata.pageId;
    }

    axios.delete(route('delete-thumbnail', {
        isTemp: "false",
        contentType: "Page",
        contentId: pageId,
        deletePage: true
    })).catch(error => {
        console.log(error);
    });

    // Remove from form.pages
    form.pages.splice(pageIndexToBeDeleted, 1);
}

function handleFilePondThumbnailProcess(error, file) {
    // Update the form with the file ids
    form.upload = file.serverId;
}

function handleFilePondThumbnailRemove(error, file) {
    form.upload = '';
}

function handleFilePondPagesRevert(serverId) {

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

function handleFilePondPagesProcess(error, file) {
    // Update the form with the file ids

    // Push to the beginning of the array
    form.pages.unshift({
        serverId: file.serverId,
        source: '',
    });
}

function handleFilePondPagesReorderFiles(files) {
    console.log(form.pages)
    // // Clear form.pages
    form.pages = files;

    console.log(form.pages);
}

function addEmptyPage() {
    filepondPages.value.addFile('/assets/black_pixel.png', {index: filepondPages.value.getFiles().length})
    // filepondPages.value.addFile()

}

onMounted(() => {
    form.chapter_title = props.chapter.chapter_title
    form.chapter_notes = props.chapter.chapter_notes
    form.chapter_number = props.chapter.chapter_number

    form.moderators = props.chapter.moderators

    APICalls.getFilepondPages(props.chapter.chapter_id).then(response => {
        form.pages = response.data
        console.log(form.pages)
    }).catch(error => console.log(error))
}
)
</script>


<template>
    <div>
        <div ref="modal" class="text-lg bg-black shadow-lg rounded-lg p-8 w-4/5">
            <h2 class="text-4xl font-bold text-white ">Edit Chapter</h2>
            <form @submit.prevent="submit">
                <div class="flex">

                    <div class="w-1/2">

                        <Label>Chapter Thumbnail</Label>

                        <file-pond stylePanelAspectRatio="1" id="thumbnailpond" name="upload"
                            label-idle="Chapter Thumbnail" accepted-file-types="image/jpeg, image/png" :files="files"
                            @processfile="handleFilePondThumbnailProcess" @removefile="handleFilePondThumbnailRemove"
                            :server="{
                                process: {
                                    url: '/upload?media=chapter_thumbnail',
                                },
                                revert: {
                                    url: '/api/thumbnail?contentType=Chapter&serverId=' + form.upload + '&isTemp=true',
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
                    <div class="flex flex-col justify-between w-1/2 ml-8 overflow-y-auto h-[80vh]">
                        <div class="flex flex-col gap-4 ">

                            <InputLabel for="chapter_title" value="Chapter title:" />
                            <div class="flex w-full">

                                <div>
                                    <InputLabel for="chapter_number" value="Chapter number:" class="hidden" />
                                    <input id="chapter_number" type="number"
                                        class="mt-1 block w-20 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                        v-model="form.chapter_number" required autofocus />
                                    <InputError class="mt-2" message="" />
                                </div>
                                <div class="w-full">

                                    <TextInput id="chapter_title" type="text" class="mt-1 block w-full"
                                        v-model="form.chapter_title" required autofocus />
                                    <InputError class="mt-2" message="" />
                                </div>

                            </div>

                            <div>
                                <InputLabel for="chapter_notes" value="Description:" />

                                <TextAreaInput id="chapter_notes" class="mt-1 block w-full" v-model="form.chapter_notes"
                                    autocomplete="chapter_notes"></TextAreaInput>
                                <InputError class="mt-2" message="" />
                            </div>

                            <div>
                                <AddAdminFormInput v-model="form.moderators" />
                            </div>

                            <div>

                                <PrimaryButton @click.prevent="addEmptyPage" class="my-4">
                                    Add Empty Page
                                </PrimaryButton>
                                <file-pond ref="filepondPages" id="pagepond" name="upload" label-idle="Pages"
                                    allow-multiple="true" allow-reorder="true" :files="form.pages"
                                    image-preview-markup-show="false" @processfile="handleFilePondPagesProcess"
                                    @reorderfiles="handleFilePondPagesReorderFiles"
                                    accepted-file-types="image/jpeg, image/png" :server="{
                                        process: {
                                            url: '/upload?media=pages',
                                        },
                                        revert: (uniqueFileId, load, error) => {
                                            handleFilePondPagesRevert(uniqueFileId)
                                            load();
                                        },
                                        remove: (source, load, error) => {
                                            deleteExistingPage(source, load);

                                            load();
                                        },
                                        load: {
                                            url: '/',
                                        },
                                        headers: {
                                            'X-CSRF-TOKEN': csrfToken
                                        }
                                    }
                                        " styleItemPanelAspectRatio="1.414" />
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

<!-- <style> -->

<style>
#pagepond .filepond--item {
    width: calc(33% - 0.5em);
}

#thumbnailpond .filepond--root {
    /* minimum height */
    /* min-height: 200px; */
    height: 500px;
    border: 1px solid #e53e3e;
}

/* #thumbnailpond .filepond--item  {
    background-color: #ffffff;
    height: 70%;
    border: 1px solid #e53e3e;
} */
</style>