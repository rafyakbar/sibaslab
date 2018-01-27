@extends('layouts.blank')

@section('activity', 'ajukan pemohonan')

@section('content')
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
            <select class="form-control" name="jurusan">
                <option>Option one</option>
                <option>Option two</option>
                <option>Option three</option>
                <option>Option four</option>
            </select>
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Prodi</label>
            <select class="form-control" name="prodi">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option>Option four</option>
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
    </script>
@endpush