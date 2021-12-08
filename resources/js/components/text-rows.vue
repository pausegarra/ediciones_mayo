<template>
    <table class="table table-striped table-sm" style="table-layout: fixed; word-wrap: break-word;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Texto</th>
                <th>Doctor</th>
                <th>Localización</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="text in texts" :key="text.id">
                <td>{{text.id}}</td>
                <td>{{text.title}}</td>
                <td>{{text.text}}</td>
                <td>{{text.doctor}}</td>
                <td>{{text.location}}</td>
                <td>
                    <a v-bind:href="'/storage/texts_images/' + text.url" target="_blank" class="btn btn-sm btn-outline-primary">Ver</a>
                </td>
                <td>
                    <button class="btn btn-outline-danger btn-sm" @click="handleDelete(text.id)">Eliminar</button>
                    <button class="btn btn-outline-warning btn-sm" @click="handleUpdate(text)">Editar</button>
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
                texts: []
            }
        },
        created() {
            this.$root.$on('update-texts', () => { this.getText() })
        },
        mounted() {
            this.getText()
        },
        methods: {
            getText: async function() {
                const res = await fetch('/api/texts')
                const data = await res.json()
                this.texts = data
            },
            handleDelete: async function(id) {
                await fetch('/api/texts/' + id,{
                    method: 'DELETE',
                })
                toastr.success('Texto eliminado')

                this.getText()
            },
            handleUpdate: function(text) {
                this.$root.$emit('edit-text',text)
            }
        }
    }
</script>