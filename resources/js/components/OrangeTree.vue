// This component represents the main Orange Tree interface. It manages the state of the tree,
// including oranges on the tree, oranges on the ground, and oranges in the bucket.
// It interacts with the backend API to load or create the tree, move oranges to the bucket,
// eat oranges, and create or advance the season. The component also uses the Oranges child component
// to render individual oranges on the tree and ground.

<template>
    <div class="orange-tree-container" :class="currentSeason">
        <div class="tree-container">
            <div class="tree">
                <img src="../../images/orange_tree.png" alt="Orange Tree" class="tree-image" />
                <div class="foliage">
                    <Oranges :oranges="oranges" :onGround="false" :startDragging="startDragging" />
                </div>
            </div>

            <div class="ground">
                <div class="grass"></div>
                <Oranges :oranges="orangesOnGround" :onGround="true" :startDragging="startDragging" />
            </div>
        </div>
    </div>
    <div class="bucket-container" @dragover.prevent @drop="dropInBucket">
        <div class="bucket"></div>
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
        currentSeason() {
            if (!this.seasons.length || !this.currentSeasonIndex) return 'Summer';
            return this.seasons[this.currentSeasonIndex]
        },
        nextSeason() {
            if (!this.seasons.length || !this.currentSeasonIndex) return 'Summer';
            const nextIndex = (this.currentSeasonIndex + 1) % this.seasons.length;
            console.log('nextindex:', nextIndex, this.seasons, this.seasons[nextIndex].charAt(0).toUpperCase() + this.seasons[nextIndex].slice(1));
            return this.seasons[nextIndex].charAt(0).toUpperCase() + this.seasons[nextIndex].slice(1);
        },
    },
    methods: {
        async loadTree() {
            const requests = [
                { url: '/api/seasons', createUrl: '/api/seasons', createPayload: { id: 42, current: 0 } }, // Default to Spring
                { url: '/api/orange-tree', createUrl: '/api/orange-tree', createPayload: { id: 42 } },
                { url: '/api/oranges' } // No create logic for bucket
            ];

            const self = this;
            const results = await Promise.all(
                requests.map(({ url, createUrl, createPayload }) =>
                    axios.get(url)
                        .then(response => response.data)
                        .catch(error => {
                            if (error.response && error.response.status === 400 && createUrl) {
                                return axios.put(createUrl, createPayload).then(response => response.data);
                            }
                            console.error(`Error fetching resource at ${url}:`, error);
                            throw error;
                        })
                )
            ).then(results => {
                const [season, tree, bucket] = results;

                // Update state
                self.bucket = bucket;
                self.currentSeasonIndex = season.current;
                self.oranges = [];
                self.orangesOnGround = [];
                if (tree.oranges) tree.oranges.forEach(orange => {
                    if (orange.on_ground) self.orangesOnGround.push(orange);
                    else self.oranges.push(orange);
                });
            }).catch(error => {
                console.error('Error loading tree:', error);
                throw error;
            });
        },
        startDragging(orange) {
            this.draggedOrange = orange;
        },
        dropInBucket() {
            if (!this.draggedOrange) return;
            const self = this;
            Promise.resolve()
                .then(() => self.pickUpOrange(self.draggedOrange))
                .then(() => axios.post('/api/oranges', { id: self.draggedOrange.id }))
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
            axios.post('/api/seasons')
                .then(() => self.loadTree())
                .catch(error => console.error('Error advancing season:', error))
        },
        pickUpOrange(orange) {
            const treeIndex = this.oranges.indexOf(orange);
            if (treeIndex !== -1) {
                this.oranges.splice(treeIndex, 1);
                axios.delete(`api/orange-tree/${orange.id}`)
                    .catch(error => console.error('Error picking orange off tree:', error));
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