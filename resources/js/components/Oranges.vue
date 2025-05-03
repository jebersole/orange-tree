// This component is responsible for rendering a list of oranges, either on the tree or on the ground. 
// Each orange is draggable, and its position is determined by a random transformation applied when it is rendered. 
// The component accepts props for the list of oranges, whether they are on the ground, 
// and a function to handle the dragstart event. Transformations are stored to ensure consistent positioning.

<template>
    <div v-for="orange in oranges" :key="orange.id"
        :class="['orange', 'fallen']" :style="transform(orange)"
        @dragstart="startDragging(orange)" draggable="true">
    </div>
</template>

<script>
export default {
    data() {
        return {
            transformations: []
        };
    },
    name: 'Oranges',
    props: {
        onGround: {
            type: Boolean,
            required: true
        },
        oranges: {
            type: Array,
            required: true,
        },
        startDragging: {
            type: Function,
            required: true,
        },
    },
    methods: {
        addTransformation(orange) {
            const transformation = `transform: translate(${Math.random() * 200}px, ${Math.random() * (orange.on_ground ? -50 : -100)}px)`;
            this.transformations.push({id: orange.id, transformation});
            return transformation;
        },
        getTransformation(orange) {
            return this.transformations.find(t => t.id === orange.id);
        },
        transform(orange) {
            const transformation = this.getTransformation(orange);
            if (transformation) return transformation.transformation;
            return this.addTransformation(orange);
        }
    }
};
</script>