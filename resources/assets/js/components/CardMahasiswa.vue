<template>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="card card-mhs">
            <div class="card-block">
                <div class="title-block">
                    <div class="identity">
                        <h4 class="title">{{ mahasiswa.nama }}</h4>
                        <h6>{{ mahasiswa.id }}</h6>
                        <h6 style="font-size: 12px;color: #888">{{ mahasiswa.prodi }}</h6>
                    </div>
                </div>

                <div style="height: 30px" v-show="kalab && status == 1"></div>

                <div class="counter-block" v-show="kalab && status != 1">
                    <div class="item">
                        <span class="counter text-warning" @click="daftarBelumMenanggapi">{{ mahasiswa.belum_menanggapi }}</span>
                        <span class="desc" @click="daftarBelumMenanggapi">Belum Menanggapi</span>
                    </div>
                    <div class="item">
                        <span class="counter text-success" @click="daftarMenyetujui">{{ mahasiswa.menyetujui }}</span>
                        <span class="desc" @click="daftarMenyetujui">Telah Menyetujui</span>
                    </div>
                    <div class="item">
                        <span class="counter text-danger" @click="daftarBelumMenyetujui">{{ mahasiswa.menolak }}</span>
                        <span class="desc" @click="daftarBelumMenyetujui">Belum Menyetujui</span>
                    </div>
                </div>

                <p class="alert alert-info" v-show="telahDisetujui && !kalab">
                    Mahasiswa ini telah disetujui oleh Kalab
                </p>

                <p class="alert alert-warning" v-show="!telahDisetujui && !kalab && status == 1">
                    Mahasiswa ini belum disetujui oleh Kalab
                </p>

                <div :class="{'card-footer-custom': true, 'add-margin': (status == 2 || status == 0) && !kalab}">
                    <div class="tools">
                        <button type="button" class="btn btn-primary unduh-berkas" @click="unduhBerkas" :disabled="typeof mahasiswa.dir !== 'string'">
                            <i class="fa fa-download"></i>&nbsp;&nbsp;Unduh Berkas</button>
                    </div>
                    <div class="btn-group" v-show="status == 0">
                        <button type="button" class="btn btn-primary" @click="setuju" :disabled="!bisaSetujui">
                            <i class="fa fa-check"></i>&nbsp;&nbsp;Setujui</button>
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

        <form :action="url_unduh" method="post" ref="form">
            <input type="hidden" name="_token" :value="csrf" />
            <input type="hidden" name="nim" :value="mahasiswa.id" />
        </form>
    </div>
</template>

