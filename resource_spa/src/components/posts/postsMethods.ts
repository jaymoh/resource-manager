import { useQuasar } from 'quasar';
import { usePostsStore } from 'stores/posts';
import { computed, reactive, ref, SetupContext } from 'vue';
import { PostInterface } from 'src/interfaces/post.interface';
import {
  createHeaderObjectArray,
  showErrorNotification,
  showMissingFieldsErrors,
  showNotification
} from 'src/utils/utils';
import { sortDirTypes } from 'src/interfaces/pagination.interface';

export default function postsMethods(props: any, context: SetupContext) {
  // IMPORTS
  const $q = useQuasar()
  const postsStore = usePostsStore()

  // DATA
  const loading = ref(false)
  const addPostDialog = ref(false)
  const isEdit = ref(false)
  const selectedPost = ref<PostInterface>()
  const postPagination = reactive({
    page: 1,
    perPage: 10,
    searchQuery: '',
    total: 0,
    lastPage: 1,
    from: 0,
    to: 0,
    sortBy: '',
    sortDir: <sortDirTypes>'ASC',
    includeRelations: ['pdfPost', 'linkPost', 'htmlPost',],
  })
  const postFormRef = ref<HTMLFormElement>()
  const postForm = ref({
    id: 0,
    title: '',
    post_type: '',
    pdf_file: '',
    description: '',
    html_snippet: '',
    url: '',
    open_in_new_tab: false
  })
  const perPageOptions = [10, 20, 50]
  const postTypeOptions = [
    { label: 'Html Snippet', value: 'storeHtml' },
    { label: 'Link', value: 'storeLink' },
    { label: 'Pdf File', value: 'storePdf' },
  ]
  const iconDir = ref('keyboard_arrow_up')

  // COMPUTED
  const postsData = computed(() => postsStore.getPosts)
  const loadingPosts = computed(() => postsStore.getLoading)
  const singlePost = computed(() => postsStore.getSinglePost)
  const tableHeaders = computed((addActionColum = false) => {
    if (postsData.value.data && postsData.value.data.length) {
      const firstKeys = Object.keys(postsData.value.data[0])

      if (addActionColum) {
        firstKeys.push('actions')
      }

      return createHeaderObjectArray(firstKeys)
    }
    return []
  })

  // METHODS
  const fetchPosts = () => postsStore.fetchPosts(postPagination).then(() => {/** */
  }).catch((err) => showErrorNotification($q, err, 'Error fetching posts'))
  const fetchSinglePost = (id: number) => postsStore.fetchSinglePost(id)
  const onPageChange = (page: number) => {
    postPagination.page = page
    fetchPosts()
  }
  const checkPdfFile = () => {
    return !!postForm.value?.pdf_file || 'Pdf File Required'
  }

  const btnSavePost = (actionEdit = false) => {
    postFormRef.value?.validate().then((success: boolean) => {
      if (success) {
        loading.value = true

        if (actionEdit) {
          updatePostAction()
        } else {
          addPostAction()
        }
      } else {
        showNotification($q, 'negative', 'Please fill the form')
      }
    })
  }

  const addPostAction = () => {
    const endpoint = postForm.value.post_type
    postsStore.storePost(postForm.value, endpoint)
      .then(() => {
        loading.value = false
        showNotification($q, 'positive', 'Post added successfully')
        context.emit('success')
      })
      .catch((err) => {
        loading.value = false
        showMissingFieldsErrors($q, err)
      })
  }

  const updatePostAction = () => {
    let endpoint = postForm.value.post_type
    endpoint = `update${endpoint.substring(5)}`

    postsStore.updatePost(postForm.value, endpoint)
      .then(() => {
        loading.value = false
        showNotification($q, 'positive', 'Post updated successfully')
        context.emit('success')
      })
      .catch((err) => {
        loading.value = false
        showMissingFieldsErrors($q, err)
      })
  }

  const btnDeletePost = (post: PostInterface) => {
    $q.dialog({
      title: 'Confirm',
      message: `Are you sure you want to delete ${post.title}?`,
      cancel: true,
      persistent: false,
    })
      .onOk(() => {
        postsStore
          .deletePost(post.id)
          .then(() => {
            showNotification($q, 'positive', `${post.title} has been deleted`)
            fetchPosts()
          })
          .catch((err) => {
            showMissingFieldsErrors($q, err)
          })
      })
      .onCancel(() => {
        showNotification($q, 'info', 'Deletion cancelled')
      })

  }

  const closeDialog = () => {
    addPostDialog.value = false
    fetchPosts()
  }
  const btnShowAddPostDialog = () => {
    selectedPost.value = undefined
    isEdit.value = false
    postForm.value = {
      id: 0,
      title: '',
      post_type: '',
      pdf_file: '',
      description: '',
      html_snippet: '',
      url: '',
      open_in_new_tab: false
    }
    addPostDialog.value = true
  }
  const showEditPostDialog = (post: PostInterface) => {
    isEdit.value = true
    selectedPost.value = post
    addPostDialog.value = true
  }
  const populateForm = (post: PostInterface) => {
    postForm.value.id = post.id
    postForm.value.title = post.title
    postForm.value.post_type = post.post_type
    postForm.value.description = post.relationships.html?.description || ''
    postForm.value.html_snippet = post.relationships.html?.html_snippet || ''
    postForm.value.url = post.relationships.link?.url || ''
    postForm.value.open_in_new_tab = post.relationships.link?.open_in_new_tab || false
  }

  const handleSortByDate = () => {
    iconDir.value = iconDir.value === 'keyboard_arrow_up' ? 'keyboard_arrow_down' : 'keyboard_arrow_up'
    postPagination.sortBy = 'created_at'
    postPagination.sortDir = iconDir.value === 'keyboard_arrow_up' ? 'ASC' : 'DESC'

    fetchPosts()
  }

  const componentToShow = (postType: string) => {
    const component = postType.substring(5)

    return `${component.toLowerCase()}-item`
  }

  const itemSelected = (post: PostInterface) => {
    selectedPost.value = post
  }

  const copySnippetToClipboard = (snippet: string) => {
    navigator.clipboard.writeText(snippet)
      .then(() => {
        showNotification($q, 'positive', 'Snippet copied to clipboard')
      })
      .catch((err) => {
        showErrorNotification($q, err, 'Error copying snippet to clipboard')
      })
  }

  return {
    loading,
    addPostDialog,
    isEdit,
    selectedPost,
    postPagination,
    postFormRef,
    postForm,
    perPageOptions,
    postTypeOptions,
    iconDir,
    postsData,
    loadingPosts,
    singlePost,
    tableHeaders,
    fetchPosts,
    fetchSinglePost,
    onPageChange,
    btnSavePost,
    btnDeletePost,
    closeDialog,
    btnShowAddPostDialog,
    showEditPostDialog,
    populateForm,
    handleSortByDate,
    componentToShow,
    itemSelected,
    checkPdfFile,
    copySnippetToClipboard,
  }
}
