<template>
    <div>
      <Head :title="form.name" />
      <h1 class="mb-8 text-3xl font-bold">
        <Link class="text-indigo-400 hover:text-indigo-600" href="/tests">Tests</Link>
        <span class="text-indigo-400 font-medium">/</span>
        {{ form.name }}
      </h1>
      <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
        <form @submit.prevent="update">
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full lg:w-1/2" label="Name" />
            <text-input v-model="form.email" :error="form.errors.email" class="pb-8 pr-6 w-full lg:w-1/2" label="Email" />
            <text-input v-model="form.phone" :error="form.errors.phone" class="pb-8 pr-6 w-full lg:w-1/2" label="Phone" />
            <text-input v-model="form.description" :error="form.errors.description" class="pb-8 pr-6 w-full lg:w-1/2" label="Description" />
            <text-input v-model="form.comment" :error="form.errors.comment" class="pb-8 pr-6 w-full lg:w-1/2" label="Comment" />
            <select-input v-model="form.status" :error="form.errors.status" class="pb-8 pr-6 w-full lg:w-1/2" label="Status">
              <option v-for="status in statuses" :key="status.value" :value="status.value">
                {{ status.label }}
              </option>
            </select-input> 
          </div>
          <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
            <button class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete Test</button>
            <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update Test</loading-button>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  import { Head, Link } from '@inertiajs/vue3'
  import Icon from '@/Shared/Icon.vue'
  import Layout from '@/Shared/Layout.vue'
  import TextInput from '@/Shared/TextInput.vue'
  import SelectInput from '@/Shared/SelectInput.vue'
  import LoadingButton from '@/Shared/LoadingButton.vue'
//   import TrashedMessage from '@/Shared/TrashedMessage.vue'
  
  export default {
    components: {
      Head,
      Icon,
      Link,
      LoadingButton,
      SelectInput,
      TextInput,
    //   TrashedMessage,
    },
    layout: Layout,
    props: {
       test: Object,
       statuses: Object,
    },
    mounted() {
        console.log("Received statuses:", this.test);
    },
    remember: 'form',
    data() {
      return {
        form: this.$inertia.form({
          name: this.test.name,
          email: this.test.email,
          phone: this.test.phone,
          description: this.test.description,
          comment: this.test.comment,
          status: this.test.status,
        }),
      }
    },
    methods: {
      update() {
        this.form.put(`/tests/${this.test.id}`)
      },
      destroy() {
        if (confirm('Are you sure you want to delete this test?')) {
          this.$inertia.delete(`/tests/${this.test.id}`)
        }
      },
    },
  }
  </script>
  