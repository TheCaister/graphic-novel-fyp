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
// import axios from 'axios';

export default {
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
        });

        return {
            form,
        }
    },
    mounted() {

        // Make a call to the API to get all the universes, passing in the user's ID
        axios.get('/api/universes/' + this.$attrs.auth.user.id).then(response => {
            this.universes = response.data
        }).catch(error => console.log(error))
    }
}
</script>