import { VueRenderer } from '@tiptap/vue-3'
import tippy from 'tippy.js'

import MentionList from '@/Components/MentionList.vue'
import APICalls from '@/Utilities/APICalls'

let list = []

export default {
  items: async ({ query }) => {
    // This is the list of items that will be passed to the MentionList component
    // we can do all this, or we can simply call the api here
    // console.log(query)
    // return ['test', 'test2', 'test3', 'test4', 'test5']

    return APICalls.searchElements(query, 5)
    
    // .then((response) => {
    //     // console.log(response.data)
    //     list = response.data
    //     // return response.data
    // }
    // ).catch((error) => {
    //     console.log(error)
    // })

    // return list

  },
//   command: (object) => {
//     console.log('command')
//     console.log(object)
//   },

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
        // console.log('exit')
        // console.log(popup)
        // popup[0].destroy()
        // component.destroy()

        if(popup){
            popup[0].destroy()
        }
        if(component){
            component.destroy()
        }

        popup = null
        component = null
      },
    }
  },
}