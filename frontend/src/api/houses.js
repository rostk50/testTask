import client from './client'
export async function searchHouses(params) {
    const res = await client.get('/api/houses', { params })
    return res.data
}
