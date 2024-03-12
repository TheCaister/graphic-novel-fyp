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
        <editor-content class="p-4 editor-field" :editor="editor" @click="updateMouseClickPosition($event)" />
    </div>

    <div id="editorHolder" :style="{ position: 'fixed', top: mouseClickY + 'px', left: mouseClickX + 'px' }"
        class=" z-10"></div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, ref, watch, defineProps, defineEmits } from 'vue'
import Mention from '@tiptap/extension-mention'
import StarterKit from '@tiptap/starter-kit'
import suggestion from './suggestion'
import { Editor, EditorContent, VueNodeViewRenderer } from '@tiptap/vue-3'

import MentionItem from './MentionItem.vue'

const props = defineProps({
    modelValue: {
        type: Object,
    },
})

const emits = defineEmits(['update:modelValue'])

const editor = ref(null)

const mouseClickX = ref(0)
const mouseClickY = ref(0)

const removePfromList = () => {
    const newHTML = editor.value.getHTML().replaceAll(/<li><p>(.*?)<\/p><(\/?)(ol|li|ul)>/gi, "<li>$1<$2$3>")
    editor.value.commands.setContent(newHTML, false)
}

const CustomMention = Mention.extend({
    addNodeView() {
        return VueNodeViewRenderer(MentionItem)
    },
});

function updateMouseClickPosition(event) {
    mouseClickX.value = event.clientX;
    mouseClickY.value = event.clientY;
}

onMounted(() => {
    // console.log('editor mounted')
    editor.value = new Editor({
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
                suggestion,
            }),
        ],
        content: props.modelValue,
        onUpdate: () => {
            emits('update:modelValue', JSON.parse(JSON.stringify(editor.value.getJSON())))
        },
    })
})

onBeforeUnmount(() => {
    editor.value.destroy()
})

// This watcher fixes the bug where the cursor jumps to the end of the editor when the modelValue changes
watch(() => props.modelValue, (value) => {

    // JSON
    const isSame = JSON.stringify(editor.value.getJSON()) === JSON.stringify(value)

    if (isSame) {
        return
    }

    editor.value.commands.setContent(value, false)
})
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