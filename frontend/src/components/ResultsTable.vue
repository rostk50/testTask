<template>
  <div>
    <div class="toolbar">
      <div class="left"><strong>{{ total }}</strong> results</div>
      <div class="right"></div>
    </div>
    <el-table
        :data="rows"
        v-loading="loading"
        element-loading-text="Loading"
        @sort-change="emitSort"
        :default-sort="defaultSort"
        style="width: 100%">
      <el-table-column prop="name" label="Name" sortable="custom" />
      <el-table-column prop="bedrooms" label="Bedrooms" sortable="custom" width="120" />
      <el-table-column prop="bathrooms" label="Bathrooms" sortable="custom" width="120" />
      <el-table-column prop="storeys" label="Storeys" sortable="custom" width="120" />
      <el-table-column prop="garages" label="Garages" sortable="custom" width="120" />
      <el-table-column prop="price" label="Price" sortable="custom" width="160" />
      <template #empty>
        <el-empty description="No results" />
      </template>
    </el-table>
    <div class="pager" v-if="total > pageSize">
      <el-pagination
          layout="prev, pager, next, sizes, total"
          :total="total"
          :page-sizes="[5,10,20,50,100]"
          :page-size="pageSize"
          :current-page="page"
          @update:page-size="v => $emit('update:pageSize', v)"
          @update:current-page="v => $emit('update:page', v)"
      />
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  rows: { type: Array, default: () => [] },
  loading: { type: Boolean, default: false },
  total: { type: Number, default: 0 },
  page: { type: Number, default: 1 },
  pageSize: { type: Number, default: 10 },
  sort: { type: String, default: 'name' },
  dir: { type: String, default: 'asc' },
})
const defaultSort = { prop: props.sort, order: props.dir === 'desc' ? 'descending' : 'ascending' }
function emitSort({ prop, order }) {
  const dir = order === 'descending' ? 'desc' : 'asc'
  if (!prop) return
  emit('sort-change', { sort: prop, dir })
}
const emit = defineEmits(['update:page','update:pageSize','sort-change'])
</script>

<style scoped>
.toolbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px }
.pager { margin-top: 12px; display: flex; justify-content: flex-end }
</style>
