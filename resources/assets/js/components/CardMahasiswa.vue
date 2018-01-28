<template>
    <div class="col-md-4">
        <div class="card card-mhs">
            <div class="card-block">
                <div class="title-block">
                    <h4 class="title">{{ nama }}</h4>
                </div>

                <h6>{{ nim }}</h6>

                <div class="counter-block">
                    <div class="item">
                        <span class="counter">{{ belumMenanggapi }}</span>
                        <span class="desc">Belum Menanggapi</span>
                    </div>
                    <div class="item">
                        <span class="counter">{{ menyetujui }}</span>
                        <span class="desc">Menyetujui</span>
                    </div>
                    <div class="item">
                        <span class="counter">{{ menolak }}</span>
                        <span class="desc">Menolak</span>
                    </div>
                </div>

                <div class="btn-group-custom">
                    <button type="button" class="btn btn-primary" @click="setuju">Setujui</button>
                    <button type="button" class="btn btn-text-warning" @click.prevent="tolak">Tunda penyetujuan</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            nama: [String],
            id: [String],
            nim: [String],
            belumMenanggapi: [String, Number],
            menyetujui: [String, Number],
            menolak: [String, Number]
        },
        data() {
            return {
                tempTextareaValue: '',
                status: 0
            }
        },
        created() {
            this.status = this.$root.status
        },
        methods: {
            setuju() {
                let that = this

                $.ajax({
                    url: that.$root.url_setuju,
                    type: 'POST',
                    data: 'nim=' + that.id,
                    success: function (response) {
                        if (response.success) {
                            swal({
                                icon: 'success',
                                text: response.success
                            })

                            that.$root.removeData(that.id)
                        } else if (response.error) {
                            swal({
                                icon: 'error',
                                text: response.error
                            })
                        }
                    },
                    error: function (response) {
                        swal({
                            icon: 'error',
                            text: 'Gagal terhubung ke server. Silahkan coba beberapa saat lagi'
                        })
                    }
                })
            },
            tolak() {
                let that = this
                let textarea = document.createElement('textarea')
                textarea.className = 'form-control'
                textarea.value = this.tempTextareaValue

                swal({
                    title: 'Berikan Catatan',
                    icon: 'warning',
                    button: {
                        text: 'Kirim',
                        closeModal: false
                    },
                    content: textarea
                }).then((value) => {
                    if (value) {
                        if (textarea.value !== '') {
                            $.ajax({
                                url: that.$root.url_tolak,
                                type: 'POST',
                                data: 'nim=' + that.id + '&catatan=' + textarea.value,
                                success: (response) => {
                                    swal({
                                        icon: 'success',
                                        text: 'Berhasil mengirim catatan'
                                    })
                                    that.$root.removeData(that.id)
                                    swal.close()
                                },
                                error: (response) => {
                                    swal({
                                        icon: 'error',
                                        text: 'Gagal terhubung ke server. Silahkan coba beberapa saat lagi'
                                    }).then(() => {
                                        that.tempTextareaValue = textarea.value
                                        that.tolak()
                                    })
                                }
                            })
                        } else {
                            swal.stopLoading()
                            swal.close()
                            that.tolak()
                        }
                    }
                })

                textarea.focus()
            }
        }
    }
</script>