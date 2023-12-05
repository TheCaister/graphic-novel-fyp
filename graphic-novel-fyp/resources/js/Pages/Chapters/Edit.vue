<template>
    <form @submit.prevent="submitForm()" class="p-10 flex flex-col items-center gap-5">
        <div>
            <p class="font-bold text-2xl md:text-3xl">Edit Chapter</p>
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

                <!-- <file-pond name="upload" label-idle="Chapter Thumbnail" accepted-file-types="image/jpeg, image/png"
                    @processfile="handleFilePondThumbnailProcess" :server="{
                        url: '/upload?media=chapter_thumbnail',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    }" /> -->

                <file-pond name="upload" label-idle="Chapter Thumbnail" accepted-file-types="image/jpeg, image/png"
                    :files="passedChapterThumbnail" @processfile="handleFilePondThumbnailProcess"
                    @removefile="handleFilePondThumbnailRemove" :server="{
                        process: {
                            url: '/upload?media=chapter_thumbnail',
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
            <div>

                <file-pond name="upload" label-idle="Pages" allow-multiple="true" allow-reorder="true"
                    :files="passedChapterPages" @processfile="handleFilePondPagesProcess"
                    @removefile="handleFilePondPagesRemoveFile" @reorderfiles="handleFilePondPagesReorderFiles"
                    :beforeRemoveFile="handleFilePondPagesBeforeRemoveFile" accepted-file-types="image/jpeg, image/png"
                    :server="{
                        process: {
                            url: '/upload?media=pages',
                        },
                        revert: {
                            url: '/api/pages/' + this.pageToBeDeleted,
                        },
                        load: {
                            url: '/',
                        },
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    }" />
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
        // series is an object
        passedSeries: {
            type: Object,
        },
        chapter: {
            type: Object,
        },

        passedChapterThumbnail: {
            type: Array,
        },

        passedChapterPages: {
            type: Array,
        },
    },
    components: {
        FilePond,
    },
    data() {
        return {
            csrfToken: document.querySelector('meta[name="csrf-token"]').content,
            pageToBeDeleted: '',
        };
    },

    setup() {
        const form = useForm({
            chapter_id: '',
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
        handleFilePondThumbnailRemove(error, file) {
            this.form.upload = '';
        },
        handleFilePondPagesProcess(error, file) {
            // Update the form with the file ids

            // Push to the beginning of the array
            this.form.pages.unshift({
                serverId: file.serverId,
                source: '',
            });
        },
        handleFilePondPagesRemoveFile(error, file) {

            // Go through the form.pages array and if a page has an empty serverId, set it to source
            this.form.pages.forEach(page => {
                if (!page.hasOwnProperty('serverId')) {
                    page.serverId = page.source;
                }
            });

            // Update the form with the file ids. Check if either the serverId or source of the page is equal to the pageToBeDeleted
            this.form.pages = this.form.pages.filter(page => page.serverId != this.pageToBeDeleted);

            

            // console.log(this.form.pages);
        },
        handleFilePondPagesBeforeRemoveFile(item) {

            // Return a promise that prints 'hi' to the console when resolved
            return new Promise((resolve, reject) => {

                // if item doesn't have a serverId, set this.pageToBeDeleted to empty string
                // console.log(item.serverId)

                this.pageToBeDeleted = item.serverId;

                // resolve with true to remove item from pond
                resolve(true);
            });
        },
        handleFilePondPagesReorderFiles(files, origin, target) {

            // Clear form.pages
            this.form.pages = [];

            // Loop through files and push to form.pages
            files.forEach(file => {
                this.form.pages.push({
                    serverId: file.serverId,
                });
            });

            // log all file names
            // files.forEach(file => console.log(file.filename));

        },
        submitForm() {
            this.form.pages.forEach(page => {

                // If serverId doesn't exist, create a serverId
                if (!page.hasOwnProperty('serverId')) {
                    page.serverId = page.source;
                }
            });


            this.form.put(route('chapters.update', this.form.chapter_id));
        },
    },
    mounted() {
        this.form.chapter_id = this.chapter.chapter_id;

        this.form.chapter_title = this.chapter.chapter_title;
        this.form.chapter_notes = this.chapter.chapter_notes;
        this.form.scheduled_publish = this.chapter.scheduled_publish;

        this.form.pages = this.passedChapterPages;

        // Set thumbnail and pages, possibly by calling an API endpoint

    },
}
</script>