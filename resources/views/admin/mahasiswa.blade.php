@extends('layouts.global')

@section('activity', 'Mahasiswa - Admin')

@section('title', 'Mahasiswa')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="header-block">
                <h3 class="title">Daftar Mahasiswa</h3>
            </div>
            <div class="header-block pull-right">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Jurusan {{ $jurusan }}
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('admin.mahasiswa', ['jurusan' => 'semua']) }}">Semua</a>
                        @foreach($jurusans as $item)
                            <a class="dropdown-item" href="{{ route('admin.mahasiswa', ['jurusan' => $item->nama]) }}">Jurusan {{ $item->nama }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="card-block">
            <table class="table" id="mahasiswa">
                <thead>
                <tr>
                    <td>No</td>
                    <td>NIM</td>
                    <td>Nama</td>
                    <td>Jurusan</td>
                    <td>Aksi</td>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <form action="{{ route('admin.mahasiswa.reset') }}" method="post" id="reset" style="display: none">
        {{ csrf_field() }}
        {{ method_field('patch') }}
        <input type="hidden" name="id" id="reset_mahasiswa">
    </form>
@endsection

@push('js')
    <script type="text/javascript">
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
        $("#mahasiswa").DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            language: {
                searchPlaceholder: "Ketik NIM atau nama..."
            },
            "lengthMenu": [[5, 10, 20, 40, 80, 100, -1], [5, 10, 20, 40, 80, 100, "Semua data"]],
            ajax: {
                url: '{{ route("admin.etc.getmahasiswa", ["jurusan" => $jurusan]) }}'
            },
            columns: [
                {
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {data: 'id', name: 'id'},
                {data: 'nama', name: 'nama'},
                {data: 'jurusan', name: 'jurusan', searchable: false},
                {data: 'aksi', name:'aksi', searchable: false, orderable: false}
            ]
        });
        function reset(id) {
            swal({
                title: "Anda yakin ingin mereset?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willReset) => {
                if (willReset) {
                    $('#reset_mahasiswa').val(id)
                    $('#reset').submit()
                }
            })
        }
    </script>
@endpush