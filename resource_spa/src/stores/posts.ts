import { defineStore } from 'pinia';
import { api } from 'boot/axios';
import { PostInterface, PostsResultInterface } from 'src/interfaces/post.interface';
import { PaginationInterface } from 'src/interfaces/pagination.interface';
import { appendEditForm, appendForm } from 'src/utils/formHelper';

export const usePostsStore = defineStore('posts', {
  state: () => ({
    posts: {} as PostsResultInterface,
    singlePost: {} as PostInterface,
    loading: false,
  }),

  getters: {
    getPosts: (state) => state.posts,
    getLoading: (state) => state.loading,
    getSinglePost: (state) => state.singlePost,
  },

  actions: {
    fetchPosts(payload: Partial<PaginationInterface>): Promise<PostsResultInterface> {
      this.loading = true;
      return new Promise((resolve, reject) => {
        api
          .get('posts', { params: payload })
          .then((response) => {

            this.posts = response.data
            this.loading = false;

            resolve(response)
          })
          .catch((error) => {
            this.loading = false;

            reject(error)
          })
      })
    },

    fetchSinglePost(id: number): Promise<PostInterface>|Promise<any> {
      this.loading = true;
      return new Promise((resolve, reject) => {
        api
          .get(`posts/${id}`)
          .then((response) => {

            this.singlePost = response.data
            this.loading = false;

            resolve(response)
          })
          .catch((error) => {
            this.loading = false;

            reject(error)
          })
      })
    },

    storePost(form: any, endpoint: string): Promise<any> {
      return new Promise((resolve, reject) => {
        api
          .post(endpoint, appendForm(form))
          .then((response) => resolve(response))
          .catch((error) => reject(error))
      })
    },

    updatePost(form: any, endpoint: string): Promise<any> {
      return new Promise((resolve, reject) => {
        api
          .post(`${endpoint}/${form.id}`, appendEditForm(form))
          .then((response) => resolve(response))
          .catch((error) => reject(error))
      })
    },


    deletePost(id: number): Promise<any> {
      return new Promise((resolve, reject) => {
        api
          .delete(`posts/${id}`)
          .then((response) => resolve(response))
          .catch((error) => reject(error))
      })
    },
  }
})
