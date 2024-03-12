<template>
    <node-view-wrapper class="inline-block text-pink-500">
        <button id="editorHolder" @click="isGridMenuOpen = true;">

            {{ node.attrs.id.element_name }}

            <Teleport to="#editorHolder">
                <Transition name="fade">
                    <DashboardDropdownMenu v-if="isGridMenuOpen" :events="dropDownMenuOptions"
                        @menuItemClick="handleMenuItemClicked($event, item)" @closeMenu="isGridMenuOpen = false" />
                </Transition>
            </Teleport>
        </button>
    </node-view-wrapper>
</template>

<script setup>
import { NodeViewWrapper } from '@tiptap/vue-3'
import APICalls from '@/Utilities/APICalls';
import DashboardDropdownMenu from '../../Pages/Dashboard/DashboardDropdownMenu.vue';
import { defineEmits, ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3';

const isGridMenuOpen = ref(false)

function setElementName(element_id) {
    APICalls.getElement(element_id).then(response => {
        console.log('returning')
        // return response.data.element_name
        console.log(response.data.element_name)
        props.updateAttributes({
            id: {
                ...props.node.attrs.id,
                element_name: response.data.element_name
            }
        })
    }).catch(error => console.log(error))
}

const mouseClickX = ref(0)
const mouseClickY = ref(0)

const dropDownMenuOptions = [
    { id: 1, text: "Visit", eventName: "visit" },
    { id: 2, text: "Search", eventName: "search" },
]

const props = defineProps(
    {
        node: {
            type: Object,
            required: true,
        },
        updateAttributes: {
            type: Function,
            required: true,
        },

    }
)


function handleMenuItemClicked(event, grid) {


    // switch statement for event
    switch (event) {
        case 'visit':
            router.visit(route('elements.edit', props.node.attrs.id.element_id))
            break
        default:
            console.log('default')
    }

    isGridMenuOpen.value = false
}

onMounted(() => {
    // console.log('mounted')
    // console.log(props.node.attrs.id)
    setElementName(props.node.attrs.id.element_id)
})
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s, transform 0.2s;
}

.fade-enter-from {
    opacity: 0;
    transform: translateY(-20px);
}

.fade-leave-to {
    opacity: 0;
    transform: translateY(-20px);
}

.fade-enter-to {
    opacity: 1;
    transform: translateY(0);
}

.material-symbols-outlined {
    font-variation-settings:
        'FILL' 0,
        'wght' 500,
        'GRAD' 0,
        'opsz' 40;
    font-size: 42px;
}

.modal-enter-active,
.modal-leave-active {
    transition: all 0.25s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
    transform: scale(1.1);
}
</style>