<template>
    <div>
        <button type="button" id="showModal" @click="handleNew" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Añadir
        </button>
        <div class="modal fade text-start" id="exampleModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ (editing) ? 'Editar texto' : 'Añadir nuevo' }}</h5>
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
                                <label for="formFile" class="form-label">Imagen</label>
                                <input class="form-control" type="file" id="formFile" @change="handleFileUpload($event)">
                            </div>
                            <div class="mb-3">
                                <label for="formFile" class="form-label">Doctor</label>
                                <input type="text" class="form-control" v-model="doctor">
                            </div>
                            <div class="mb-3">
                                <label for="formFile" class="form-label">Localización</label>
                                <input type="text" class="form-control" v-model="location">
                            </div>
                            <div class="mb-3">
                                <label for="formFile" class="form-label">Título</label>
                                <input type="text" class="form-control" v-model="title">
                            </div>
                            <div class="mb-3">
                                <label for="formFile" class="form-label">Texto</label>
                                <textarea class="form-control" v-model="text"></textarea>
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
            text: '',
            doctor: '',
            title: '',
            location: '',
        }
    },
    created() {
        this.$root.$on('edit-text', (id) => this.handleEdit(id))
    },
    methods: {
        handleNew: function () {
            this.editing = false
            this.id = null
        },
        handleEdit: function (text) {
            $("#showModal").click()
            this.editing  = true
            this.id       = text.id
            this.text     = text.text
            this.doctor   = text.doctor
            this.title    = text.title
            this.location = text.location
        },
        handleFileUpload: function (evt) {
            this.file = evt.target.files[0]
        },
        handleSubmit: async function (evt) {
            evt.preventDefault()

            let url = '/api/texts/'

            if (this.editing) {
                url = '/api/texts/' + this.id
            }

            const formData = new FormData()
            formData.append('image',this.file)
            formData.append('text',this.text)
            formData.append('doctor',this.doctor)
            formData.append('title',this.title)
            formData.append('location',this.location)

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
                toastr.success(this.editing ? 'Texto actualizado' : 'Texto creado')
                $("#exampleModal .btn-close").click()
                this.$root.$emit('update-texts')
            }
        }
    }
};
</script>
