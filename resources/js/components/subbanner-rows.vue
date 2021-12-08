<template>
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Período</th>
                <th>Icono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="banner in banners" :key="banner.id">
                <td>{{banner.id}}</td>
                <td>{{banner.title}}</td>
                <td>{{banner.period}}</td>
                <td>{{banner.icon}}</td>
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
            this.$root.$on('update-subbanners', () => { this.getSubBanners() })
        },
        mounted() {
            this.getSubBanners()
        },
        methods: {
            getSubBanners: async function() {
                const res = await fetch('/api/subbanner')
                const data = await res.json()
                this.banners = data
            },
            handleDelete: async function(id) {
                await fetch('/api/subbanner/' + id,{
                    method: 'DELETE',
                })
                toastr.success('Sub Banner eliminado')

                this.getSubBanners()
            },
            handleUpdate: function(banner) {
                this.$root.$emit('edit-subbanner',banner)
            }
        }
    }
</script>