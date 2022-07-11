<template>
  <q-card flat elevated class="q-pa-sm q-ma-sm">
    <q-item>
      <q-item-section>
        <q-item-label class="q-mb-sm">{{ post.title }}</q-item-label>

        <q-item-label v-if="post.relationships.link?.open_in_new_tab" caption>
          <a :href="post.relationships.link?.url" target="_blank">View</a>
        </q-item-label>

        <q-item-label v-else caption>
          <a :href="post.relationships.link?.url">View</a>
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
import { PostInterface } from 'src/interfaces/post.interface';
import { defineComponent, PropType } from 'vue';

export default defineComponent({
  name: 'LinkItem',
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
    const emitDelete = () => {
      context.emit('showDeleteDialog', props.post);
    }

    const emitEdit = () => {
      context.emit('emitShowEditDialog', props.post);
    }

    return {
      emitDelete,
      emitEdit,
    }
  }
})
</script>

<style scoped>

</style>
