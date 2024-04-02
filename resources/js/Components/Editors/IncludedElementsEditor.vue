<template>
    <div class="bg-black text-white">
        <p class="block font-medium text-sm text-gray-700 dark:text-gray-300">
            Included elements:
        </p>
        <editor-content class="p-4 border border-gray-700 rounded-md" :editor="editor" @itemSelected="itemSelected"
            @removeMentionItem="removeMentionItem" />
    </div>
</template>

<script setup>
import { ref, watch, onMounted, onBeforeUnmount, onUpdated } from 'vue'
import Mention from '@tiptap/extension-mention'
import Paragraph from '@tiptap/extension-paragraph'
import { Editor, EditorContent, VueNodeViewRenderer } from '@tiptap/vue-3'
import Document from '@tiptap/extension-document'
import Text from '@tiptap/extension-text'
import { defineProps, defineEmits } from 'vue'
import MentionItemDeletable from './MentionItemDeletable.vue'

import { VueRenderer } from '@tiptap/vue-3'
import tippy from 'tippy.js'

import MentionList from '@/Components/MentionList.vue'
import APICalls from '@/Utilities/APICalls'

const props = defineProps({
    // modelValue: {
    //     type: Object,
    // },
    includedElements: {
        type: Array,
        default: []
    }
})

// const emits = defineEmits(['update:modelValue', 'addElement', 'removeElement'])
const emits = defineEmits(['addMentionItem', 'removeMentionItem'])



const editor = ref(null)

const CustomMention = Mention.extend({
    addNodeView() {
        return VueNodeViewRenderer(MentionItemDeletable)
    },
    addOptions() {
        return {
            suggestion: {
                items: async ({ query }) => {
                    // This is the list of items that will be passed to the MentionList component
                    // we can do all this, or we can simply call the api here
                    // return APICalls.searchElements(query, 5, 'users')

                    const mentionItems = await APICalls.searchMention(query, 5, 'elements')
                    // console.log(mentionItems)
                    const mentionList = mentionItems.data.map(item => ({
                        label: item.element_name,
                        id: item.element_id
                    }))

                    return mentionList
                },
                command: ({ editor, range, props }) => {
                    // increase range.to by one when the next node is of type "text"
                    // and starts with a space character
                    const nodeAfter = editor.view.state.selection.$to.nodeAfter
                    const overrideSpace = nodeAfter?.text?.startsWith(' ')

                    if (overrideSpace) {
                        range.to += 1
                    }

                    editor
                        .chain()
                        .focus()
                        .insertContentAt(range, [
                            {
                                type: 'mention',
                                attrs: props,
                            },
                            {
                                type: 'text',
                                text: ' ',
                            },
                        ])
                        .run()

                    editor.contentComponent.emit('itemSelected', props)

                    window.getSelection()?.collapseToEnd()
                },

                render: () => {
                    // Initialize the VueRenderer with the MentionList component
                    let component
                    let popup

                    return {

                        onStart: props => {
                            // We prepare a new VueRenderer instance with the MentionList component,
                            // and mount it to a DOM element. We also pass the editor instance and
                            // the props from the `suggest` method to the component.
                            // We need to set the editor because we want to call commands like
                            // `chain` or `insertContent` from the component.
                            component = new VueRenderer(MentionList, {
                                // using vue 2:
                                // parent: this,
                                // propsData: props,
                                // using vue 3:
                                props,
                                editor: props.editor,
                            })

                            // clientRect is the position of the caret
                            if (!props.clientRect) {
                                return
                            }

                            popup = tippy('body', {
                                getReferenceClientRect: props.clientRect,
                                appendTo: () => document.body,
                                content: component.element,
                                showOnCreate: true,
                                interactive: true,
                                //   determines the events that cause the tippy to show.
                                // With manual, the tippy must be triggered programmatically.
                                trigger: 'manual',
                                placement: 'bottom-start',
                            })
                        },

                        //   Updating the props
                        onUpdate(props) {
                            component.updateProps(props)

                            if (!props.clientRect) {
                                return
                            }

                            popup[0].setProps({
                                getReferenceClientRect: props.clientRect,
                            })
                        },

                        //   If the escape key is pressed, we hide the suggester.
                        // Otherwise we call the `onKeyDown` method of the VueRenderer instance.
                        onKeyDown(props) {
                            if (props.event.key === 'Escape') {
                                popup[0].hide()

                                return true
                            }

                            return component.ref?.onKeyDown(props)
                        },

                        //   When exiting the suggester, we destroy the VueRenderer instance.
                        onExit() {
                            // console.log('exit')
                            // console.log(popup)
                            // popup[0].destroy()
                            // component.destroy()

                            if (popup) {
                                popup[0].destroy()
                            }
                            if (component) {
                                component.destroy()
                            }

                            popup = null
                            component = null
                        },
                    }
                },
            }
        }
    },
})

watch(() => props.modelValue, (value) => {
    const isSame = JSON.stringify(editor.value.getJSON()) === JSON.stringify(value)

    if (!isSame) {
        editor.value.commands.setContent(value, false)
    }
}, { deep: true })

function itemSelected(props) {
    emits('addMentionItem', props.id)
    // editor.value.commands.setContent('', false)
}

function removeMentionItem(id) {
    emits('removeMentionItem', id)
}

// onUpdated(() => {
//     console.log('included elements...')
//     console.log(props.includedElements)
// })

onMounted(() => {
    // console.log('included elements...')
    // console.log(props.includedElements)


    editor.value = new Editor({
        extensions: [
            Document,
            Text,
            Paragraph,
            CustomMention.configure({
                renderLabel() {
                    return 'hi guys'
                },
            }),
        ],
        // content: props.modelValue,
        onUpdate: () => {
            // emits('update:modelValue', JSON.parse(JSON.stringify(editor.value.getJSON())))
        },
    })

    // editor.value.commands.insertContent([
    //     {
    //         type: 'mention',
    //         attrs: {
    //             id: {
    //                 id: 1,
    //                 label: 'hi guys'
    //             }
    //         }
    //     }
    // ])

    // for loop over props.includedElements and add them to the editor
    // props.includedElements.forEach((element) => {
    //     console.log('inserting!!')
    //     editor.value.commands.insertContentAt(editor.value.state.selection.$to, [
    //         // {
    //         //     type: 'mention',
    //         //     attrs: {
    //         //         id: {
    //         //             id: element.id,
    //         //             label: element.label
    //         //         }
    //         //     }
    //         // },
    //         // {
    //         //     type: 'text',
    //         //     text: ' ',
    //         // },
    //         {}
    //     ])
    // })
})

onBeforeUnmount(() => {
    if (editor.value) {
        editor.value.destroy()
    }
})
</script>

<style>
.not-active {
    background-color: #fff;
    color: #000;

    /* Add border with radius */
    border: 1px solid #000;
    border-radius: 5px;

    padding: 5px 10px;
}

.is-active {
    background-color: #000;
    color: #fff;

    /* Add border with radius */
    border: 1px solid #000;
    border-radius: 5px;

    padding: 5px 10px;
}

button:disabled {
    color: #a1a1a1;

    /* Add border with radius */
    border: 1px solid #a1a1a1;
    border-radius: 5px;

    padding: 5px 10px;
}

.ProseMirror:focus {
    outline: none;
}
</style>