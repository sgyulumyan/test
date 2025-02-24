<template>
    <div>
      <Head title="Contacts" />
      <h1 class="mb-8 text-3xl font-bold">Tests</h1>
      <div class="flex items-center justify-between mb-6">
        <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
          <label class="block text-gray-700">Status:</label>
          <select v-model="form.status" class="form-select mt-1 w-full">
            <option :value="null" />
            <option value="allowed">Only Allowed</option>
            <option value="prohibited">Only Prohibited</option>
          </select>
        </search-filter>
        <Link class="btn-indigo" href="/tests/create">
          <span>Create New Test</span>
        </Link>
        <Link class="btn-indigo" href="/tests/generate">
          <span>Generate 1000 Tests</span>
        </Link>
        <Link class="btn-indigo" href="/tests/clear">
          <span>Delete All Tests</span>
        </Link>
        <Link class="btn-indigo" href="/tests/google-sheet-url">
          <span>Settings</span>
        </Link>
      </div>
      <div class="bg-white rounded-md shadow overflow-x-auto">
        <table class="w-full whitespace-nowrap">
          <tbody>
              <tr class="text-left font-bold">
              <th class="pb-4 pt-6 px-6">Name</th>
              <th class="pb-4 pt-6 px-6">Email</th>
              <th class="pb-4 pt-6 px-6">Phone</th>
              <th class="pb-4 pt-6 px-6">Description</th>
              <th class="pb-4 pt-6 px-6">Comment</th>
              <th class="pb-4 pt-6 px-6">Status</th>
            </tr>
            <tr v-for="test in tests.data" :key="test.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
              <td class="border-t">
                <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/tests/${test.id}/edit`">
                  {{ test.name }}
                  <icon v-if="test.deleted_at" name="trash" class="shrink-0 ml-2 w-3 h-3 fill-gray-400" />
                </Link>
              </td>
              <td class="border-t">
                <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/tests/${test.id}/edit`">
                  {{ test.email }}
                  <icon v-if="test.deleted_at" name="trash" class="shrink-0 ml-2 w-3 h-3 fill-gray-400" />
                </Link>
              </td>
              <td class="border-t">
                <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/tests/${test.id}/edit`">
                  {{ test.phone }}
                  <icon v-if="test.deleted_at" name="trash" class="shrink-0 ml-2 w-3 h-3 fill-gray-400" />
                </Link>
              </td>
              <td class="border-t">
                <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/tests/${test.id}/edit`">
                  {{ test.description }}
                  <icon v-if="test.deleted_at" name="trash" class="shrink-0 ml-2 w-3 h-3 fill-gray-400" />
                </Link>
              </td>
              <td class="border-t">
                <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/tests/${test.id}/edit`">
                  {{ test.comment }}
                  <icon v-if="test.deleted_at" name="trash" class="shrink-0 ml-2 w-3 h-3 fill-gray-400" />
                </Link>
              </td>
              <td class="border-t">
                <Link class="flex items-center px-6 py-4" :href="`/test/${test.id}/edit`" tabindex="-1">
                  {{ test.status }}
                </Link>
              </td>
            </tr>
            <tr v-if="tests.data.length === 0">
              <td class="px-6 py-4 border-t" colspan="4">No tests found.</td>
            </tr>
          </tbody>          
        </table>
      </div>
      <pagination class="mt-6" :links="tests.links" />
    </div>
  </template>
  
  <script>
  import { Head, Link } from '@inertiajs/vue3'
  import Icon from '@/Shared/Icon.vue'
  import pickBy from 'lodash/pickBy'
  import Layout from '@/Shared/Layout.vue'
  import throttle from 'lodash/throttle'
  import mapValues from 'lodash/mapValues'
  import Pagination from '@/Shared/Pagination.vue'
  import SearchFilter from '@/Shared/SearchFilter.vue'
  
  export default {
    components: {
      Head,
      Icon,
      Link,
      Pagination,
      SearchFilter,
    },
    layout: Layout,
    props: {
      filters: Object,
      tests: Object,
    },
    data() {
      return {
        form: {
          search: this.filters?.search || '', // Default to empty string
          status: this.filters?.status || null
        },
      }
    },
    watch: {
      form: {
        deep: true,
        handler: throttle(function () {
          this.$inertia.get('/tests', pickBy(this.form), { preserveState: true })
        }, 150),
      },
    },
    methods: {
      reset() {
        this.form = mapValues(this.form, () => null)
      },
    },
  }
  </script>
  