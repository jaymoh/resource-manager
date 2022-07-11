<template>
  <q-card flat elevated class="q-pa-sm q-ma-sm">
    <q-item>
      <q-item-section>
        <q-item-label class="q-mb-xs">{{ post.title }}</q-item-label>

        <q-item-label caption class="q-mt-md">
          {{ post.relationships.html?.description }}
        </q-item-label>

        <q-item-label>
          <p class="q-mt-md">
            {{ post.relationships.html?.html_snippet }}
          </p>

          <p class="q-mt-md">
            <q-btn
              label="Copy To Clipboard"
              color="primary"
              outline
              @click="copySnippetToClipboard(post.relationships.html?.html_snippet || '')"
              class="text-capitalize"
            />
          </p>
        </q-item-label>

        <small class="q-mt-md">{{ post.created_at }}</small>
      </q-item-section>

      <q-item-section side top v-if="isAdminPage">
        <q-btn
          class="q-ma-xs"
          outline
          color="positive"
          icon="edit"
          @click="emitEdit"
        />
        <q-btn
          class="q-ma-xs"
          outline
          color="negative"
          icon="delete"
          @click="emitDelete"
        />
      </q-item-section>
    </q-item>
    <q-separator spaced inset/>
  </q-card>
</template>

<script lang="ts">

import { defineComponent, PropType } from 'vue';
import { PostInterface } from 'src/interfaces/post.interface';
import postsMethods from 'src/components/posts/postsMethods';

export default defineComponent({
  name: 'HtmlItem',
  props: {
    post: {
      required: true,
      type: Object as PropType<PostInterface>,
    },
    isAdminPage: {
      type: Boolean,
      default: false,
    },
  },
  setup(props, context) {
    // IMPORTS
    const { copySnippetToClipboard } = postsMethods(props, context);

    // METHODS
    const emitDelete = () => {
      context.emit('showDeleteDialog', props.post);
    }

    const emitEdit = () => {
      context.emit('emitShowEditDialog', props.post);
    }


    return {
      emitDelete,
      emitEdit,
      copySnippetToClipboard,
    }
  }
})
</script>

<style scoped>

</style>
