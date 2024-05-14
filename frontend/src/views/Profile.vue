<template>
    <v-card>
        <v-card-item>
            <v-card-title class='text-center'>
                Profile
            </v-card-title>
        </v-card-item>

        <v-card-text>
            <v-row>
                <v-avatar color='blue-grey-lighten-5' size="80" class='mx-auto mt-4 mb-8'>
                    <span class="text-h5" v-if='!user.picture'>
                        {{ initials }}
                    </span>
                    <v-img
                        :src="user.picture"
                        v-else
                    />
                
                </v-avatar>
            </v-row>

            <v-file-input
                label="Change Image"
                v-model='user.file'
                prepend-icon="mdi-camera"
                accept="image/png, image/jpeg, image/bmp"
                clearable
            />

            <v-text-field 
                label="Name"
                v-model='user.name'
                prepend-icon="mdi-account"
                clearable
            />

            <v-text-field 
                label="Email"
                v-model='user.email'
                prepend-icon="mdi-at"
                clearable
            />
        </v-card-text>

        <v-card-actions class='mb-4'>
            <v-row class='text-center'>
                <v-btn @click="update" color='primary' class='mx-auto' variant='elevated'>
                    Update 
                </v-btn>
            </v-row>
        </v-card-actions>
    </v-card>
</template>

<script setup>
    import { ref, computed, onMounted } from 'vue'
    import axios from 'axios'

    const user = ref({})
    
    const initials = computed(() => {
            if(!user.value.name) {
                return ""
            }

            const names = user.value.name.split(" ")
            let initials = ''

            for(const name of names)
            {
                initials += name.charAt(0)
            }

            return initials.toUpperCase()
        })

    onMounted(() => {
        axios.get('/api/user')
            .then(response => {
                user.value = response.data
            })
            .catch(error => {
                console.log(error.response.data)
            })
    })

    function update() 
    {
        axios.post(
                '/api/user/update', 
                {
                    _method: 'put',
                    name: user.value.name,
                    email: user.value.email,
                    file: user.value.file,
                },
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            )
            .then(response => {
                user.value = response.data
            })
            .catch(error => {
                console.log(error.response.data)
            })
    }
</script>

<style scoped>

</style>