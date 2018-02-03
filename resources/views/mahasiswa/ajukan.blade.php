@extends('layouts.blank')

@section('activity', 'ajukan pemohonan')

@section('titleinfo', 'Ajukan Surat')

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
    <form role="form" action="{{route('mahasiswa.ajukan.proses')}}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        {{method_field('post')}}
        <div class="form-group">
            <label for="exampleInputEmail1">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama" placeholder="" name="nama"> </div>
        <div class="form-group">
            <label for="exampleInputEmail1">NIM</label>
            <input type="number" class="form-control" id="nim" placeholder="" name="nim"> </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Jurusan</label>
            <select class="form-control" name="jurusan" id="jurusan" required>
                @foreach($semuaJurusan as $jurusan)
                    <option value="{{$jurusan->id}}" >{{$jurusan->nama}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Prodi</label>
            <select class="form-control" name="prodi" id="prodi">
                <option value=""></option>
            </select>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Upload Berkas</label><br>
            <div class="input-group">
                <input type="button" class="input-group-addon text-dark" onclick="upload()" value="Pilih Berkas">
                <label class="form-control" id="namaFile" for="exampleInputPassword1"></label><br>
            </div>
        </div>
        <input id="inputFile" type="file"  name="berkas" style="display: none" class="dz-message-block">
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
@endsection

@push('js')
    <script>
        function upload() {
            document.getElementById("inputFile").click();
        }
        $(document).ready(function(){
            $('input[type="file"]').change(function(e){
                var fileName = e.target.files[0].name;
                document.getElementById("namaFile").innerHTML = fileName;
            });
        });

        $('#jurusan').on('change', function(e){
            console.log(e);
            var jurusan_id = e.target.value;

            $.get('{{ url('mahasiswa/etc/getprodi') }}/' + jurusan_id, function (data) {
                console.log(data);
                $('#prodi').empty();
                $.each(data, function (index, subCatObj) {
                    $('#prodi').append('<option value="' + subCatObj.id + '">' + subCatObj.nama + '</option>');
                });
            });
        });

        @if(Session::has('message'))
        swal({
            icon: "success",
            title: "{{Session::get('message')}}",
            buttons: true
        }).then((berhasil) => {
            if(berhasil){
                window.location.href = '{{ route('mahasiswa.dashboard') }}';
            }
        });
        @endif
    </script>
@endpush