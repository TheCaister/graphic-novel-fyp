<template>
    <div class="bg-black text-white">
        <editor-content class="p-4 editor-field border-4 border-white" :editor="editor" @itemSelected="itemSelected" />
    </div>
</template>
  
<script>
import Mention from '@tiptap/extension-mention'
import Paragraph from '@tiptap/extension-paragraph'
import { Editor, EditorContent } from '@tiptap/vue-3'
import suggestion from './SuggestionAdmin/suggestion'

import Document from '@tiptap/extension-document'
import Text from '@tiptap/extension-text'

export default {

    components: {
        EditorContent,
    },

    props: {
        modelValue: {
            type: Object,
        },
    },

    emits: ['update:modelValue', 'addAdminId'],

    data() {
        return {
            editor: null,
        }
    },

    watch: {
        modelValue(value) {
            // HTML
            // const isSame = this.editor.getHTML() === value

            // JSON
            const isSame = JSON.stringify(this.editor.getJSON()) === JSON.stringify(value)

            if (isSame) {
                return
            }

            console.log(this.editor.getJSON())

            this.editor.commands.setContent(value, false)
        },
    },

    methods: {
        removePfromList() {
            const newHTML = this.editor.getHTML().replaceAll(/<li><p>(.*?)<\/p><(\/?)(ol|li|ul)>/gi, "<li>$1<$2$3>")

            this.editor.commands.setContent(newHTML, false)
        },
        itemSelected(props) {
    this.$emit('addAdminId', props.id)
    console.log('Deleting...')
    this.editor.commands.setContent('', false)
}
    },

    mounted() {
        // Defining custom mention
        const CustomMention = Mention.extend({

            renderHTML(props) {
                const { node } = props;
                let id = node.attrs.id;
                return [
                    'button',
                    {
                        style: 'color: red; border: 2px solid pink;',
                        target: '_blank',
                        //   Onclick function
                    },
                    `${node.attrs.label ?? node.attrs.id.name}`,
                ];
            },
        });

        this.editor = new Editor({
            extensions: [
                Document,
                Text,
                Paragraph,
                CustomMention.configure({
                    HTMLAttributes: {
                        class: 'mention',
                    },
                    renderLabel({ options, node }) {
                        // console.log(node)

                        // node.attrs.id is the element object that is returned when a suggestion is selected.
                        // return `${options.suggestion_admin.char}${node.attrs.label ?? node.attrs.id.element_name}`
                        return `${options.suggestion.char}${node.attrs.label ?? node.attrs.id.name}`

                    },
                    // suggestion_admin,
                    suggestion,

                }),
            ],
            content: this.modelValue,
            onUpdate: () => {
                // console.log(JSON.parse(JSON.stringify(this.editor.getJSON())))
                // this.$emit('update:modelValue', JSON.parse(JSON.stringify(this.editor.getJSON())))
                // console.log('updated...')
            },
        })
    },

    beforeUnmount() {
        this.editor.destroy()
    },
}
</script>

<style>
/* Change how the mention looks here */
.mention {
    border: 3px solid #c62424;
    border-radius: 0.4rem;
    padding: 0.1rem 0.3rem;
    box-decoration-break: clone;
}

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