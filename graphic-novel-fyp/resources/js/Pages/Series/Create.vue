<template>
    <form @submit.prevent="form.post(route('series.store'))" class="p-10 flex flex-col items-center gap-5">
        <div>
            <p class="font-bold text-2xl md:text-3xl">Create Series</p>
        </div>

        <div class="flex flex-col gap-5 md:w-full">
            <!-- Dropdown for all universes -->
            <div>
                <Label>Universe</Label>
                <select v-model="form.universe_id" class="border-2 border-black rounded-md p-2">
                    <option v-for="universe in universes" :key="universe.universe_id" :value="universe.universe_id">
                        {{ universe.universe_name }}
                    </option>
                </select>
            </div>

            <div>
                <BaseInput for="series_title" v-model="form.series_title" label="Series Title" type="text" />
            </div>

            <div>
                <Label>Genre</Label>
                <select v-model="form.series_genre" class="border-2 border-black rounded-md p-2">
                    <option v-for="genre in genres" :key="genre" :value="genre">
                        {{ genre }}
                    </option>
                </select>
            </div>

            <div>
                <TextAreaInput for="series_title" v-model="form.series_summary" label="Series Summary" type="text" />
            </div>

            <div>
                <Label>Series Thumbnail</Label>
                <!-- <ImageLabel /> -->

                <file-pond name="upload" label-idle="Series Thumbnail" accepted-file-types="image/jpeg, image/png"
                    @processfile="handleFilePondThumbnailProcess" :server="{
                        url: '/upload?media=series_thumbnail',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    }" />
            </div>

            <div class="flex justify-center gap-2 md:gap-5">
                <a href="">
                    <SecondaryButton class="flex flex-row gap-1 items-center">
                        <span class="material-symbols-rounded">
                            arrow_back
                        </span>
                        Go Back
                    </SecondaryButton>
                </a>

                <PrimaryButton>Create Series</PrimaryButton>
            </div>
        </div>
    </form>
</template>

<script>
import { useForm } from '@inertiajs/vue3';

import vueFilePond from 'vue-filepond';

import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';

import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';

import APICalls from '@/Utilities/APICalls'
import { usePage } from '@inertiajs/vue3'

const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview,
);

export default {
    props: {
        // universe is an object
        universe: {
            type: Object,
        },
    },
    components: {
        FilePond,
    },
    data() {
        return {
            genres: [
                'ACTION',
                'ADVENTURE',
                'COMEDY',
                'DRAMA',
                'FANTASY',
                'HORROR',
                'MYSTERY',
                'ROMANCE',
                'THRILLER',
            ],
            universes: [],
            csrfToken: document.querySelector('meta[name="csrf-token"]').content,
        }
    }
    ,
    setup() {
        const form = useForm({
            series_title: '',
            universe_id: '',
            series_summary: '',
            series_thumbnail: '',
            series_genre: '',
            upload: '',
        });

        return {
            form,
        }
    },
    methods: {
        handleFilePondThumbnailProcess(error, file) {
            // console.log(file.serverId);

            // Update the form with the file ids
            this.form.upload = file.serverId;

            console.log(this.form.upload)
        },
    },
    mounted() {
        const page = usePage()

        APICalls.getUniversesByUserId(page.props.auth.user.id).then(response => {
            this.universes = response.data
            this.universeLoaded = true
        }).catch(error => console.log(error))

        // If the universe prop is not null, then set the universe_id to the universe's ID
        if (this.universe != null) {
            this.form.universe_id = this.universe.universe_id
        }
    }
}
</script>