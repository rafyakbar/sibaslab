@extends('layouts.global')



@section('content')
    <div class="row">
        <div class="col-md-12">
            @if(Auth::guard('mhs')->user()->konfirmasi)
                <div class="card card-info">
                    <div class="card-block">
                        <h4 class=""><em class="fa fa-check-circle-o"></em> Pengajuan surat telah disetujui</h4>
                        <br>
                        <button class="btn btn-primary">DOWNLOAD SURAT</button>
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
                        <h3 class="title"> Kasublab yang menyetujui </h3>
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
                                            <div>
                                                <a class="date"
                                                   style="font-size: small">{{$kasublab->pivot->created_at->diffForHumans()}} </a>
                                                <h4 class="item-title no-wrap">{{$kasublab->nama}} </h4>
                                            </div>
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
                        <h3 class="title"> Kasublab yang tidak menyetujui </h3>
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
                                            </div>
                                            <button class="btn btn-primary btn-sm rounded pull-right"
                                                    onclick="tampilCatatan('{{$kasublab->pivot->catatan}}')">Lihat
                                                Catatan
                                            </button>
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
                        <h3 class="title"> Kasublab yang belum menyetujui </h3>
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
    </script>
@endpush