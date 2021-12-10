<template>
    <div>
        <button type="button" id="showModal" @click="handleNew" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Añadir
        </button>
        <div class="modal fade text-start" id="exampleModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ (editing) ? 'Editar banner' : 'Añadir nuevo' }}</h5>
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
                                <label for="formFile" class="form-label">Banner</label>
                                <input class="form-control" type="file" id="formFile" @change="handleFileUpload($event)">
                            </div>
                            <div class="mb-3">
                                <label for="formFile" class="form-label">Texto</label>
                                <input class="form-control" type="file" id="formTitleFile" @change="handleTitleUpload($event)">
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
            title: '',
            editing: false,
            id: null,
        }
    },
    created() {
        this.$root.$on('edit', (id) => this.handleEdit(id))
    },
    methods: {
        handleNew: function () {
            this.editing = false
            this.id = null
        },
        handleTitleUpload: function(evt) {
            this.title = evt.target.files[0]
        },
        handleEdit: function (banner) {
            $("#showModal").click()
            this.editing = true
            this.id = banner.id
        },
        handleFileUpload: function (evt) {
            this.file = evt.target.files[0]
        },
        handleSubmit: async function (evt) {
            evt.preventDefault()

            let url = '/api/banner'

            if (this.editing) {
                url = '/api/banner/' + this.id
            }

            const formData = new FormData()
            formData.append('file',this.file)
            formData.append('title',this.title)

            const res = await axios({
                method: 'POST',
                url: url,
                data: formData,
                headers: {
                    'Content-Type': 'multipart/form-data',
                }
            })

            if (res.status == 200) {
                toastr.success(this.editing ? 'Banner actualizado' : 'Banner creado')
                $("#exampleModal .btn-close").click()
                this.$root.$emit('update-banners')
            }
        }
    }
};
</script>
