<template>
    <form @submit.prevent="form.post(route('universes.store'))" class="p-10 flex flex-col items-center gap-5">
        <!-- Form Title -->
        <div>
            <p class="font-bold text-2xl md:text-3xl">Create Universe</p>


            <!-- <input type="text"> -->
        </div>

        <div class="flex flex-col gap-5 md:w-full">
            <div>
                <!-- <Label :forString="universe_name">Universe Name</Label> -->

                <!-- <TextInput v-model="universe.name" /> -->
                <BaseInput for="universe_name" v-model="form.universe_name" label="Universe Name" type="text" />

            </div>

            <div class="flex justify-center gap-2 md:gap-5">
                <!-- Make an a sending you back to the index of universes -->
                <a href="">
                    <SecondaryButton class="flex flex-row gap-1 items-center">
                        <span class="material-symbols-rounded">
                            arrow_back
                        </span>
                        Go Back
                    </SecondaryButton>
                </a>

                <PrimaryButton>Create Universe</PrimaryButton>
            </div>
        </div>
    </form>
</template>

<script>
import Label from '@/Components/Forms/Label.vue';
import TextInput from '@/Components/Forms/TextInput.vue';
import ImageLabel from '@/Components/Forms/ImageLabel.vue';
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue';
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue';
import BaseInput from '@/Components/Forms/BaseInput.vue';
import { useForm } from '@inertiajs/vue3';

export default {
    components: {
        Label,
        TextInput,
        BaseInput,
        ImageLabel,
        PrimaryButton,
        SecondaryButton
    },
    setup() {
        const form = useForm({
            universe_name: ''
        });

        return {
            form,
        }
    }
}

</script>