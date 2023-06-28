<template>
    <form @submit.prevent="form.post(route('series.store'))" class="p-10 flex flex-col items-center gap-5">
        <div>
            <p class="font-bold text-2xl md:text-3xl">Create Series</p>
        </div>

        <div class="flex flex-col gap-5 md:w-full">
            <div>
                <BaseInput for="series_title" v-model="form.series_title" label="Series Title" type="text" />
            </div>

            <div>
                <TextAreaInput for="series_title" v-model="form.series_summary" label="Series Summary" type="text" />
            </div>

            <div>
                <Label>Series Thumbnail</Label>
                <ImageLabel/>
            </div>

            <!-- Dropdown for all universes -->


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
            ]
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
    props: {
        universes: {
            type: Array,
            required: true
        }
    }
}
</script>