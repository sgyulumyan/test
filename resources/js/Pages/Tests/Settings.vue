<template>
<div>
    <!-- <h2>Настройки Google Sheets</h2> -->
    <Head title="Настройки Google Sheets" />
    <h1 class="mb-8 text-3xl font-bold">Настройки Google Sheets</h1>
    <form @submit.prevent="saveUrl">
    <input class="pb-6 pr-12 w-full lg:w-1/2" type="url" v-model="googleSheetUrl" placeholder="Введите Google Sheet URL" required /><br>
    <button class="btn-indigo" type="submit">Сохранить</button>
    </form>
    <p v-if="url">Текущий URL: <a :href="googleSheetUrl" target="_blank">{{ url }}</a></p>
</div>
</template>
  
<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import Layout from '@/Shared/Layout.vue'
import Pagination from '@/Shared/Pagination.vue'
import { ref, onMounted } from "vue";
import axios from 'axios';

export default {
components: {
    Head,
    Icon,
    Link,
    Pagination,
},
layout: Layout,
props: {
    url: [],
},
setup() {
    const googleSheetUrl = ref("");

    onMounted(() => {
    axios.get("/api/settings/google_sheet_url").then((response) => {
        googleSheetUrl.value = response.data.value || "";
    });
    });

    const saveUrl = () => {
        axios.post("/tests/google-sheet-url", { url: googleSheetUrl.value }).then(() => {
            alert("URL сохранён!");
            // После сохранения заново запрашиваем актуальный URL
            axios.get("/api/settings/google_sheet_url").then((response) => {
                googleSheetUrl.value = response.data.value || "";
            });
        });
};

    return { googleSheetUrl, saveUrl };
},
}
</script>
  
  
  