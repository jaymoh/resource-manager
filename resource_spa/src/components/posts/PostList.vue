<template>
  <div class="col">
    <div class="fit">
      <div class="flex justify-end q-gutter-lg">
        <q-btn
          class="q-ml-md q-px-sm text-capitalize"
          :icon-right="iconDir"
          @click="handleSortByDate"
        >
          Sort By Date
        </q-btn>
        <q-input
          style="width: 320px"
          debounce="1000"
          v-model="postPagination.searchQuery"
          placeholder="Search..."
          clearable
        >
          <template v-slot:prepend>
            <q-icon name="search"/>
          </template>
        </q-input>
      </div>

      <q-linear-progress class="q-mt-lg" v-if="loadingPosts" indeterminate/>
      <!-- list of posts -->
      <template v-if="postsData.data?.length">

        <q-list>
          <component
            v-for="post in postsData.data"
            :key="post.id"
            :is="componentToShow(post.post_type)"
            :post="post"
            :is-admin-page="adminPage"
            @itemSelected="itemSelected"
            @emitShowEditDialog="emitShowEditDialog"
            @showDeleteDialog="btnDeletePost"
          />
        </q-list>

        <!-- pagination -->
        <div class="row">
          <div class="col-md-4 q-pa-md float-left">
            <span class="q-pt-xl-lg">
              Showing
              <strong>{{ postPagination.from || 0 }}</strong>
              to
              <strong>{{ postPagination.to || 0 }} </strong>
              of
              <strong>{{ postPagination.total }}</strong>
              resources
            </span>
          </div>

          <div class="col-md-4 q-pa-md text-center">
            <div class="q-gutter-md row items-start">
              <q-select
                dense
                item-aligned
                input-debounce="1000"
                v-model="postPagination.perPage"
                :options="perPageOptions"
                class="q-pa-none q-ma-none"
              />
              <div class="q-ma-md">Per Page</div>
            </div>
          </div>

          <div class="col-md-4 q-pa-md float-right">
            <q-pagination
              class="float-right"
              @update:model-value="onPageChange"
              v-model="postPagination.page"
              :max="postPagination.lastPage"
              direction-links
              boundary-links
              :max-pages="5"
              :ellipses="true"
            >
            </q-pagination>
          </div>
        </div>
      </template>
      <div class="flex justify-center text-subtitle1" v-else>No Resources to Show</div>

    </div>
  </div>
</template>

<script lang="ts">
import { defineAsyncComponent, defineComponent, onMounted, watch } from 'vue';
import postsMethods from 'src/components/posts/postsMethods';
import { PostInterface } from 'src/interfaces/post.interface';

export default defineComponent({
  name: 'PostList',
  components: {
    htmlItem: defineAsyncComponent(() => import('src/components/posts/HtmlItem.vue')),
    linkItem: defineAsyncComponent(() => import('src/components/posts/LinkItem.vue')),
    pdfItem: defineAsyncComponent(() => import('src/components/posts/PdfItem.vue')),
  },
  props: {
    adminPage: {
      required: false,
      type: Boolean,
      default: false,
    },
  },
  setup(props, context) {
    // IMPORTS
    const {
      iconDir,
      fetchPosts,
      postPagination,
      postsData,
      loadingPosts,
      componentToShow,
      perPageOptions,
      onPageChange,
      handleSortByDate,
      itemSelected,
      btnDeletePost,
    } = postsMethods(props, context);

    // METHODS
    const emitShowEditDialog = (post: PostInterface) => {
      context.emit('emitShowEditDialog', post);
    }

    // HOOKS
    onMounted(() => {
      fetchPosts();
    });

    watch(
      () => postPagination.searchQuery,
      () => {
        fetchPosts()
      }
    )

    watch(
      () => postPagination.perPage,
      () => {
        fetchPosts()
      }
    )

    watch(
      postsData,
      () => {
        if (Object.prototype.hasOwnProperty.call(postsData.value, 'meta')) {
          postPagination.from = postsData.value.meta?.from || 0;
          postPagination.to = postsData.value.meta?.to || 0;
          postPagination.total = postsData.value.meta?.total || 0;
          postPagination.lastPage = postsData.value.meta?.last_page || 1;
        }
      },
      { deep: true }
    )

    return {
      iconDir,
      postPagination,
      postsData,
      loadingPosts,
      componentToShow,
      perPageOptions,
      onPageChange,
      handleSortByDate,
      itemSelected,
      emitShowEditDialog,
      btnDeletePost,
    };
  }
})
</script>

<style scoped>

</style>
