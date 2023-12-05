<template>
    <form @submit.prevent="form.post(route('chapters.store'))" class="p-10 flex flex-col items-center gap-5">
        <div>
            <p class="font-bold text-2xl md:text-3xl">Create Chapter</p>
        </div>

        <div class="flex flex-col gap-5 md:w-full">

            <!-- Display series title -->
            <div>
                <Label>Series Title: {{ passedSeries.series_title }}</Label>
            </div>


            <div>
                <BaseInput for="chapter_title" v-model="form.chapter_title" label="Chapter Title" type="text" />
            </div>

            <div>
                <TextAreaInput for="chapter_notes" v-model="form.chapter_notes" label="Chapter Notes" type="text" />
            </div>

            <div>
                <Label>Chapter Thumbnail</Label>
                <!-- <ImageLabel /> -->

                <file-pond name="upload" label-idle="Chapter Thumbnail" accepted-file-types="image/jpeg, image/png"
                    @processfile="handleFilePondThumbnailProcess" :server="{
                        url: '/upload?media=chapter_thumbnail',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    }" />
            </div>
            <div>
                <file-pond name="upload" label-idle="Pages" allow-multiple="true" allow-reorder="true"
                @processfile="handleFilePondPagesProcess" 
                @removefile="handleFilePondPagesRemoveFile"
                accepted-file-types="image/jpeg, image/png" :server="{
                        url: '/upload?media=pages',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    }
                        " />
            </div>

            <!-- Create a list of buttons for Preview PC, Preview Mobile and Save Draft -->
            <div class="flex flex-col md:flex-row gap-5">
                <div class="flex flex-col gap-2">
                    <PrimaryButton>Preview PC</PrimaryButton>
                    <PrimaryButton>Preview Mobile</PrimaryButton>
                </div>

                <div class="flex flex-col gap-2">
                    <PrimaryButton>Save Draft</PrimaryButton>
                </div>
            </div>

            <!-- Create a radio input between immediate publish and scheduled publish.If scheduled is selected, make these pickers accessible. Immediate is the default choice -->
            <div>
                <Label>Publish</Label>
                <div class="flex flex-row gap-2">
                    <div>
                        <input type="radio" id="immediate" name="publish" value="immediate" checked="checked"
                            v-model="form.scheduled_publish" />
                        <label for="immediate">Immediate</label>
                    </div>

                    <div>
                        <input type="radio" id="scheduled" name="publish" value="scheduled"
                            v-model="form.scheduled_publish" />
                        <label for="scheduled">Scheduled</label>
                    </div>
                </div>
            </div>

            <!-- Create a date and time picker that only appears when isScheduled is true -->
            <div v-if="form.scheduled_publish == 'scheduled'">
                <div>
                    <Label>Date</Label>
                    <input type="date" v-model="form.scheduled_date" />
                </div>

                <div>
                    <Label>Time</Label>
                    <input type="time" v-model="form.scheduled_time" />
                </div>
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

                <PrimaryButton>Create Chapter</PrimaryButton>
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
        // series is an object
        passedSeries: {
            type: Object,
        },
    },
    components: {
        FilePond,
    },
    data() {
        return {
            csrfToken: document.querySelector('meta[name="csrf-token"]').content,
        };
    },

    setup() {
        const form = useForm({
            series_id: '',
            chapter_title: '',
            upload: '',
            pages: [],
            chapter_notes: '',
            scheduled_publish: '',
        });

        return {
            form,
        }
    },
    methods: {
        handleFilePondThumbnailProcess(error, file) {
            // Update the form with the file ids
            this.form.upload = file.serverId;
        },
        handleFilePondPagesProcess(error, file) {
            // Update the form with the file ids
            this.form.pages.push(file.serverId);
        },
        handleFilePondPagesRemoveFile(error, file) {
            // Update the form with the file ids
            this.form.pages = this.form.pages.filter((item) => item !== file.serverId);
        },
    },
    mounted() {
        this.form.series_id = this.passedSeries.series_id;
    },
}
</script>