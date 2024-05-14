<template>
    <v-card>
        <v-card-item>
            <v-card-title class='text-center'>
                Profile
            </v-card-title>
        </v-card-item>

        <v-card-text>
        </v-card-text>
    </v-card>
</template>

<script setup>
    import { ref, onMounted } from 'vue'
    import axios from 'axios'

    const user = ref({})

    onMounted(() => {
        axios.get('/api/user')
            .then(response => {
                user.value = response.data.user
            })
            .catch(error => {
                console.log(error.response.data)
            })
    })
</script>

<style scoped>

</style>