<template>
    <form @submit.prevent="form.put(route('series.update', form.series_id))" class="p-10 flex flex-col items-center gap-5">
        <div>
            <p class="font-bold text-2xl md:text-3xl">Edit Series</p>
        </div>

        <div class="flex flex-col gap-5 md:w-full">
            <!-- Show universe name -->
            <div>
                <Label>Universe Name: {{ universe.universe_name }}</Label>
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

                <!-- <file-pond name="upload" label-idle="Series Thumbnail" accepted-file-types="image/jpeg, image/png"
                    :files="passedFiles" @processfile="handleFilePondThumbnailProcess" :server="{
                        url: '/upload?media=series_thumbnail',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    }" /> -->

                <file-pond name="upload" label-idle="Series Thumbnail" accepted-file-types="image/jpeg, image/png"
                    :files="passedFiles" @processfile="handleFilePondThumbnailProcess"
                    @removefile="handleFilePondThumbnailRemove" :server="{
                        process: {
                            url: '/upload?media=series_thumbnail',
                        },
                        revert: {
                            url: '/api/series/' + this.form.upload + '/thumbnail',
                        },
                        load: {
                            url: '/',
                        },
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    }" />
            </div>

            <div class="flex justify-center gap-2 md:gap-5">
                <Link :href="route('dashboard')">
                    <SecondaryButton class="flex flex-row gap-1 items-center">
                        <span class="material-symbols-rounded">
                            arrow_back
                        </span>
                        Go Back
                    </SecondaryButton>
                </Link>

                <PrimaryButton>Save Changes</PrimaryButton>
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
        series: {
            type: Object,
        },
        passedFiles: {
            type: Array,
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
            series_id: '',
            series_title: '',
            series_summary: '',
            // series_thumbnail: '',
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

        handleFilePondThumbnailRemove(error, file) {
            this.form.upload = '';
        },
    },
    mounted() {
        // Set the form values
        this.form.series_id = this.series.series_id;
        this.form.series_title = this.series.series_title;
        this.form.series_summary = this.series.series_summary;
        this.form.series_genre = this.series.series_genre;

    }
}
</script>