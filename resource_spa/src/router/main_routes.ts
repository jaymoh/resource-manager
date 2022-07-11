export default [
  {
    path: '',
    meta: { requiresAuth: false, title: 'Remote | Home' },
    name: 'HomeRoute',
    component: () => import('pages/IndexPage.vue'),
  },
  {
    path: '/admin',
    meta: { requiresAuth: true, title: 'Remote | Admin' },
    name: 'AdminPageRoute',
    component: () => import('pages/AdminPage.vue'),
  },
]
