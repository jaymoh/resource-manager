<template>
  <q-layout view="hhh lpR fFf">
    <q-header elevated>
      <q-toolbar>

        <q-toolbar-title>
          <span @click="$router.push({ name: 'HomeRoute' })" style="cursor: pointer">
            Resource Manager App
          </span>
        </q-toolbar-title>
        <q-space/>
        <span @click="$router.push({ name: 'AdminPageRoute' })" style="cursor: pointer">
            Admin
          </span>
      </q-toolbar>
    </q-header>

    <q-page-container>
      <router-view/>
    </q-page-container>
  </q-layout>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'MainLayout',

  components: {},

  setup() {

    return {}
  }
});
</script>
