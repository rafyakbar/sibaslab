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
    <div class="row">
        <card-mhs v-for="mahasiswa in daftarMahasiswa" :key="mahasiswa.id" :id="mahasiswa.id" :nama="mahasiswa.nama" :nim="mahasiswa.id" :belum-menanggapi="mahasiswa.belum_menanggapi" :menyetujui="mahasiswa.menyetujui" :menolak="mahasiswa.menolak"></card-mhs>
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
        status: {{ request()->has('status') ? request()->get('status') : 0 }}
    },
    methods: {
        removeData(id) {
            this.daftarMahasiswa = this.daftarMahasiswa.filter((item) => {
                return item.id != id
            })
        }
    }
})

// handle scroll
$('.main-wrapper').scroll(function (e) {
    let scroll = e.target.scrollTop
})
</script>
@endpush