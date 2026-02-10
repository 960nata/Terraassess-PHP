import { defineStore } from 'pinia'
import axios from 'axios'

export const useTugasStore = defineStore('tugas', {
  state: () => ({
    tugas: [],
    currentTugas: null,
    loading: false,
    pagination: {
      current_page: 1,
      last_page: 1,
      per_page: 10,
      total: 0
    },
    filters: {
      search: '',
      tipe_tugas: '',
      status: ''
    }
  }),

  getters: {
    filteredTugas: (state) => {
      let filtered = state.tugas

      if (state.filters.search) {
        filtered = filtered.filter(tugas => 
          tugas.judul.toLowerCase().includes(state.filters.search.toLowerCase()) ||
          tugas.deskripsi.toLowerCase().includes(state.filters.search.toLowerCase())
        )
      }

      if (state.filters.tipe_tugas) {
        filtered = filtered.filter(tugas => tugas.tipe_tugas === state.filters.tipe_tugas)
      }

      if (state.filters.status) {
        filtered = filtered.filter(tugas => {
          const now = new Date()
          const deadline = new Date(tugas.deadline)
          
          switch (state.filters.status) {
            case 'upcoming':
              return deadline > now
            case 'overdue':
              return deadline < now && !tugas.submission_status?.submitted
            case 'completed':
              return tugas.submission_status?.submitted
            default:
              return true
          }
        })
      }

      return filtered
    },

    tugasByType: (state) => {
      const grouped = {}
      state.tugas.forEach(tugas => {
        if (!grouped[tugas.tipe_tugas]) {
          grouped[tugas.tipe_tugas] = []
        }
        grouped[tugas.tipe_tugas].push(tugas)
      })
      return grouped
    },

    upcomingDeadlines: (state) => {
      const now = new Date()
      const nextWeek = new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000)
      
      return state.tugas.filter(tugas => {
        const deadline = new Date(tugas.deadline)
        return deadline > now && deadline <= nextWeek
      }).sort((a, b) => new Date(a.deadline) - new Date(b.deadline))
    }
  },

  actions: {
    async fetchTugas(page = 1) {
      this.loading = true
      try {
        const params = {
          page,
          per_page: this.pagination.per_page,
          ...this.filters
        }

        const response = await axios.get('/tugas', { params })
        this.tugas = response.data.data.data
        this.pagination = {
          current_page: response.data.data.current_page,
          last_page: response.data.data.last_page,
          per_page: response.data.data.per_page,
          total: response.data.data.total
        }
      } catch (error) {
        console.error('Error fetching tugas:', error)
        throw error
      } finally {
        this.loading = false
      }
    },

    async fetchTugasById(id) {
      this.loading = true
      try {
        const response = await axios.get(`/tugas/${id}`)
        this.currentTugas = response.data.data
        return response.data.data
      } catch (error) {
        console.error('Error fetching tugas:', error)
        throw error
      } finally {
        this.loading = false
      }
    },

    async createTugas(data) {
      this.loading = true
      try {
        const formData = new FormData()
        
        Object.keys(data).forEach(key => {
          if (key === 'file_tugas' && data[key]) {
            data[key].forEach(file => {
              formData.append('file_tugas[]', file)
            })
          } else if (key !== 'file_tugas') {
            formData.append(key, data[key])
          }
        })

        const response = await axios.post('/tugas', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
        
        this.tugas.unshift(response.data.data)
        return response.data.data
      } catch (error) {
        console.error('Error creating tugas:', error)
        throw error
      } finally {
        this.loading = false
      }
    },

    async updateTugas(id, data) {
      this.loading = true
      try {
        const formData = new FormData()
        
        Object.keys(data).forEach(key => {
          if (key === 'file_tugas' && data[key]) {
            data[key].forEach(file => {
              formData.append('file_tugas[]', file)
            })
          } else if (key !== 'file_tugas') {
            formData.append(key, data[key])
          }
        })

        const response = await axios.put(`/tugas/${id}`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
        
        const index = this.tugas.findIndex(t => t.id === id)
        if (index !== -1) {
          this.tugas[index] = response.data.data
        }
        
        return response.data.data
      } catch (error) {
        console.error('Error updating tugas:', error)
        throw error
      } finally {
        this.loading = false
      }
    },

    async deleteTugas(id) {
      this.loading = true
      try {
        await axios.delete(`/tugas/${id}`)
        this.tugas = this.tugas.filter(t => t.id !== id)
      } catch (error) {
        console.error('Error deleting tugas:', error)
        throw error
      } finally {
        this.loading = false
      }
    },

    async searchTugas(query) {
      this.loading = true
      try {
        const response = await axios.get('/tugas/search', {
          params: { q: query }
        })
        this.tugas = response.data.data.data
        this.pagination = {
          current_page: response.data.data.current_page,
          last_page: response.data.data.last_page,
          per_page: response.data.data.per_page,
          total: response.data.data.total
        }
      } catch (error) {
        console.error('Error searching tugas:', error)
        throw error
      } finally {
        this.loading = false
      }
    },

    async getStatistics() {
      try {
        const response = await axios.get('/tugas/statistics')
        return response.data.data
      } catch (error) {
        console.error('Error fetching statistics:', error)
        throw error
      }
    },

    async getUpcomingDeadlines(days = 7) {
      try {
        const response = await axios.get('/tugas/upcoming-deadlines', {
          params: { days }
        })
        return response.data.data
      } catch (error) {
        console.error('Error fetching upcoming deadlines:', error)
        throw error
      }
    },

    setFilters(filters) {
      this.filters = { ...this.filters, ...filters }
    },

    clearFilters() {
      this.filters = {
        search: '',
        tipe_tugas: '',
        status: ''
      }
    },

    setPagination(pagination) {
      this.pagination = { ...this.pagination, ...pagination }
    }
  }
})
