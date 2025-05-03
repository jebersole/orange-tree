// This component represents the main Orange Tree interface. It manages the state of the tree, 
// including oranges on the tree, oranges on the ground, and oranges in the bucket. 
// It interacts with the backend API to load the tree, move oranges to the bucket, 
// eat oranges, and advance the season. The component also uses the Oranges child component 
// to render individual oranges on the tree and ground.

<template>
    <div class="orange-tree-container" :class="currentSeason">
        <div class="tree-container">
            <div class="tree">
                <div class="trunk"></div>
                <div class="foliage">
                    <Oranges :oranges="oranges" :onGround="false" :startDragging="startDragging"/>
                </div>
            </div>

            <div class="ground">
                <div class="grass"></div>
                <Oranges :oranges="orangesOnGround" :onGround="true" :startDragging="startDragging"/>
            </div>
        </div>

        <div class="bucket-container" @dragover.prevent @drop="dropInBucket">
            <div class="bucket">
                <div class="bucket-top"></div>
                <div class="bucket-body"></div>
            </div>
            <div class="orange-count">Oranges: {{ bucket.length }}</div>
        </div>

        <div class="controls">
            <button @click="eatOrange" :disabled="!bucket.length" class="eat-button">
                Eat an Orange
            </button>
            <button @click="advanceSeason" class="season-button">
                Wait for {{ nextSeason }}
            </button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Oranges from './Oranges.vue';

export default {
    name: 'OrangeTree',
    data() {
        return {
            seasons: ['spring', 'summer', 'autumn', 'winter'],
            currentSeasonIndex: 0,
            oranges: [],
            orangesOnGround: [],
            bucket: [],
            draggedOrange: null,
        };
    },
    components: {
        Oranges
    },
    computed: {
        currentSeason() { return this.seasons[this.currentSeasonIndex] },
        nextSeason() {
            const nextIndex = (this.currentSeasonIndex + 1) % this.seasons.length;
            return this.seasons[nextIndex].charAt(0).toUpperCase() + this.seasons[nextIndex].slice(1);
        },
    },
    methods: {
        async loadTree() {
            const [seasonResponse, treeResponse, bucketResponse] = await Promise.all([
                axios.get('/api/seasons'),
                axios.get('/api/orange-tree'),
                axios.get('/api/oranges')
            ]).catch(error => console.error('Error loading tree tree:', error));
            this.oranges = [];
            this.orangesOnGround = [];

            const season = seasonResponse.data;
            const tree = treeResponse.data;
            this.bucket = bucketResponse.data;
            this.currentSeasonIndex = season.current;
            const self = this;
            tree.oranges.forEach(orange => {
                if (orange.on_ground) self.orangesOnGround.push(orange);
                else self.oranges.push(orange);
            });
        },
        startDragging(orange) {
            this.draggedOrange = orange;
        },
        dropInBucket() {
            if (!this.draggedOrange) return;
            const self = this;
            axios.put(`/api/oranges/${this.draggedOrange.id}`)
                .then(() => {
                    self.draggedOrange.falling = false;
                    self.pickUpOrange(this.draggedOrange);
                    self.bucket.push(this.draggedOrange);
                    self.draggedOrange = null;
                }).catch(error => 
                    console.error('Error dropping orange in bucket:', error)
                );
        },
        eatOrange() {
            if (!this.bucket.length) return;
            const orangeToEat = this.bucket.pop();
            axios.delete(`/api/oranges/${orangeToEat.id}`)
                .catch(error => console.error('Error eating orange:', error))
        },
        advanceSeason() {
            const self = this;
            axios.put('/api/seasons')
                .then(() => self.loadTree())
                .catch(error => console.error('Error advancing season:', error))
        },
        pickUpOrange(orange) {
            const treeIndex = this.oranges.indexOf(orange);
            if (treeIndex !== -1) {
                this.oranges.splice(treeIndex, 1);
            }
            const groundIndex = this.orangesOnGround.indexOf(orange);
            if (groundIndex !== -1) {
                this.orangesOnGround.splice(groundIndex, 1);
            }
        },
    },
    async mounted() { await this.loadTree() }
};
</script>