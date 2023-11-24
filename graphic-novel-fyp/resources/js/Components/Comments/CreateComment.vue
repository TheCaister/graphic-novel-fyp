<template>
    <div class="flex gap-5">
        <form @submit.prevent="submit">
            <div class="w-1/2">
                <TextAreaInput label="Comment" v-model="this.form.comment_content"></TextAreaInput>
            </div>

            <div>
                <p>
                    Comment: {{ this.form.comment_content }}
                </p>
            </div>

            <div>

                <!-- POST button that when clicked, calls the submitComment method -->
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full"
                    @click="submitComment">POST</button>

            </div>

        </form>
    </div>
</template>

<script>
import TextAreaInput from "../Forms/TextAreaInput.vue";
import { useForm, usePage } from '@inertiajs/vue3';

export default {
    components: {
        TextAreaInput
    },
    props: {
        commentableType: String,
        commentableId: Number
    },
    emits: ['commentCreated'],
    data() {
        return {
            form: useForm({
                commenter_id: "",
                comment_content: "",
                commentable_type: "",
                commentable_id: ""
            }),
        }
    },
    methods: {
        submit() {
            this.form.post(route('comments.store'), {
                preserveScroll: true,
                onSuccess: () => {
                    // Clear the comment field
                    this.form.comment_content = "";
                    // Emit the commentCreated event
                    this.$emit('commentCreated');
                }
            });

            // console.log(this.form.comment)
        }
    },
    mounted() {
        const page = usePage()

        // Set the commentable type and id
        this.form.commentable_type = this.commentableType;
        this.form.commentable_id = this.commentableId;
        this.form.commenter_id = page.props.auth.user.id;
    },

}
</script>