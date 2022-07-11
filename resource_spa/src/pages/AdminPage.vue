<template>
  <q-page>
    <q-card elevated class="q-pa-md q-ma-md">
      <q-toolbar class="text-center">
        <q-toolbar-title class="text-h4">Manage Resources</q-toolbar-title>
      </q-toolbar>
      <post-list :admin-page="true" @emitShowEditDialog="showEditPostDialog"/>

      <q-page-sticky position="bottom-right" :offset="[18, 18]">
        <q-btn fab icon="add" color="primary" @click="btnShowAddPostDialog">
          <q-tooltip anchor="top middle" self="bottom middle" :offset="[10, 10]">
            <strong>Add a Post</strong>
          </q-tooltip>
        </q-btn>
      </q-page-sticky>
    </q-card>

    <!-- dialogs -->
    <q-dialog v-model="addPostDialog">
      <add-post-dialog @success="closeDialog" :is-edit="isEdit" :post="selectedPost"/>
    </q-dialog>
  </q-page>
</template>

<script lang="ts">
import { defineAsyncComponent, defineComponent } from 'vue';
import PostList from 'src/components/posts/PostList.vue';
import postsMethods from 'src/components/posts/postsMethods';

export default defineComponent({
  name: 'AdminPage',
  components: {
    PostList,
    addPostDialog: defineAsyncComponent(() => import('src/components/posts/AddPostDialog.vue')),
  },

  setup (props, context) {
    // IMPORTS
    const {
      addPostDialog,
      closeDialog,
      showEditPostDialog,
      btnShowAddPostDialog,
      isEdit,
      selectedPost,
    } = postsMethods(props, context);

    return {
      isEdit,
      addPostDialog,
      closeDialog,
      showEditPostDialog,
      btnShowAddPostDialog,
      selectedPost,
    };
  }
})
</script>

<style scoped>

</style>
