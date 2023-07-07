<template>
    Here are all your followers
    </template>
    
    <script>
    
    
        export default {
    
            data() {
                return {
                    following: [],
                    followingLoaded: false,
                }
            },

            mounted() {
                axios.get('/api/following', {
                    params: {
                        user_id: this.user.user_id,
                    }
                }).then(response => {
                    this.following = response.data
                    this.followingLoaded = true
        
                    console.log(this.following)
                }).catch(error => console.log(error))
            },
        }
    </script>