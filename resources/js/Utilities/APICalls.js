import axios from "axios"

export default {
    getPagesByChapterId(chapterId) {
        return axios.get('/api/chapters/' + chapterId + '/pages')
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
    getSeriesByUniverseId(universeId) {
        return axios.get('/api/series/' + universeId)
    },
    getChaptersBySeriesId(seriesId) {
        return axios.get('/api/series/' + seriesId + '/chapters')
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
    },
    getContent(type, id){
        // Switch
        switch(type){
            case 'page':
                return this.getPage(id)
            case 'chapter':
                return this.getChapter(id)
            case 'series':
                return this.getSeries(id)
            case 'universe':
                return this.getUniverse(id)
        }
    },
    getPage(pageId){
        return axios.get('/api/pages/' + pageId)
    },
    getChapter(chapterId){
        return axios.get('/api/chapters/' + chapterId)
    },
    getSeries(seriesId){
        return axios.get('/api/series/' + seriesId)
    },
    getUniverse(universeId){
        return axios.get('/api/universes/' + universeId)
    },
    getUniverseThumbnail(universeId){
        return axios.get('/api/universes/' + universeId + '/thumbnail')
    },
    searchElements(query, limit, searchType='elements'){
        return axios.get('/api/search', {
            params: {
                searchType: searchType,
                // userId: userId,
                search: query,
                limit: limit,
            }
        })
    },
    searchMention(query, limit, searchType='elements'){
        return axios.get('/api/search/mention', {
            params: {
                searchType: searchType,
                // userId: userId,
                search: query,
                limit: limit,
            }
        })
    },
    getRecents(limit){
        return axios.get('/api/recents', {
            params: {
                limit: limit,
            }
        })
    },
    getFilepondPages(chapterId){
        return axios.get('/api/chapters/' + chapterId + '/filepond-pages')
    }
}