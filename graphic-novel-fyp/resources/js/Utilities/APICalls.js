export default {
    getPagesByChapterId(chapterId) {
        return axios.get('/api/chapters/' + chapterId + '/pages')
    },
    getComments(commentableId, commentableType, attachReplies = false) {
        return axios.get('/api/comments', {
            params: {
                commentable_id: commentableId,
                commentable_type: commentableType,
                attach_replies: attachReplies,
            }
        })
    },
    getUserComments(userId) {
        return axios.get('/api/comments', {
            params: {
                user_id: userId,
            }
        })
    },
    getFollowings(userId) {
        return axios.get('/api/followings', {
            params: {
                user_id: userId,
            }
        })
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
    getPopularSeries(genre) {
        return axios.get('/api/series/popular', {
            params: {
                genre: genre,
            }
        })
    },
    getAllGenres() {
        return axios.get('/api/genres')
    }
}