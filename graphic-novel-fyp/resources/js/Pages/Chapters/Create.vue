<template>
    <form @submit.prevent="form.post(route('chapters.store'))" class="p-10 flex flex-col items-center gap-5">
        <div>
            <p class="font-bold text-2xl md:text-3xl">Create Chapter</p>
        </div>

        <div class="flex flex-col gap-5 md:w-full">
            <!-- Dropdown for all universes. When a universe is selected, call updateSeries, passing in the universe ID -->
            <div>
                <Label>Universe</Label>
                <select v-model="form.universe_id" class="border-2 border-black rounded-md p-2"
                    @change="updateSeries(form.universe_id)">
                    <option v-for="universe in universes" :key="universe.universe_id" :value="universe.universe_id">
                        {{ universe.universe_title }}
                    </option>
                </select>
            </div>


            <!-- Dropdown for all series -->
            <div>
                <Label>Series</Label>
                <select v-model="form.series_id" class="border-2 border-black rounded-md p-2">
                    <option v-for="series in series" :key="series.series_id" :value="series.series_id">
                        {{ series.series_title }}
                    </option>
                </select>
            </div>

            <div>
                <BaseInput for="chapter_title" v-model="form.series_title" label="Chapter Title" type="text" />
            </div>

            <div>
                <TextAreaInput for="chapter_notes" v-model="form.series_summary" label="Chapter Notes" type="text" />
            </div>

            <div>
                <Label>Chapter Thumbnail</Label>
                <ImageLabel />
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

            <!-- Create a checkbox to enable/disable comments -->
            <div>
                <Label>Enable Comments</Label>
                <input type="checkbox" v-model="form.comments_enabled" />
            </div>

            <!-- Create a radio input between immediate publish and scheduled publish.If scheduled is selected, make these pickers accessible -->
            <div>
                <Label>Publish</Label>
                <div class="flex flex-row gap-5">
                    <div>
                        <input type="radio" v-model="form.scheduled_publish" value="immediate" />
                        <Label>Immediate</Label>
                    </div>

                    <div>
                        <input type="radio" v-model="form.scheduled_publish" value="scheduled" />
                        <Label>Scheduled</Label>
                    </div>
                </div>
            </div>

            <!-- Create a date and time picker that only appears when Scheduled is selected -->
            <div>
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

export default {
    props: {
        // universe is an object
        passedUniverse: {
            type: Object,
        },
        // series is an object
        passedSeries: {
            type: Object,
        },
    },
    data() {
        return {
            universes: [],
            series: [],
            selected: {
                universe: '',
                series: '',
            }
        }
    }
    ,
    setup() {
        const form = useForm({
            series_id: '',
            chapter_title: '',
            chapter_thumbnail: '',
            chapter_notes: '',
            comments_enabled: '',
            scheduled_publish: '',
        });

        return {
            form,
        }
    },
    methods: {

        // Make a call to the API to get all the series, passing in a universe ID
        updateSeries(universe_id) {
            axios.get('/api/series/' + universe_id).then(response => {
                this.series = response.data
                this.selected.series = this.series[0].series_id
            }).catch(error => console.log(error))
        }
    },
    mounted() {

        // Make a call to the API to get all the universes, passing in the user's ID
        axios.get('/api/universes/' + this.$attrs.auth.user.id).then(response => {
            this.universes = response.data

            this.updateSeries(this.universes[0].universe_id)

            this.selected.universe = this.universes[0].universe_id
        }).catch(error => console.log(error))

        // If universe is passed in, set the selected universe to the universe ID
        if (this.universe) {
            // this.selected.universe = this.passedUniverse.universe_id
            this.form.universe_id = this.passedUniverse.universe_id

        }

        // If series is passed in, set the selected series to the series ID
        if (this.series) {
            // this.selected.series = this.passedSeries.series_id
            this.form.series_id = this.passedSeries.series_id

        }
    }
}
</script>