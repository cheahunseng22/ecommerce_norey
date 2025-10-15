<template>
  <RouterView />
</template>

<script>

import { RouterLink, RouterView } from 'vue-router';
import Login from './components/Login.vue';
import Dashboard from './components/dashboard/dashboard.vue';



export default {
  components: {
    Login,
    Dashboard,
  
  
  },
}

</script>
<style>
@import 'font-awesome/css/font-awesome.min.css';
</style>