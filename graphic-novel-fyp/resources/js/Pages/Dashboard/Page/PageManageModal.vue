<<<<<<< Updated upstream
<template>
    </template>
=======
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
import APICalls from '@/Utilities/APICalls';

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
    
})


const form = useForm({
    upload: '',
});

onClickOutside(modal, () => {
    close()
})

function submit() {
    // form.put(route('universes.update', props.universe.universe_id), {
    //     onFinish: () => {
    //         form.upload = '';
    //         close()
    //     }
    // });
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

        // axios.delete(route('delete-thumbnail', {
        //     isTemp: "true",
        //     contentType: "Universe",
        //     serverId: form.upload,
        // })).catch(error => {
        //     console.log(error);
        // });
    }
}

function deleteExistingThumbnail() {
    // if (props.universe.media && props.universe.media.length > 0) {
    if (props.universe.thumbnail !== '') {
        // axios.delete(route('delete-thumbnail', {
        //     isTemp: "false",
        //     contentType: "Universe",
        //     contentId: props.universe.universe_id,
        // })).catch(error => {
        //     console.log(error);
        // });
    }
}

onMounted(() => {
    // form.universe_name = props.universe.universe_name

    APICalls.getElements('pages', props.page.page_id).then(response => {
        elements.value = response.data
        console.log(elements.value)
    }).catch(error => console.log(error))
})

const files = computed(() => {
    console.log(props.page)
    if (props.page.page_image !== null || props.page.page_image !== '') {
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

console.log(dashboardView)
console.log(parentContentId)

onClickOutside(modal, () => {
    close()
})

// const submit = (elementType) => {
//     // console.log(parentContentType)
//     form.elementType = elementType;
//     form.contentType = getParentContentType();
//     form.contentId = parentContentId;
//     form.post(route('elements.store'), {
//         onFinish: () => {
//             form.reset();
//             close();
//         }
//     })
// };

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
                    <file-pond  name="upload" label-idle="Page Image" accepted-file-types="image/jpeg, image/png"
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
                    <div class="flex flex-col h-full px-1 my-4 overflow-auto">

                        <div v-for="element in elements" :key="element.element_id" class="flex flex-col items-center">
                            <div>
                                <img :src="element.element_thumbnail" alt="element.element_name" class="w-32 h-32 bg-pink-500 rounded-lg">
                            </div>
                            <div>
                                {{ element.element_name ? element.element_name : 'no name' }}
                            </div>
                        </div>
                    </div>

                    <button @click="handleCreateElementButtonClicked" class="bg-pink-500 rounded-lg">
                        Add element
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
>>>>>>> Stashed changes
