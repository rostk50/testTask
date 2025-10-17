<template>
  <el-form ref="formRef" :model="q" :rules="rules" label-position="top" class="form">
    <div class="grid">
      <el-form-item label="Name" prop="name">
        <el-input v-model="q.name" placeholder="Name" clearable />
      </el-form-item>
      <el-form-item label="Bedrooms" prop="bedrooms">
        <el-input-number v-model="q.bedrooms" :min="0" :controls="false" placeholder="Bedrooms" />
      </el-form-item>
      <el-form-item label="Bathrooms" prop="bathrooms">
        <el-input-number v-model="q.bathrooms" :min="0" :controls="false" placeholder="Bathrooms" />
      </el-form-item>
      <el-form-item label="Storeys" prop="storeys">
        <el-input-number v-model="q.storeys" :min="0" :controls="false" placeholder="Storeys" />
      </el-form-item>
      <el-form-item label="Garages" prop="garages">
        <el-input-number v-model="q.garages" :min="0" :controls="false" placeholder="Garages" />
      </el-form-item>
      <el-form-item label="Price From" prop="price_from">
        <el-input-number v-model="q.price_from" :min="0" :controls="false" placeholder="Price From" />
      </el-form-item>
      <el-form-item label="Price To" prop="price_to">
        <el-input-number v-model="q.price_to" :min="0" :controls="false" placeholder="Price To" />
      </el-form-item>
    </div>
    <div class="actions">
      <el-button type="primary" :loading="loading" @click="onSubmit">Search</el-button>
      <el-button @click="onReset">Reset</el-button>
      <span class="hint">Auto search on change</span>
    </div>
  </el-form>
</template>

<script setup>
import { reactive, ref, watch } from 'vue'
const emit = defineEmits(['search'])
const props = defineProps({ loading: { type: Boolean, default: false }, initial: { type: Object, default: () => ({}) } })
const q = reactive({ name: '', bedrooms: null, bathrooms: null, storeys: null, garages: null, price_from: null, price_to: null, ...props.initial })
const formRef = ref()
const rules = {
  price_to: [{ validator: (_, v, cb) => { if (v != null && q.price_from != null && v < q.price_from) cb(new Error('Must be ≥ Price From')); else cb() }, trigger: 'change' }]
}
let t = null
watch(q, () => {
  clearTimeout(t)
  t = setTimeout(() => emit('search', clean(q)), 350)
}, { deep: true })
const onSubmit = () => {
  formRef.value.validate(valid => { if (valid) emit('search', clean(q)) })
}
const onReset = () => {
  Object.assign(q, { name: '', bedrooms: null, bathrooms: null, storeys: null, garages: null, price_from: null, price_to: null })
  emit('search', clean(q))
}
function clean(obj) {
  const p = {}
  Object.keys(obj).forEach(k => { const v = obj[k]; if (v !== null && v !== '' && v !== undefined) p[k] = v })
  return p
}
</script>

<style scoped>
.form { background: transparent }
.grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px }
.actions { display: flex; align-items: center; gap: 12px; margin-top: 8px }
.hint { font-size: 12px; opacity: .7 }
</style>
