<template>
    <Dialog :open="props.isOpen" @close="$emit('close')" class="relative z-50">
        <div class="fixed inset-0 bg-black/30" aria-hidden="true"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <DialogPanel class="bg-white p-6 rounded-xl">
                <DialogTitle>Rate Series</DialogTitle>
                <DialogDescription>
                    Rate the series out of 5
                </DialogDescription>

                <!-- 6 choices from 0 to 5 and model it to "form" -->
                <div class="flex flex-row gap-2">
                    <input type="radio" value="0" v-model="form.rating">
                    <label for="rating-0">0</label>
                    <input type="radio" value="1" v-model="form.rating">
                    <label for="rating-1">1</label>
                    <input type="radio" value="2" v-model="form.rating">
                    <label for="rating-2">2</label>
                    <input type="radio" value="3" v-model="form.rating">
                    <label for="rating-3">3</label>
                    <input type="radio" value="4" v-model="form.rating">
                    <label for="rating-4">4</label>
                    <input type="radio" value="5" v-model="form.rating">
                    <label for="rating-5">5</label>
                </div>

                <button @click="submitRating()">Rate</button>
                <button @click="$emit('close')">Cancel</button>
            </DialogPanel>
        </div>

    </Dialog>
</template>
  
<script setup>
import { ref } from 'vue'
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    DialogDescription,
} from '@headlessui/vue'
import { useForm, usePage } from '@inertiajs/vue3';

// const isOpen = ref(true)
const page = usePage()

const props = defineProps({
    series: Object,
    isOpen: Boolean,
})

const emit = defineEmits(['close'])

const form = useForm({
    rating: '',
    series_id: props.series.series_id,
    user_id: page.props.auth.user.id
})

function setIsOpen(value) {
    props.isOpen = value
}

function submitRating() {
    emit('close');
    form.post(route('series.rate'), {
        preserveScroll: true,
        onSuccess: () => {
            form.rating = "";
            
        }
    });
    emit('close')
}
</script>