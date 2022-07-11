<template>
  <q-card style="width: 550px; max-width: 85vw; margin: 13px">
    <q-toolbar>
      <q-toolbar-title>{{ isEdit ? 'Update Post' : 'Create Post' }}</q-toolbar-title>
    </q-toolbar>

    <q-card-section>
      <q-form ref="postFormRef">
        <q-select
          :disable="isEdit"
          :label="isEdit? 'Resource Type': 'Select Resource Type'"
          clearable
          v-model="postForm.post_type"
          :options="postTypeOptions"
          class="q-ma-lg"
          option-label="label"
          option-value="value"
          map-options
          emit-value
        />

        <template v-if="postForm.post_type">
          <q-input
            label="Title"
            type="text"
            class="q-ma-lg"
            clearable
            v-model="postForm.title"
            trim
            lazy-rules
            :rules="[
                  (val) => (val && val.length > 0) || 'Title Required',
                ]"
          />
        </template>

        <template v-if="postForm.post_type === 'storeHtml'">
          <q-input
            label="Description"
            type="textarea"
            class="q-ma-lg"
            clearable
            v-model="postForm.description"
            trim
            lazy-rules
            :rules="[
                  (val) => (val && val.length > 0) || 'Description Required',
                ]"
          />

          <q-input
            label="HTML Snippet"
            type="textarea"
            class="q-ma-lg"
            clearable
            v-model="postForm.html_snippet"
            trim
            lazy-rules
            :rules="[
                  (val) => (val && val.length > 0) || 'Html Snippet required',
                ]"
          />

          <p class="q-mt-lg q-ml-lg q-mb-none text-subtitle1" v-if="postForm.html_snippet">Preview Below</p>
          <hr />
          <span v-html="postForm.html_snippet" v-if="postForm.html_snippet"></span>
        </template>

        <template v-if="postForm.post_type === 'storeLink'">
          <q-input
            label="Link"
            type="text"
            class="q-ma-lg"
            clearable
            v-model="postForm.url"
            trim
            lazy-rules
            :rules="[
                  (val) => (val && val.length > 0) || 'Link/URL Required',
                ]"
          />

          <q-checkbox class="q-ma-lg" left-label v-model="postForm.open_in_new_tab" label="Open In New Tab?" />
        </template>

        <template v-if="postForm.post_type === 'storePdf'">
          <q-file
            label="Choose File"
            v-model="postForm.pdf_file"
            class="q-ma-lg"
            clearable
            :accept="'.pdf'"
            lazy-rules
            :rules="[checkPdfFile]"
          >
            <template v-slot:prepend>
              <q-icon size="md" name="attach_file" class="q-ml-sm" />
            </template>
          </q-file>
        </template>
      </q-form>
    </q-card-section>

    <q-card-actions class="q-px-lg" align="right">
      <q-btn
        size="lg"
        :loading="loading"
        :disable="loading"
        class="q-pa-lg q-pl-xl q-pr-xl q-ma-lg text-black text-capitalize"
        v-close-popup
      >
        <span class="text-h5">Cancel</span>
      </q-btn>

      <q-btn
        v-if="postForm.post_type"
        size="lg"
        :loading="loading"
        :disable="loading"
        @click="btnSavePost(isEdit)"
        color="accent"
        class="q-pa-lg q-pl-xl q-pr-xl q-ma-lg text-capitalize text-white"
      >
        <span class="text-h5">{{ isEdit ? 'Update' : 'Create' }}</span>
        <template v-slot:loading>
          <q-spinner-facebook/>
        </template>
      </q-btn>
    </q-card-actions>
  </q-card>
</template>

<script lang="ts">
import { defineComponent, onMounted, PropType } from 'vue';
import postsMethods from 'src/components/posts/postsMethods';
import { PostInterface } from 'src/interfaces/post.interface';

export default defineComponent({
  name: 'AddPostDialog',
  props: {
    isEdit: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
    post: {
      type: Object as PropType<PostInterface>,
      default: () => ({})
    }
  },

  setup(props, context) {
    // IMPORTS
    const { postFormRef, checkPdfFile, postForm, populateForm, loading, btnSavePost, postTypeOptions } = postsMethods(props, context)

    // HOOKS
    onMounted(() => {
      if (props.isEdit) {
        populateForm(props.post)
      }
    })

    return {
      postForm,
      postFormRef,
      loading,
      btnSavePost,
      postTypeOptions,
      checkPdfFile,
    }
  }
})
</script>

<style scoped>

</style>
