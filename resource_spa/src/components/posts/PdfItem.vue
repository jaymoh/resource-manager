<template>
  <q-card flat elevated class="q-pa-sm q-ma-sm">
    <q-item>
      <q-item-section>
        <q-item-label class="q-mb-xs">{{ post.title}}</q-item-label>

        <q-item-label>
          <p>
            <q-btn
              label="Download"
              color="primary"
              outline
              @click="btnDownloadPdf"
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
    <q-separator spaced inset />
  </q-card>
</template>

<script lang="ts">

import { defineComponent, PropType } from 'vue';
import { PostInterface } from 'src/interfaces/post.interface';
import { baseUrl } from 'boot/axios';

export default defineComponent({
  name: 'PdfItem',
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

    const btnDownloadPdf = () => {
      const link = document.createElement('a')
      link.href = `${baseUrl}downloadPdf/${props.post.id}`
      link.click()
    }

    return {
      emitDelete,
      emitEdit,
      btnDownloadPdf,
    }
  }
})
</script>

<style scoped>

</style>
