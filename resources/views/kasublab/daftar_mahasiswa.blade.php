@extends('layouts.global')

@push('css')
<link href="{{ asset('css/fonts.css') }}" rel="stylesheet"/>
<link href="{{ asset('css/kasublab.css') }}" rel="stylesheet"/>
@endpush

@section('content')

<ul class="ui-tab">
    <li class="{{ !request()->has('status') || (request()->has('status') && request()->get('status') == 0) ? 'active' : '' }}"><a href="{{ route('kasublab.daftar.mahasiswa', ['status' => 0]) }}">Belum ditanggapi</a></li>
    <li class="{{ (request()->has('status') && request()->get('status') == 1) ? 'active' : '' }}"><a href="{{ route('kasublab.daftar.mahasiswa', ['status' => 1]) }}">Telah disetujui</a></li>
    <li class="{{ (request()->has('status') && request()->get('status') == 2) ? 'active' : '' }}"><a href="{{ route('kasublab.daftar.mahasiswa', ['status' => 2]) }}">Belum Disetujui </a></li>
</ul>



<div id="daftar-mahasiswa">
    <div class="card" v-show="daftarMahasiswa.length == 0">
        <div class="card-block">
            <p>Tidak ada data</p>
        </div>
    </div>

    <div v-show="daftarMahasiswa.length > 0">
            <div class="col-md-4">
                <form id="cari" @submit.prevent="cari">
                    <input v-on:keyup="cari" type="text" v-model="keyword" placeholder="Cari mahasiswa ..."/>
                    <button><i class="fa fa-search"></i></button>
                </form>
            </div>
        </div>

    <div class="row" v-show="filteredMahasiswa.length == 0">
        <card-mhs v-for="mahasiswa in daftarMahasiswa" :key="mahasiswa.id" :mahasiswa="mahasiswa"></card-mhs>
    </div>
    
    <div class="row" v-show="filteredMahasiswa.length > 0">
        <card-mhs v-for="mahasiswa in filteredMahasiswa" :key="mahasiswa.id" :mahasiswa="mahasiswa"></card-mhs>
    </div>

    <div class="row">
        <div class="col">
            <button v-show="canLoadMore" class="btn btn-primary" @click="loadMore">Tampilkan lainnya</button>
        </div>
    </div>
       
</div>

@endsection

@push('js')
<script>
let daftarMahasiswa = new Vue({
    el: '#daftar-mahasiswa',
    data: {
        daftarMahasiswa: {!! $daftarMahasiswa !!},
        url_setuju: '{{ route('surat.setuju') }}',
        url_tolak: '{{ route('surat.tolak') }}',
        url_lihat_catatan: '{{ route('kasublab.lihat.catatan') }}',
        url_daftar_belum_menanggapi: '{{ route('kasublab.daftar.belum.menanggapi') }}',
        url_daftar_menyetujui: '{{ route('kasublab.daftar.setuju') }}',
        url_daftar_belum_menyetujui: '{{ route('kasublab.daftar.belum.setuju') }}',
        status: {{ request()->has('status') ? request()->get('status') : 0 }},
        canLoadMore: true,
        jumlahTotal: {{ $jumlahTotal }},
        kalab: {{ Auth::user()->isKalab() ? 'true' : 'false' }},
        keyword: null,
        filteredMahasiswa: []
    },
    created() {
        this.canLoadMore = (this.daftarMahasiswa.length > 0 && this.daftarMahasiswa.length < this.jumlahTotal)
    },
    methods: {
        removeData(id) {
            this.daftarMahasiswa = this.daftarMahasiswa.filter((item) => {
                return item.id != id
            })
        },
        loadMore() {
            let that = this

            $.ajax({
                url: '{{ route('kasublab.loadmore.mahasiswa') }}',
                type: 'POST',
                data: 'status={{ request()->has('status') ? request()->get('status') : 0 }}&count_loaded=' + that.daftarMahasiswa.length,
                success: function (response) {
                    if(response.length === 0) {
                        that.canLoadMore = false
                    }
                    else {
                        for(i in response)
                            that.daftarMahasiswa.push(response[i])
                    }
                }
            })
        },
        cari() {
            let that = this

            console.log(this.keyword.length)

            if(this.keyword.length > 0) {
                this.filteredMahasiswa = this.daftarMahasiswa.filter((mahasiswa) => {
                    return mahasiswa.nama.toLowerCase().indexOf(that.keyword) > -1 || mahasiswa.id.indexOf(that.keyword) > -1
                })

                if(this.filteredMahasiswa.length == 0) {
                    $.ajax({
                        url: '{{ route('kasublab.cari.mahasiswa') }}',
                        type: 'POST',
                        data: 'keyword=' + that.keyword + '&status=' + that.status,
                        success: (response) => {
                            that.filteredMahasiswa = response
                        }
                    })
                }
            }
            else {
                this.filteredMahasiswa = []
            }
        }
    }
})

// handle scroll
$('.main-wrapper').scroll(function (e) {
    let scroll = e.target.scrollTop
})
</script>
@endpush