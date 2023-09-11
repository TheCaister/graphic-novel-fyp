<template>
    <h1>
        Here is a nice list of elements
    </h1>

    <div v-if="elements.length === 0">
        <p>
            No elements found
        </p>
    </div>

    <div v-else>
        <ul>
            <li v-for="element in elements">
                <div class="flex">
                    <input type="checkbox" :id="element.element_id" :name="element.element_id" :value="element.element_id"
                        @change="check(element.element_id, $event)" :checked="value.includes(element.element_id)">
                    <label :for="element.element_id">
                        {{ element.element_id }}
                        <img src="" alt="Page image">
                    </label>

                    <div v-if="value.some(e => e.optionId === element.element_id)">
                        <!-- Add an 'assign' checkbox -->
                        <input type="checkbox" :id="`assign-${element.element_id}`" :name="`assign-${element.element_id}`"
                            :value="`assign-${element.element_id}`" @change="checkAssign(element.element_id, $event)"
                            :checked="checkIfAssigned(element.element_id)">
                        <label :for="`assign-${element.element_id}`">
                            Assign?
                        </label>
                    </div>

                    <Link :href="route('elements.show', element.element_id)">
                    >
                    </Link>
                </div>

            </li>
        </ul>

        <ul>
            <li v-for="element in elements">
                <div class="flex">
                    <input type="checkbox" :id="element.element_id" :name="element.element_id" :value="element.element_id"
                        @change="checkAssign(element.element_id, $event)" :checked="value.includes(element.element_id)">
                    <label :for="element.element_id">
                        {{ element.element_id }}
                        <img src="" alt="Page image">
                    </label>

                    <Link :href="route('elements.show', element.element_id)">
                    >
                    </Link>
                </div>

            </li>
        </ul>
    </div>

    <!-- Add two buttons to either set all "assign" to true or false -->
    <div v-if="value.length > 0">
        <PrimaryButton @click="setAllAssign(true)">
            Assign All
        </PrimaryButton>
        <PrimaryButton @click="setAllAssign(false)">
            Unassign All
        </PrimaryButton>
    </div>

    <div>
        <Link :href="route('elements.create', {
            contentType: contentType,
            contentId: contentId
        })">
        Add new element
        </Link>
    </div>
</template>

<script>
export default {
    props: {
        elements: {
            type: Array,
            required: true
        },
        contentType: {
            type: String,
            required: true
        },
        contentId: {
            type: Number,
            required: true
        },
        value: {
            type: Array,
            required: true
        },
    },

    setup(props, context) {
        const check = (optionId, checked) => {



            // copy the value Array to avoid mutating props
            let updatedValue = [...props.value];
            // remove name if checked, else add name
            if (checked.target.checked) {
                updatedValue.push({
                    optionId: optionId,
                    assign: false
                });
            } else {
                updatedValue.splice(updatedValue.indexOf(optionId), 1);
            }
            // emit the updated value
            context.emit("update:value", updatedValue);
        };

        const checkAssign = (optionId, checked) => {

            // console.log(checked.target.checked)

            // I want to cycle between off, on and indeterminate

            // Find the element in value that matches the optionId, and add an 'assign' property to it. Depending on checked, assign true or false
            let updatedValue = [...props.value];
            let element = updatedValue.find(element => element.optionId === optionId);

            console.log(element)

            if (element) {
                console.log("element found")
                if (element.assign) {
                    // Set to indeterminate
                    checked.target.indeterminate = true;
                }

                element.assign = checked.target.checked;
            }
            else {
                // checked.target.checked = false
            }
          

            // emit the updated value
            context.emit("update:value", updatedValue);
        };

        const setAllAssign = (assign) => {
            console.log(props.value)

            // copy the value Array to avoid mutating props
            let updatedValue = [...props.value];

            // Loop through the updatedValue and set all assign to true
            updatedValue.forEach(element => {
                element.assign = assign;
            });

            // emit the updated value
            context.emit("update:value", updatedValue);
        }

        const checkIfAssigned = (optionId) => {
            // Check if the element is assigned
            let updatedValue = [...props.value];
            let element = updatedValue.find(element => element.optionId === optionId);

            return element.assign;
        }

        return {
            check,
            checkAssign,
            setAllAssign,
            checkIfAssigned
        };
    },
}
</script>