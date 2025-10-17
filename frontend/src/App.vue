<template>
  <div class="wrap">
    <h1>House Search</h1>
    <SearchForm :loading="loading" @search="onFilters" />
    <ResultsTable
        :rows="rows"
        :loading="loading"
        :total="total"
        :page="page"
        :pageSize="pageSize"
        :sort="sort"
        :dir="dir"
        @update:page="v => { page = v; fetch() }"
        @update:pageSize="v => { pageSize = v; page = 1; fetch() }"
        @sort-change="onSort"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { ElMessage } from 'element-plus'
import { searchHouses } from './api/houses'
import SearchForm from './components/SearchForm.vue'
import ResultsTable from './components/ResultsTable.vue'

const rows = ref([])
const total = ref(0)
const loading = ref(false)
let filters = {}
let page = ref(1)
let pageSize = ref(10)
let sort = ref('name')
let dir = ref('asc')

async function fetch() {
  loading.value = true
  try {
    const data = await searchHouses({ ...filters, page: page.value, per_page: pageSize.value, sort: sort.value, dir: dir.value })
    rows.value = data.data || []
    total.value = data.meta?.total || rows.value.length
  } catch (e) {
    rows.value = []
    total.value = 0
    ElMessage.error('Request failed')
  } finally {
    loading.value = false
  }
}

function onFilters(p) {
  filters = p || {}
  page.value = 1
  fetch()
}

function onSort({ sort: s, dir: d }) {
  sort.value = s || 'name'
  dir.value = d || 'asc'
  page.value = 1
  fetch()
}

fetch()
</script>

<style>
body { margin: 0; font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji"; }
.wrap { max-width: 1100px; margin: 24px auto; padding: 0 16px; }
</style>
