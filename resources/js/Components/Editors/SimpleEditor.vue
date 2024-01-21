<template>
    <div v-if="editor" class="flex flex-wrap space-x-2">
        <button @click="editor.chain().focus().toggleBold().run()"
            :disabled="!editor.can().chain().focus().toggleBold().run()"
            :class="[editor.isActive('bold') ? 'is-active' : '', 'not-active']">
            bold
        </button>
        <button @click="editor.chain().focus().toggleItalic().run()"
            :disabled="!editor.can().chain().focus().toggleBold().run()"
            :class="[editor.isActive('italic') ? 'is-active' : '', 'not-active']">
            italic
        </button>
        <button @click="editor.chain().focus().toggleStrike().run()"
            :disabled="!editor.can().chain().focus().toggleBold().run()"
            :class="[editor.isActive('strike') ? 'is-active' : '', 'not-active']">
            strike
        </button>
        <button @click="editor.chain().focus().unsetAllMarks().run()" class="not-active">
            clear marks
        </button>
        <button @click="editor.chain().focus().clearNodes().run()" class="not-active">
            clear nodes
        </button>
        <button @click="editor.chain().focus().setParagraph().run()"
            :class="[editor.isActive('paragraph') ? 'is-active' : '', 'not-active']">
            paragraph
        </button>
        <button @click="editor.chain().focus().toggleHeading({ level: 1 }).run()"
            :class="[editor.isActive('heading', { level: 1 }) ? 'is-active' : '', 'not-active']">
            h1
        </button>
        <button @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
            :class="[editor.isActive('heading', { level: 2 }) ? 'is-active' : '', 'not-active']">
            h2
        </button>
        <button @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
            :class="[editor.isActive('heading', { level: 3 }) ? 'is-active' : '', 'not-active']">
            h3
        </button>
        <button @click="editor.chain().focus().toggleHeading({ level: 4 }).run()"
            :class="[editor.isActive('heading', { level: 4 }) ? 'is-active' : '', 'not-active']">
            h4
        </button>
        <button @click="editor.chain().focus().toggleHeading({ level: 5 }).run()"
            :class="[editor.isActive('heading', { level: 5 }) ? 'is-active' : '', 'not-active']">
            h5
        </button>
        <button @click="editor.chain().focus().toggleHeading({ level: 6 }).run()"
            :class="[editor.isActive('heading', { level: 6 }) ? 'is-active' : '', 'not-active']">
            h6
        </button>
        <button @click="editor.chain().focus().toggleBulletList().run(); removePfromList()"
            :class="[editor.isActive('bulletList') ? 'is-active' : '', 'not-active']">
            bullet list
        </button>
        <button @click="editor.chain().focus().toggleOrderedList().run(); removePfromList()"
            :class="[editor.isActive('orderedList') ? 'is-active' : '', 'not-active']">
            ordered list
        </button>
        <button @click="editor.chain().focus().toggleBlockquote().run()"
            :class="[editor.isActive('blockquote') ? 'is-active' : '', 'not-active']">
            blockquote
        </button>
        <button @click="editor.chain().focus().setHorizontalRule().run()" class="not-active">
            horizontal rule
        </button>
        <button @click="editor.chain().focus().undo().run()" :disabled="!editor.can().chain().focus().undo().run()"
            class="not-active">
            undo
        </button>
        <button @click="editor.chain().focus().redo().run()" :disabled="!editor.can().chain().focus().redo().run()"
            class="not-active">
            redo
        </button>
    </div>

    <div class="bg-black text-white">
        <editor-content class="p-4 editor-field" :editor="editor" @itemSelected="itemSelected" />
    </div>
</template>

<script setup>

function itemSelected(props) {
    console.log(props.id.element_id)
}
</script>
  
<script>
import Mention from '@tiptap/extension-mention'
import StarterKit from '@tiptap/starter-kit'
import { Editor, EditorContent } from '@tiptap/vue-3'
import suggestion from './suggestion'



export default {

    components: {
        EditorContent,
    },

    props: {
        modelValue: {
            type: Object,
        },
    },

    emits: ['update:modelValue'],

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

            this.editor.commands.setContent(value, false)
        },
    },

    methods: {
        removePfromList() {
            const newHTML = this.editor.getHTML().replaceAll(/<li><p>(.*?)<\/p><(\/?)(ol|li|ul)>/gi, "<li>$1<$2$3>")

            this.editor.commands.setContent(newHTML, false)
        },
        testing(event) {
            console.log(event)
            console.log('testing')
        },
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
                        onclick: `alert(testing())`,
                    },
                    `${node.attrs.label ?? node.attrs.id.element_name}`,
                ];
            },
        });

        this.editor = new Editor({
            extensions: [
                StarterKit.configure(
                    {
                        bulletList: {
                            HTMLAttributes: {
                                class: 'list-disc list-inside list',
                            },
                        },
                        orderedList: {
                            HTMLAttributes: {
                                class: 'list-decimal list-inside list',
                            },
                        },
                        blockquote: {
                            HTMLAttributes: {
                                class: 'border-l-4 border-gray-400 pl-4',
                            },
                        },
                        horizontalRule: {
                            HTMLAttributes: {
                                class: 'border-0 border-t-2 border-gray-400 my-4',
                            },
                        },
                    }
                ),
                CustomMention.configure({
                    HTMLAttributes: {
                        class: 'mention',
                        onclick: 'alert(event.target.getAttribute("data-id"))',
                        // onclick: 'testing(event)',
                    },
                    renderLabel({ options, node }) {
                        console.log(node)

                        // node.attrs.id is the element object that is returned when a suggestion is selected.
                        return `${options.suggestion.char}${node.attrs.label ?? node.attrs.id.element_name}`
                    },
                    suggestion,
                }),
            ],
            content: this.modelValue,
            onUpdate: () => {

                // this.editor.commands.setContent(, false)

                // console.log(this.editor.getHTML().replaceAll(/<li><p>(.*?)<\/p><(\/?)(ol|li|ul)>/gi, "<li>$1<$2$3>"))
                // HTML
                // Emit only the text in the editor, excluding the HTML tags
                // this.$emit('update:modelValue', this.editor.getHTML().replace(/<[^>]+>/g, ''))

                // this.$emit('update:modelValue', this.editor.getHTML())

                // JSON
                // this.$emit('update:modelValue', this.editor.getJSON())

                // this.editor.commands.setContent(before, false)

                console.log(this.editor.getJSON())

                // In before.content, Remove any entry that has an null text field
                // Go through each node in the content array

                this.$emit('update:modelValue', JSON.parse(JSON.stringify(this.editor.getJSON())))
                // console.log(this.editor.getJSON());
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

ul>li {
    margin-left: 20px;
}

ol>li {
    margin-left: 20px;
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

.list p {
    display: inline;
}

.ProseMirror:focus {
    outline: none;
}
</style>