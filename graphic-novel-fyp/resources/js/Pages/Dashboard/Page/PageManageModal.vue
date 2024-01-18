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
    page: {
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
    // console.log(props.universe)
    // form.universe_name = props.universe.universe_name
})

const files = computed(() => {
    // if (props.universe.thumbnail !== '') {
    //     return [
    //         {
    //             source: props.universe.thumbnail.replace('http://localhost', ''),
    //             options: {
    //                 type: 'local',
    //             },
    //         },
    //     ]
    // }
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
</script>

<template>
    <div>
        <div ref="modal" class="text-lg bg-black shadow-lg rounded-lg p-8 w-4/5">
            <div class="flex">
                <!-- upload, remove and add element buttons -->
                <div class="flex flex-col">
                    <button>
                        Upload image
                    </button>
                    <button>
                        Remove image
                    </button>
                    <button>
                        Add element
                    </button>
                </div>

                <!-- Page image here -->
                <div>
                    <file-pond name="upload" label-idle="Universe Thumbnail" accepted-file-types="image/jpeg, image/png"
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

                <!-- List of elements -->
                <div class="flex flex-col">
                    <div>
                        Elements
                    </div>

                    <!-- v-for here... -->
                    <div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>