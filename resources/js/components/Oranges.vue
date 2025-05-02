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