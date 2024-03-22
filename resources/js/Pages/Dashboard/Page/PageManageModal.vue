<script setup>
import { onClickOutside } from '@vueuse/core'
import { ref, inject, onMounted, computed } from 'vue'
import { useForm } from '@inertiajs/vue3';

import vueFilePond from 'vue-filepond';

import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';

import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';
import axios from 'axios';

import DeleteModal from '../../Dashboard/DeleteModal.vue'

const emit = defineEmits(['closeModal', 'createElement'])

function close() {
    deleteTempThumbnail();
    emit('closeModal');
};

const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview,
);

const modal = ref(null)

const elements = ref([])

const isDeleteModalOpen = ref(false)

const selectedElement = ref({ element_id: 0, element_name: "" })


const props = defineProps({
    page: {
        type: Object,
    },
    chapter_id: {
        type: Number,
    }
})


const form = useForm({
    upload: '',
});

onClickOutside(modal, () => {
    submit()
})

function submit() {

    console.log(props.page)

    form.put(route('pages.update', props.page.page_id), {
        onFinish: () => {
            form.upload = '';
            close()
        }
    });
};

function updateContentList() {
    APICalls.getElements('Page', props.page.page_id).then(response => {
        elements.value = response.data
        // elementsLoaded.value = true

        // emits('updateSize', elements.value.length)
    }).catch(error => console.log(error))
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
            contentType: "Page",
            serverId: form.upload,
        })).catch(error => {
            console.log(error);
        });
    }
}

function deleteExistingThumbnail() {
    if (props.page.page_image !== '') {
        axios.delete(route('delete-thumbnail', {
            isTemp: "false",
            contentType: "Page",
            contentId: props.page.page_id,
            deletePage: false
        })).catch(error => {
            console.log(error);
        });
    }
}

onMounted(() => {
    // console.log(props.page)
})

const files = computed(() => {
    if (props.page.page_image !== '' && props.page.page_image) {
        return [
            {
                source: props.page.page_image.replace('http://localhost', ''),
                options: {
                    type: 'local',
                },
            },
        ]
    }
    return [];
})

const dashboardView = inject('dashboardView')
const parentContentId = inject('parentContentId')

// console.log(dashboardView)
// console.log(parentContentId)

onClickOutside(modal, () => {
    close()
})

function getParentContentType() {
    switch (dashboardView) {
        case 'UniverseView':
            return ''
        case 'SeriesView':
            return 'universes'
        case 'ChapterView':
            return 'series'
        case 'PageView':
            return 'chapters'
        default:
            return ''
    }

}

function handleCreateElementButtonClicked() {
    emit('createElement')
    close()
}
</script>

<template>
    <div>
        <div ref="modal" class="text-lg rounded-lg p-8 w-4/5 h-4/5 text-white">
            <div class="flex h-full gap-8">

                <!-- Page image here -->
                <div class="w-full ">
                    <file-pond style="{
                    }" name="upload" label-idle="Page Image" accepted-file-types="image/jpeg, image/png"
                        :files="files" @processfile="handleFilePondThumbnailProcess"
                        @removefile="handleFilePondThumbnailRemove" :server="{
                            process: {
                                url: '/upload?media=page_image',
                            },
                            revert: {
                                url: '/api/thumbnail?contentType=Page&serverId=' + form.upload + '&isTemp=true',
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

                <!-- List of elements -->
                <div class="flex flex-col gap-6">
                    <div class="flex flex-col bg-black px-4 py-8 rounded-lg justify-between overflow-auto items-center">
                        <div class="mb-4">
                            Elements
                        </div>
                        <Link :href="route('elements.edit', {
                                element: element.element_id,
                                contentType: 'Page',
                                content_id: props.page.page_id
                            })" v-for="element in props.page.elements" :key="element.element_id"
                            class="flex flex-col relative">
                        <!-- image, then text -->
                        <div>
                            <img :src="element.element_thumbnail ? element.element_thumbnail : '/assets/black_page.jpg'"
                                alt="element image" class="w-24 h-24 rounded-lg" />
                        </div>
                        <div class="w-1/2">
                            <p>{{ element.element_name }}</p>
                        </div>
                        <!-- Button for deletion -->
                        <div class="absolute top-0 right-0 p-1">
                            <button @click.prevent="selectedElement = element; isDeleteModalOpen = true;"
                                class="bg-red-500 text-white rounded-full w-6 h-6">X</button>
                        </div>
                        </Link>
                    </div>
                    <button @click="handleCreateElementButtonClicked" class="bg-pink-500 rounded-full h-24">
                        Add element
                    </button>
                </div>
            </div>
        </div>

        <Teleport to="body">
            <Transition name="modal" class="z-50">
                <delete-modal v-if="isDeleteModalOpen" @closeModal="isDeleteModalOpen = false; updateContentList()"
                    :content="{
                            content_id: selectedElement.element_id,
                            content_name: selectedElement.element_name,
                        }" type="elements"
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60" />
            </Transition>

        </Teleport>
    </div>
</template>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: all 0.25s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
    transform: scale(1.1);
}

.filepond--panel-root {
    background-color: transparent;
    border: 20px solid #0059ff;
}
</style>