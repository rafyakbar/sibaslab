@extends('layouts.global')

@section('activity', 'Lain-lain - Admin')

@section('title', 'Lain-lain')

@section('content')
    @if($errors->any())
        <div class="alert alert-warning">
            <ul>
                @foreach($errors->all() as $error)
                    <li >{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="header-block">
                        <h3 class="title">Atur link</h3>
                    </div>
                </div>
                <div class="card-block">
                    <form action="{{ route('admin.link.update') }}" method="post">
                        {{ csrf_field() }}
                        <div class="input-group">
                            <input type="text" name="link" class="form-control"
                                   placeholder="Awali link dengan https:// atau http://" value="{{ Auth::user()->getFakultas()->link }}">
                            <input type="submit" class="btn btn-primary text-light" value="Atur">

                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="header-block">
                        <h3 class="title">Sinkron data mahasiswa dengan PPTI (minimal per semester)</h3>
                    </div>
                </div>
                <div class="card-block">
                    <form action="" method="post">
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-primary" value="Sinkron sekarang">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="header-block">
                <h3 class="title">Atur apakah Kalab bisa menambahkan Kasublab atau tidak</h3>
            </div>
        </div>
        <div class="card-block">
            <table class="table table-responsive">
                <thead>
                <tr>
                    <td>No</td>
                    <td>NIP/NIDN</td>
                    <td>Nama</td>
                    <td>Hak akses</td>
                    <td>Jurusan</td>
                    <td>Status</td>
                </tr>
                </thead>
                <tbody>
                @foreach($kalabs as $kalab)
                    <tr>
                        <td>{{ ++$no }}</td>
                        <td>{{ $kalab->id }}</td>
                        <td>{{ $kalab->nama}}</td>
                        <td>{{ $kalab->role }}</td>
                        <td>{{ $kalab->getJurusan()->nama }}</td>
                        <td>
                            @if($kalab->tambah_kasublab)
                                <button class="btn btn-primary btn-sm text-light" title="Ubah supaya tidak bisa menambahkan">Bisa menambahkan</button>
                            @else
                                <button class="btn btn-danger btn-sm text-light" title="Ubah supaya bisa menambahkan">Tidak bisa menambahkan</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('js')
    <script>
        @if($errors->any())
            swal({
                icon: "error",
                text: "{!! implode('\n', $errors->all()) !!}",
                html: true
            });
        @endif
        @if(session()->has('message'))
            swal({
                icon: "success",
                title: "{{ session()->get('message') }}"
            });
        @endif
    </script>
@endpush