<script>
    export default {
        props: {
            mahasiswa: [Object],
        },
        data() {
            return {
                tempTextareaValue: '',
                status: 0,
                bisaSetujui: true,
                bisaTunda: true,
                bisaBatalkanPenyetujuan: true,
                kalab: false,
                telahDisetujui: false,
                csrf: null,
                url_unduh: null
            }
        },
        created() {
            this.status = this.$root.status
            this.kalab = this.$root.kalab
            this.csrf = $('meta[name="csrf-token"]').attr('content')
            this.url_unduh = this.$root.url_unduh

            this.telahDisetujui = this.mahasiswa.belum_menanggapi == 0 && this.mahasiswa.menolak == 0

            if (this.kalab) {
                if (this.status == 2) {
                    this.bisaSetujui = this.mahasiswa.menolak == 1 && this.mahasiswa.belum_menanggapi == 0
                } else if (this.status == 0) {
                    this.bisaSetujui = this.mahasiswa.belum_menanggapi == 1 && this.mahasiswa.menolak == 0
                } else if (status == 1) {
                    this.bisaBatalkanPenyetujuan = true
                }
                this.bisaTunda = true
            } else {
                if (this.status == 1) {
                    this.bisaBatalkanPenyetujuan = !this.mahasiswa.konfirmasi || this.mahasiswa.belum_menanggapi > 0
                }
            }
        },
        methods: {
            setuju() {
                let that = this

                swal({
                    title: 'Apa anda yakin ?',
                    icon: 'warning',
                    buttons: {
                        cancel: {
                            text: 'Batal',
                            visible: true
                        },
                        confirm: {
                            text: 'Yakin',
                            closeModal: false
                        }
                    }
                }).then((sure) => {
                    if(sure) {
                        that.prosesSetuju()
                    }
                })
            },
            prosesSetuju() {
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
            tolak(e) {
                let that = this
                let textarea = document.createElement('textarea')
                textarea.className = 'form-control'
                textarea.value = this.tempTextareaValue

                swal({
                    title: 'Berikan Catatan',
                    icon: 'warning',
                    buttons: {
                        cancel: {
                            text: 'Batal',
                            visible: true
                        },
                        confirm: {
                            text: 'Kirim',
                            closeModal: false
                        }
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
                                    if (response.success) {
                                        swal({
                                            icon: 'success',
                                            text: response.success
                                        })

                                        if(typeof e === 'string' && e === 'edit') {
                                            swal.close()
                                            return
                                        }

                                        that.$root.removeData(that.mahasiswa.id)
                                        swal.close()
                                    } else if (response.error) {
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
                if(this.mahasiswa.belum_menanggapi === 0)
                    return

                let that = this
                let root = document.createElement('swal-list')
                root.innerText = 'Sedang memuat...'

                swal({
                    title: 'Yang belum menanggapi',
                    content: root
                })

                $.ajax({
                    url: that.$root.url_daftar_belum_menanggapi,
                    type: 'POST',
                    data: 'nim=' + that.mahasiswa.id,
                    success: (response) => {
                        root.innerHTML = ''

                        let listVue = new Vue({
                            el: root,
                            data: {
                                list: response
                            },
                        })
                    }
                })
            },
            daftarMenyetujui() {
                if(this.mahasiswa.menyetujui === 0)
                    return

                let that = this
                let root = document.createElement('swal-list')
                root.innerText = 'Sedang memuat...'

                swal({
                    title: 'Yang menyetujui',
                    content: root
                })

                $.ajax({
                    url: that.$root.url_daftar_menyetujui,
                    type: 'POST',
                    data: 'nim=' + that.mahasiswa.id,
                    success: (response) => {
                        root.innerHTML = ''

                        let listVue = new Vue({
                            el: root,
                            data: {
                                list: response
                            },
                        })
                    }
                })
            },
            daftarBelumMenyetujui() {
                if(this.mahasiswa.menolak === 0)
                    return

                let that = this
                let root = document.createElement('swal-list')
                root.innerText = 'Sedang memuat...'

                swal({
                    title: 'Yang belum menyetujui',
                    content: root
                })

                $.ajax({
                    url: that.$root.url_daftar_belum_menyetujui,
                    type: 'POST',
                    data: 'nim=' + that.mahasiswa.id,
                    success: (response) => {
                        root.innerHTML = ''

                        let listVue = new Vue({
                            el: root,
                            data: {
                                list: response
                            },
                        })
                    }
                })
            },
            batalkanPenyetujuan() {
                let that = this
                swal({
                    icon: 'warning',
                    title: 'Apa anda yakin ?',
                    buttons: ['Batal', 'Yakin']
                }).then(function (response) {
                    if (response) {
                        $.ajax({
                            url: that.url_batal,
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
                    content: p,
                    buttons: {
                        edit: {
                            text: 'Edit Catatan',
                            value: 'edit'
                        },
                        confirm: true
                    }
                }).then((value) => {
                    if(value === 'edit') {
                        that.tolak('edit')
                    }
                })

                $.ajax({
                    url: that.$root.url_lihat_catatan,
                    type: 'POST',
                    data: 'nim=' + that.mahasiswa.id,
                    success: function (response) {
                        if (response.catatan) {
                            p.innerText = response.catatan
                        }
                    }
                })
            },
            unduhBerkas() {
                Vue.nextTick(() => {
                    this.$refs.form.submit()
                })
            }
        }
    }
</script>