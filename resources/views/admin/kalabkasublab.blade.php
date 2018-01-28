@extends('layouts.global')

@section('activity', 'Kalab & Kasublab - Admin')

@section('title', 'Kalab & Kasublab')

@section('content')
    <div class="card">
        <div class="card-header bordered">
            <div class="header-block">
                <h3 class="title">Daftar Kalab & Kasublab</h3>
            </div>
            <div class="header-block pull-right">
                <button data-target="#tambah" class="btn btn-primary btn-sm rounded pull-right" data-toggle="modal">
                    Tambah Kalab / Kasublab
                </button>
                <div class="modal fade" id="tambah" tabindex="-1" role="dialog"
                     aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="card">
                            <div class="card-header bordered">
                                <div class="header-block">
                                    <h3 class="title">Tambah Kalab / Kasublab</h3>
                                </div>
                                <div class="header-block pull-right">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                            <div class="card-block">
                                <form action="{{ route('admin.kalabkasublab.tambah') }}" method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('put') }}
                                    <div class="form-group">
                                        <label class="control-label">NIP/NIDN</label>
                                        <input type="number" class="form-control" name="id" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Nama</label>
                                        <input type="text" class="form-control" name="nama" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Jurusan</label>
                                        <select class="form-control" id="jurusan" required>
                                            <option value=""></option>
                                            @foreach($jurusans as $jurusan)
                                                <option value="{{ $jurusan->id }}">{{ $jurusan->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Prodi</label>
                                        <select class="form-control" id="prodi" name="prodi" required>
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Hak akses</label>
                                        <select class="form-control" name="role" required>
                                            <option value=""></option>
                                            <option value="{{ \App\Support\Role::KALAB }}">{{ \App\Support\Role::KALAB }}</option>
                                            <option value="{{ \App\Support\Role::KASUBLAB }}">{{ \App\Support\Role::KASUBLAB }}</option>
                                        </select>
                                    </div>
                                    <input type="submit" class="btn btn-success" value="Simpan">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-block">
            <table class="table table-responsive" id="kalabkasublab">
                <thead>
                <tr>
                    <td>No</td>
                    <td>NIP/NIDN</td>
                    <td>Nama</td>
                    <td>Hak akses</td>
                    <td>Jurusan</td>
                    <td>Aksi</td>
                </tr>
                </thead>
                <tbody>
                @foreach($dosens as $dosen)
                    <tr>
                        <td>{{ ++$no }}</td>
                        <td>{{ $dosen->id }}</td>
                        <td>{{ $dosen->nama}}</td>
                        <td>{{ $dosen->role }}</td>
                        <td>{{ $dosen->getJurusan()->nama }}</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-warning btn-sm text-light">Reset password</button>
                                <button class="btn btn-danger btn-sm text-light" onclick="konfirmasi('{{ $dosen->nama }}', {{ $dosen->id }})">Hapus</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <form action="{{ route('admin.kalabkasublab.hapus') }}" method="post" id="hapus" style="display: none">
        {{ csrf_field() }}
        {{ method_field('delete') }}
        <input type="hidden" name="id" id="dosen_id">
    </form>
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
        $('#jurusan').on('change', function (e) {
            console.log(e);
            var jurusan_id = e.target.value;

            $.get('{{ url('admin/etc/getprodi') }}/' + jurusan_id, function (data) {
                console.log(data);
                $('#prodi').empty();
                $.each(data, function (index, subCatObj) {
                    $('#prodi').append('<option value="' + subCatObj.id + '">' + subCatObj.nama + '</option>');
                });
            });
        });
        $('#kalabkasublab').DataTable({
            responsive: true,
            "lengthMenu": [[5, 10, 20, 40, 80, 100, -1], [5, 10, 20, 40, 80, 100, "Semua data"]],
            "columnDefs": [
                {"orderable": false, "targets": 5}
            ]
        });

        function konfirmasi(nama, id) {
            swal({
                title: "Anda yakin ingin menghapus '" + nama +"'?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $('#dosen_id').val(id)
                    $('#hapus').submit()
                }
            })
        }
    </script>
@endpush