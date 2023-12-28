import axios from "axios"

export default {
    getPagesByChapterId(chapterId) {
        return axios.get('/api/chapters/' + chapterId + '/pages')
    },
    getSeriesByUniverseId(universeId) {
        return axios.get('/api/series/' + universeId)
    },

    getUniversesByUserId(userId, withSeries = false) {
        const withSeriesBool = withSeries ? 1 : 0

        return axios.get('/api/universes/', {
            params: {
                user_id: userId,
                with_series: withSeriesBool,
            }
        })
    },
    getSeriesByGenre(genre) {
        return axios.get('/api/series/genres', {
            params: {
                genre: genre,
            }
        })
    },
    getAllGenres() {
        return axios.get('/api/genres')
    },
    deleteSeriesThumbnail(serverId) {
        return axios.delete('/api/series/' + serverId + '/thumbnail')
    },
    getElements(type, id){
        return axios.get('/api/elements', {
            params: {
                type: type,
                content_id: id,
            }
        })
    },
    getParentContent(type, id){
        return axios.get('/api/content/get-parent', {
            params: {
                type: type,
                id: id,
            }
        })
    }
}