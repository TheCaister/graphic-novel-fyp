<template>
    <form method="POST" action="/organisations" enctype="multipart/form-data" class="p-10 flex flex-col items-center gap-5">
        <!-- Form Title -->
        <div>
            <p class="font-bold text-2xl md:text-3xl">Create Universe</p>


            <!-- <input type="text"> -->
        </div>

        <div class="flex flex-col gap-5 md:w-full">
            <div>
                <Label :forString="universe_name">Universe Name</Label>

                <TextInput />
            </div>

            <!-- Universe Picture -->
            <div>
                <Label :forString="universe_picture">Universe Picture</Label>
                <div>
                    <ImageLabel />
                </div>

            </div>
        </div>
    </form>
</template>

<script>
import Label from '@/Components/Forms/Label.vue';
import TextInput from '@/Components/Forms/TextInput.vue';
import ImageLabel from '@/Components/Forms/ImageLabel.vue';

export default {
    components: {
        Label,
        TextInput,
        ImageLabel
    },
    props: {
        universe_name: {
            type: String,
            required: true
        }
    }
}

</script>