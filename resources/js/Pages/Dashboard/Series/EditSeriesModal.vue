<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { onClickOutside } from '@vueuse/core'
import { ref, onMounted, defineProps, computed } from 'vue'
import AddAdminFormInput from '../AdminMention/AddAdminFormInput.vue'
import APICalls from '@/Utilities/APICalls';

import { useForm } from '@inertiajs/vue3';

import vueFilePond from 'vue-filepond';

import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';

import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';



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

const form = useForm({
    series_title: '',
    series_summary: '',
    series_genre: '',
    upload: '',
    moderators: []
});

const genres = ref([
])

const props = defineProps({
    series: {
        type: Object,
    },
})

const files = computed(() => {

    if (props.series.series_thumbnail !== '') {

        return [
            {
                source: props.series.series_thumbnail.replace('http://localhost', ''),
                options: {
                    type: 'local',
                },
            },
        ]
    }
    return [];
})

onClickOutside(modal, () => {
    close()
})

function submit() {

    // console.log(form.moderators)

    form.moderators = form.moderators.map(moderator => moderator.id)


    form.put(route('series.update', props.series.series_id), {
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
            contentType: "Series",
            serverId: form.upload,
        })).catch(error => {
            console.log(error);
        });
    }
}

function deleteExistingThumbnail() {
    if (props.series.series_thumbnail !== '') {
        axios.delete(route('delete-thumbnail', {
            isTemp: "false",
            contentType: "Series",
            contentId: props.series.series_id,
        })).catch(error => {
            console.log(error);
        });
    }
}

onMounted(() => {
    APICalls.getAllGenres().then(response => {
        genres.value = response.data
    }).catch(error => console.log(error))

    form.series_title = props.series.series_title
    form.series_summary = props.series.series_summary
    form.series_genre = props.series.series_genre

    console.log('moderators...')
    console.log(props.series.moderators)

    form.moderators = props.series.moderators

    // console.log(props.series)
}
)

let csrfToken = document.querySelector('meta[name="csrf-token"]').content

function handleFilePondThumbnailProcess(error, file) {
    form.upload = file.serverId;
}

function handleFilePondThumbnailRemove(error, file) {
    form.upload = '';
}
</script>


<template>
    <div>
        <div ref="modal" class="text-lg bg-black shadow-lg rounded-lg p-8 w-4/5">
            <h2 class="text-4xl font-bold text-white ">Edit Series</h2>
            <form @submit.prevent="submit">
                <div class="flex">

                    <div class="w-1/2">
                        <Label>Series Thumbnail</Label>
                        <file-pond name="upload" label-idle="Series Thumbnail" accepted-file-types="image/jpeg, image/png"
                            :files="files" @processfile="handleFilePondThumbnailProcess"
                            @removefile="handleFilePondThumbnailRemove" :server="{
                                process: {
                                    url: '/upload?media=series_thumbnail',
                                },
                                revert: {
                                    url: '/api/thumbnail?contentType=Series&serverId=' + form.upload + '&isTemp=true',
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
                                <InputLabel for="series_title" value="Series title:" />
                                <TextInput id="series_title" type="text" class="mt-1 block w-full"
                                    v-model="form.series_title" required autofocus />
                                <InputError class="mt-2" message="" />
                            </div>

                            <div>
                                <InputLabel for="series_genre" value="Genre:" />
                                <select v-model="form.series_genre" class="border-2 border-black rounded-md p-2 w-full"
                                    required>
                                    <option disabled value="">Please select a genre</option>
                                    <option v-for="genre in genres" :key="genre" :value="genre">
                                        {{ genre }}
                                    </option>
                                </select>
                                <InputError class="mt-2" message="" />
                            </div>

                            <div>
                                <InputLabel for="series_summary" value="Description:" />
                                <textarea id="series_summary" class="mt-1 block w-full" v-model="form.series_summary"
                                    autofocus></textarea>
                                <InputError class="mt-2" message="" />
                            </div>

                            <div>
                                <AddAdminFormInput v-model="form.moderators"/>
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