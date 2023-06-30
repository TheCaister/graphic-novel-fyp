<template>
    <form @submit.prevent="form.post(route('chapters.store'))" class="p-10 flex flex-col items-center gap-5">
        <div>
            <p class="font-bold text-2xl md:text-3xl">Create Chapter</p>
        </div>

        <div class="flex flex-col gap-5 md:w-full">
            <!-- Dropdown for all universes. When a universe is selected, call updateSeries, passing in the universe ID -->
            <div>
                <Label>Universe</Label>
                <select v-model="form.universe_id" class="border-2 border-black rounded-md p-2" @change="updateSeries(universes[0])">
                    <option v-for="universe in universes" :key="universe.universe_id" :value="universe.universe_id">
                        {{ universe.universe_name }}
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
                <ImageLabel />
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

export default {
    data() {
        return {
            universes: [],
            series: [],
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
            }).catch(error => console.log(error))
        }
    },
    mounted() {

        // Make a call to the API to get all the universes, passing in the user's ID
        axios.get('/api/universes/' + this.$attrs.auth.user.id).then(response => {
            this.universes = response.data
        }).catch(error => console.log(error))

        for(let i = 0; i < this.universes.length; i++) {
            this.updateSeries(this.universes[i].universe_id)
        }
    }
}
</script>