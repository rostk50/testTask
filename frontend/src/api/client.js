import axios from 'axios'
const base = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8080'
const client = axios.create({ baseURL: base })
export default client
