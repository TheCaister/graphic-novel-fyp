<template>
    <div class="bg-black text-white">
        <p class="block font-medium text-sm text-gray-700 dark:text-gray-300">
            Type '@' to start adding admins
        </p>
        <editor-content class="p-4 border border-gray-700 rounded-md" :editor="editor" @itemSelected="itemSelected" @removeMentionItem="emits('removeAdmin', $event)" />
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

// const props = defineProps({
//     adminList: {
//         type: Array,
//         default: []
//     }
// })

const adminList = defineModel({
    default: [],
})

const emits = defineEmits(['addAdmin', 'removeAdmin'])

const editor = ref(null)

const CustomMention = Mention.extend({
    addNodeView() {
        return VueNodeViewRenderer(MentionItemDeletable)
    },
    addOptions() {
        return {
            suggestion: {
                items: async ({ query }) => {
                    const mentionItems = await APICalls.searchMention(query, 5, 'users')
                    const mentionList = mentionItems.data.map(item => ({
                        label: item.username,
                        id: item.id
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

                    console.log('inserting admin...')

                    if(!adminList.value.some(admin => admin.id === props.id.id)){
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
                    }

                  

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

                            // console.log(props)

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

function itemSelected(props) {
    emits('addAdmin', props.id)
    // editor.value.commands.setContent('', false)
}

onMounted(() => {


    editor.value = new Editor({
        extensions: [
            Document,
            Text,
            Paragraph,
            CustomMention.configure({
                renderLabel(){
                    return ''
                }
            }),
        ],
    })

})

onUpdated(() => {

    editor.value.commands.setContent('', false)

    adminList.value.forEach(admin => {
        console.log(admin.id, admin.username)
        editor.value.commands.insertContent({
            type: 'mention',
            attrs: {
                id: {
                    id: admin.id,
                    label: admin.username || admin.label,
                }
            },
        })
    })
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


    border: 1px solid #000;
    border-radius: 5px;

    padding: 5px 10px;
}

.is-active {
    background-color: #000;
    color: #fff;


    border: 5px solid #ff4b4b;
    border-radius: 5px;

    padding: 5px 10px;
}

button:disabled {
    color: #a1a1a1;


    border: 1px solid #a1a1a1;
    border-radius: 5px;

    padding: 5px 10px;
}

.ProseMirror:focus {
    outline: none;
}
</style>