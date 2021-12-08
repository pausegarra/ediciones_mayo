<template>
    <div>
        <button type="button" id="showModal" @click="handleNew" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Añadir
        </button>
        <div class="modal fade text-start" id="exampleModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ (editing) ? 'Editar subbanner' : 'Añadir nuevo' }}</h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <form @submit="handleSubmit($event)">
                            <div class="mb-3">
                                <label for="formFile" class="form-label">Icono</label>
                                <input class="form-control" type="file" id="formFile" @change="handleFileUpload($event)">
                            </div>
                            <div class="mb-3">
                                <label for="formFile" class="form-label">Título</label>
                                <input class="form-control" type="input" v-model="title">
                            </div>
                            <div class="mb-3">
                                <label for="formFile" class="form-label">Período</label>
                                <input class="form-control" type="date" v-model="period">
                            </div>
                            <button class="btn btn-sm btn-outline-primary">Guardar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import $ from "jquery";
import toastr from 'toastr'

export default {
    data() {
        return {
            file: '',
            editing: false,
            id: null,
            title: '',
            period: '',
        }
    },
    created() {
        this.$root.$on('edit-subbanner', (id) => this.handleEdit(id))
    },
    methods: {
        handleNew: function () {
            this.editing = false
            this.id = null
        },
        handleEdit: function (subBanner) {
            $("#showModal").click()
            this.editing = true
            this.id = subBanner.id
            this.title = subBanner.title
            this.period = subBanner.period
        },
        handleFileUpload: function (evt) {
            this.file = evt.target.files[0]
        },
        handleSubmit: async function (evt) {
            evt.preventDefault()

            let url = '/api/subbanner/'

            if (this.editing) {
                url = '/api/subbanner/' + this.id
            }

            const formData = new FormData()
            formData.append('icon',this.file)
            formData.append('title',this.title)
            formData.append('period',this.period)

            const res = await axios({
                method: 'POST',
                url: url,
                data: formData,
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Accept': 'application/json'
                }
            })

            if (res.status == 200) {
                toastr.success(this.editing ? 'Sub Banner actualizado' : 'Sub Banner creado')
                $("#exampleModal .btn-close").click()
                this.$root.$emit('update-subbanners')
            }
        }
    }
};
</script>
