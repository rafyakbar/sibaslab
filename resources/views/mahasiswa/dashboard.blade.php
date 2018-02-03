@extends('layouts.global')



@section('content')
    <div class="row">
        <div class="col-md-12">
            @if(Auth::guard('mhs')->user()->konfirmasi)
                <div class="card card-info">
                    <div class="card-block">
                        <h4 class=""><em class="fa fa-check-circle-o"></em> Pengajuan surat telah disetujui</h4>
                        <br>
                        <form role="form" action="{{ route('mahasiswa.unduh') }}" method="get" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <button class="btn btn-primary">DOWNLOAD SURAT</button>
                        </form>
                    </div>
                </div>
            @elseif($jumlahMenolak>0)
                <div class="card card-danger">
                    <div class="card-header">
                        <div class="card-block">
                            <h4 class="text-light"><em class="fa  fa-warning"></em> Pengajuan surat tidak disetujui</h4>
                        </div>
                    </div>
                    <div class="card-block">
                        <p>Lakukan awf kuwe akik okeh iurjr ojwkeu hidy le,rj akeuehk. jaf alfirf iurkj</p>
                    </div>
                </div>
            @else
                <div class="alert alert-warning">
                    <div class="card-block">
                        <h4 class="text-light"><em class="fa  fa-warning"></em> Pengajuan surat belum
                            disetujui</h4>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-default" data-exclude="xs,sm">
                <div class="card-header bordered">
                    <div class="header-block">
                        <h3 class="title"> Kasublab/Kalab yang menyetujui </h3>
                    </div>
                    <div class="header-block pull-right">
                        <h3 class="title">{{$jumlahMenyetujui}}</h3>
                    </div>
                </div>
                <div class="card-block">
                    <div class="tasks-block">
                        <ul class="item-list striped">

                            @foreach($kasublabMenyetujui as $kasublab)
                                <li class="item">
                                    <div class="item-row">
                                        <div class="item-col item-col-title no-overflow">
                                            <div class="" style="width: 5%">
                                                <p class="l l1"></p>
                                            </div>
                                            <div>
                                                <a class="date"
                                                   style="font-size: small">{{$kasublab->pivot->created_at->diffForHumans()}} </a>
                                                <h4 class="item-title no-wrap">{{$kasublab->nama}} </h4>
                                                <p class="date">{{$kasublab->id}} </p>
                                            </div>
                                            <p class="text-info pull-right" style="font-size: small">{{$kasublab->role}}</p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach()
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card card-default" data-exclude="xs,sm">
                <div class="card-header bordered">
                    <div class="header-block">
                        <h3 class="title"> Kasublab/Kalab yang tidak menyetujui </h3>
                    </div>
                    <div class="header-block pull-right">
                        <h3 class="title">{{$jumlahMenolak}}</h3>
                    </div>
                </div>
                <div class="card-block">
                    <div class="tasks-block">
                        <ul class="item-list striped">
                            @foreach($kasublabMenolak as $kasublab)
                                <li class="item">
                                    <div class="item-row">
                                        <div class="item-col item-col-title no-overflow">
                                            <div>
                                                <a class="date"
                                                   style="font-size: small">{{$kasublab->pivot->created_at->diffForHumans()}} </a>
                                                <h4 class="item-title no-wrap">{{$kasublab->nama}} </h4>
                                                <p class="date">{{$kasublab->id}} </p>
                                            </div>
                                            <div class="pull-right" style="width: auto">
                                                <p class="text-info" style="font-size: small">{{$kasublab->role}}</p>
                                                <button class="btn btn-primary btn-sm rounded"
                                                        onclick="tampilCatatan('{{$kasublab->pivot->catatan}}')">Lihat
                                                    Catatan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach()
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card sameheight-item card-default" data-exclude="xs,sm">
                <div class="card-header bordered">
                    <div class="header-block">
                        <h3 class="title"> Kasublab/Kalab yang belum menyetujui </h3>
                    </div>
                    <div class="header-block pull-right">
                        <h3 class="title">{{$jumlahBelum}}</h3>
                    </div>
                </div>
                <div class="card-block">
                    <div class="tasks-block">
                        <ul class="item-list striped">
                            @foreach($kasublabBelumMenyetujui as $kasublab)
                                <li class="item">
                                    <div class="item-row">
                                        <div class="item-col item-col-title no-overflow">
                                            <div>
                                                <h4 class="item-title no-wrap">{{$kasublab->nama}} </h4>
                                                <p class="date">{{$kasublab->id}} </p>
                                            </div>
                                            <p class="text-info pull-right" style="font-size: small">{{$kasublab->role}}</p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach()
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@push('js')
    <script>
        function tampilCatatan(catatan) {
            swal({
                icon: 'info',
                title: catatan
            });
        }
        @if(Session::has('message'))
        swal({
            icon: "success",
            title: "{{Session::get('message')}}"
        });
        @endif
    </script>
@endpush