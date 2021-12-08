<template>
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="banner in banners" :key="banner.id">
                <td>{{banner.id}}</td>
                <td>{{banner.url}}</td>
                <td>
                    <button class="btn btn-outline-danger btn-sm" @click="handleDelete(banner.id)">Eliminar</button>
                    <button class="btn btn-outline-warning btn-sm" @click="handleUpdate(banner)">Editar</button>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    import toastr from 'toastr'
    export default {
        data() {
            return {
                banners: []
            }
        },
        created() {
            this.$root.$on('update-banners', () => { this.getBanners() })
        },
        mounted() {
            this.getBanners()
        },
        methods: {
            getBanners: async function() {
                const res = await fetch('/api/banner')
                const data = await res.json()
                this.banners = data
            },
            handleDelete: async function(id) {
                const res = await fetch('/api/banner/' + id,{
                    method: 'DELETE',
                })
                toastr.success('Banner eliminado')

                this.getBanners()
            },
            handleUpdate: function(banner) {
                this.$root.$emit('edit',banner)
            }
        }
    }
</script>