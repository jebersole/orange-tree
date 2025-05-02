import { createRouter, createWebHistory } from 'vue-router';
import BucketIndex from './components/BucketIndex.vue'; // Replace with your component
import BucketShow from './components/BucketShow.vue'; // Replace with your component
import OrangeIndex from './components/OrangeIndex.vue'; // Replace with your component
import OrangeShow from './components/OrangeShow.vue'; // Replace with your component
import OrangeCreate from './components/OrangeCreate.vue'; // Replace with your component
import OrangeTreeIndex from './components/OrangeTreeIndex.vue'; // Replace with your component
import OrangeTreeShow from './components/OrangeTreeShow.vue'; // Replace with your component
import OrangeTreeCreate from './components/OrangeTreeCreate.vue'; // Replace with your component
import SeasonIndex from './components/SeasonIndex.vue'; // Replace with your component
import SeasonShow from './components/SeasonShow.vue'; // Replace with your component
import GroundCreate from './components/GroundCreate.vue'; // Replace with your component
import GroundShow from './components/GroundShow.vue'; // Replace with your component

const routes = [
  {
    path: '/buckets',
    name: 'buckets.index',
    component: BucketIndex,
    method: 'GET', // Indicate the HTTP method
  },
  {
    path: '/buckets/:id',
    name: 'buckets.show',
    component: BucketShow,
    method: 'GET',
  },
    {
    path: '/buckets',
    name: 'buckets.create',
    component: BucketShow, // Assuming a form for creating buckets
    method: 'POST',
  },
  {
    path: '/oranges',
    name: 'oranges.index',
    component: OrangeIndex,
    method: 'GET',
  },
  {
    path: '/oranges/:id',
    name: 'oranges.show',
    component: OrangeShow,
    method: 'GET',
  },
  {
      path: '/oranges',
      name: 'oranges.create',
      component: OrangeCreate,
      method: 'POST',
  },
  {
    path: '/orange-trees',
    name: 'orangeTrees.index',
    component: OrangeTreeIndex,
    method: 'GET',
  },
    {
    path: '/orange-trees/:id',
    name: 'orangeTrees.show',
    component: OrangeTreeShow,
    method: 'GET',
  },
  {
    path: '/orange-trees',
    name: 'orangeTrees.create',
    component: OrangeTreeCreate,
    method: 'POST',
  },
  {
    path: '/seasons',
    name: 'seasons.index',
    component: SeasonIndex,
    method: 'GET',
  },
    {
    path: '/seasons/:id',
    name: 'seasons.show',
    component: SeasonShow,
    method: 'GET',
  },
  {
      path: '/seasons',
      name: 'seasons.create',
      component: SeasonShow, // Assuming a form for creating/managing seasons.
      method: 'POST',
  },
    {
    path: '/ground',
    name: 'ground.create',
    component: GroundCreate,
    method: 'POST',
  },
    {
    path: '/ground/:id',
    name: 'ground.show',
    component: GroundShow,
    method: 'GET',
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
