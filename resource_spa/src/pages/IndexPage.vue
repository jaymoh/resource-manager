<template>
  <q-page>
    <q-card elevated class="q-pa-md q-ma-md">
      <q-toolbar class="text-center">
        <q-toolbar-title class="text-h4">Available Resources</q-toolbar-title>
      </q-toolbar>
      <post-list />

    </q-card>
  </q-page>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import PostList from 'src/components/posts/PostList.vue';

export default defineComponent({
  name: 'IndexPage',
  components: {
    PostList,
  },
  setup () {

    return {  };
  }
});
</script>
