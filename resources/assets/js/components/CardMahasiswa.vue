<template>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="card card-mhs">
            <div class="card-block">
                <div class="title-block">
                    <h4 class="title">{{ mahasiswa.nama }}</h4>
                </div>

                <h6>{{ mahasiswa.id }}</h6>

                <div class="counter-block" v-show="kalab">
                    <div class="item">
                        <span class="counter" @click="daftarBelumMenanggapi">{{ mahasiswa.belum_menanggapi }}</span>
                        <span class="desc" @click="daftarBelumMenanggapi">Belum Menanggapi</span>
                    </div>
                    <div class="item">
                        <span class="counter">{{ mahasiswa.menyetujui }}</span>
                        <span class="desc">Menyetujui</span>
                    </div>
                    <div class="item">
                        <span class="counter">{{ mahasiswa.menolak }}</span>
                        <span class="desc">Menolak</span>
                    </div>
                </div>

                <div class="btn-group-custom" v-show="status == 0">
                    <button type="button" class="btn btn-primary" @click="setuju" :disabled="!bisaSetujui">Setujui</button>
                    <button type="button" class="btn btn-text-warning" @click.prevent="tolak" :disabled="!bisaTunda">Tunda penyetujuan</button>
                </div>
                
                <div class="btn-group" v-show="status == 1">
                    <button type="button" class="btn btn-danger btn-sm" @click="tolak" :disabled="!bisaBatalkanPenyetujuan">Batalkan Penyetujuan</button>
                </div>
                
                <div class="btn-group" role="group" v-show="status == 2">
                    <button type="button" class="btn btn-primary" @click="setuju" :disabled="!bisaSetujui">Setujui</button>
                    <button type="button" class="btn btn-primary" @click="lihatCatatan">Lihat Catatan</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            mahasiswa: [Object]
        },
        data() {
            return {
                tempTextareaValue: '',
                status: 0,
                bisaSetujui: true,
                bisaTunda: true,
                bisaBatalkanPenyetujuan: true,
                kalab: false
            }
        },
        created() {
            this.status = this.$root.status
            this.kalab = this.$root.kalab

            if(this.kalab) {
                if(this.status == 2)
                    this.bisaSetujui = this.mahasiswa.belum_menanggapi == -1 && this.mahasiswa.menolak == 1
                else if(this.status == 0)
                    this.bisaSetujui = this.mahasiswa.belum_menanggapi == 0 && this.mahasiswa.menolak == 0
                this.bisaTunda = this.mahasiswa.belum_menanggapi == 0 && this.mahasiswa.menolak == 0
                this.bisaBatalkanPenyetujuan = this.mahasiswa.belum_menanggapi == -1 && this.mahasiswa.menolak == 0
            }
            else {
                if(this.status == 1) {
                    this.bisaBatalkanPenyetujuan = !this.mahasiswa.konfirmasi
                }
            }
        },
        methods: {
            setuju() {
                let that = this

                $.ajax({
                    url: that.$root.url_setuju,
                    type: 'POST',
                    data: 'nim=' + that.mahasiswa.id,
                    success: function (response) {
                        if (response.success) {
                            swal({
                                icon: 'success',
                                text: response.success
                            })

                            that.$root.removeData(that.mahasiswa.id)
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
                                data: 'nim=' + that.mahasiswa.id + '&catatan=' + textarea.value,
                                success: (response) => {
                                    if(response.success) {
                                        swal({
                                            icon: 'success',
                                            text: response.success
                                        })
                                        that.$root.removeData(that.mahasiswa.id)
                                        swal.close()
                                    }
                                    else if(response.error) {
                                        swal({
                                            icon: 'error',
                                            text: response.error
                                        })
                                    }
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
            },
            daftarBelumMenanggapi() {
                let text = document.createElement('p')
                text.innerHTML = 'Memuat'
                setTimeout(function () {
                    text.innerHTML = 'Hello World !'
                }, 2000)
                swal({
                    title: 'Kasublab yang belum menanggapi',
                    content: text
                })
            },
            batalkanPenyetujuan() {
                let that = this
                swal({
                    icon: 'warning',
                    title: 'Apa anda yakin ?',
                    buttons: ['Batal', 'Yakin']
                }).then(function (response) {
                    if(response) {
                        $.ajax({
                            url: that.url_batal,
                            type: 'POST',
                            data: 'nim=' + that.mahasiswa.id,
                            success: function (response) {
                                if(response.success) {
                                    swal({
                                        icon: 'success',
                                        text: response.success
                                    })
                                    that.$root.removeData(that.mahasiswa.id)
                                } 
                                else if(response.error) {
                                    swal({
                                        icon: 'error',
                                        text: response.error
                                    })
                                }
                            }
                        })
                    }
                })
            },
            lihatCatatan() {
                let that = this
                    
                let p = document.createElement('p')
                p.innerText = 'Sedang memuat...'
                p.style.textAlign = 'Left'

                swal({
                    title: 'Catatan',
                    content: p
                })

                $.ajax({
                    url: that.$root.url_lihat_catatan,
                    type: 'POST',
                    data: 'nim=' + that.mahasiswa.id,
                    success: function (response) {
                        if(response.catatan) {
                            p.innerText = response.catatan
                        }
                    }
                })
            }
        }
    }
</script>