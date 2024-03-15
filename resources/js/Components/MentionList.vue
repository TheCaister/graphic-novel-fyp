<!-- This is created everytime the @ key is pressed. With items and commands passed in -->
<template>
  <div class="items" id="mention-list">

    <template v-if="(props.items && props.items.length > 0)">
      <button class="item" id="mention-list" :class="{ 'is-selected': index === selectedIndex }"
        v-for="(item, index) in items" :key="index" @click="selectItem(index)" @mouseover="selectedIndex = index">
        {{ item.label }}
      </button>
    </template>
    <div class="item" v-else>
      No result
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted, defineExpose } from 'vue'

defineExpose({
  selectItem,
  onKeyDown,

})

// Props are declared with defineProps
const props = defineProps({
  items: {
    type: Object,
    required: true,
  },
  command: {
    type: Function,
    required: true,
  },
})

// Refs are used for reactive variables
const selectedIndex = ref(0)

// Watchers are set up with the watch function
watch(() => props.items, (newValue, oldValue) => {
  console.log(newValue)
  selectedIndex.value = 0
}, { deep: true })

// Methods are just regular functions inside <script setup>
function onKeyDown(event) {
  if (event.event.key === 'ArrowUp') {
    upHandler()
    return true
  }

  if (event.event.key === 'ArrowDown') {
    downHandler()
    return true
  }

  if (event.event.key === 'Enter') {
    enterHandler()
    return true
  }

  return false
}

function upHandler() {
  selectedIndex.value = ((selectedIndex.value + props.items.length) - 1) % props.items.length
}

function downHandler() {
  selectedIndex.value = (selectedIndex.value + 1) % props.items.length
}

function enterHandler() {
  selectItem(selectedIndex.value)
}

function selectItem(index) {
  const item = props.items[index]
  if (item) {
    props.command({ id: item })
  }
}

// If you need to use lifecycle hooks, import them from 'vue'
// For example, to use onMounted:
/*
onMounted(() => {
  console.log('Component is mounted')
})
*/

</script>


<style>
.items {
  padding: 0.2rem;
  position: relative;
  border-radius: 0.5rem;
  background: #FFF;
  color: rgba(0, 0, 0, 0.8);
  overflow: hidden;
  font-size: 0.9rem;
  box-shadow:
    0 0 0 1px rgba(0, 0, 0, 0.05),
    0px 10px 20px rgba(0, 0, 0, 0.1),
  ;
}

.item {
  display: block;
  margin: 0;
  width: 100%;
  text-align: left;
  background: transparent;
  border-radius: 0.4rem;
  border: 1px solid transparent;
  padding: 0.2rem 0.4rem;

  &.is-selected {
    border-color: #000;
  }
}
</style>