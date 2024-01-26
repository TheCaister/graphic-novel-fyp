<!-- This is created everytime the @ key is pressed. With items and commands passed in -->
<template>
  <div class="items">
    <template v-if="this.items.data.length">
      <!-- <button class="w-64 h-64" @click="console.log(this.items)">

      </button> -->
      <!-- <template v-if="items.length"> -->
      <!-- <button class="item" :class="{ 'is-selected': index === selectedIndex }" v-for="(item, index) in items" -->

      <button class="item" :class="{ 'is-selected': index === selectedIndex }" v-for="(item, index) in items.data"
        :key="index" @click="selectItem(index)">
        {{ item.name }}
      </button>
    </template>
    <div class="item" v-else>
      No result
    </div>
  </div>
</template>
  
<script>
export default {
  emits: ['selectItem'],
  props: {
    // The list of items to display
    items: {
      type: Object,
      required: true,
    },

    command: {
      type: Function,
      required: true,
    },
  },

  data() {
    return {
      selectedIndex: 0,
    }
  },

  watch: {
    items() {
      this.selectedIndex = 0
    },
  },

  methods: {
    // Handling navigation with the keyboard
    onKeyDown({ event }) {
      // console.log(event)
      if (event.key === 'ArrowUp') {
        this.upHandler()
        return true
      }

      if (event.key === 'ArrowDown') {
        this.downHandler()
        return true
      }

      if (event.key === 'Enter') {
        this.enterHandler()
        return true
      }

      return false
    },

    upHandler() {
      this.selectedIndex = ((this.selectedIndex + this.items.data.length) - 1) % this.items.data.length
      // this.selectedIndex = ((this.selectedIndex + this.items.length) - 1) % this.items.length

    },

    downHandler() {
      this.selectedIndex = (this.selectedIndex + 1) % this.items.data.length
      // this.selectedIndex = (this.selectedIndex + 1) % this.items.length

    },

    enterHandler() {
      this.selectItem(this.selectedIndex)
    },

    //   Gets the item at the given index and calls the command
    selectItem(index) {
      // console.log(this.items)

      const item = this.items.data[index]

      // const item = this.items[index]

      if (item) {
        this.command({ id: item })
      }
    },
  },
}
</script>
  
<style >
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