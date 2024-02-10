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
                <div class="w-full h-full">
                    <file-pond name="upload" label-idle="Page Image" accepted-file-types="image/jpeg, image/png"
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
                <div class="flex flex-col bg-black px-4 py-8 rounded-lg justify-between">
                    <div>
                        Elements
                    </div>

                    <!-- v-for here... -->
                    <Link :href="route('elements.edit', {element: element.element_id,
                    content_type: 'chapters',
                    content_id: props.chapter_id})" v-for="element in props.page.elements" :key="element.element_id" class="flex flex-col">
                        <!-- image, then text -->
                        <div class="w-1/2">
                            <img :src="element.element_thumbnail ? element.element_thumbnail : '/assets/black_page.jpg'" alt="element image" class="w-24 h-24 rounded-lg" />
                        </div>
                        <div class="w-1/2">
                            <p>{{ element.element_name }}</p>
                        </div>

                    </Link>

                    <button @click="handleCreateElementButtonClicked" class="bg-pink-500 rounded-lg">
                        Add element
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>