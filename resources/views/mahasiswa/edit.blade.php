@extends('layouts.global')

@section('activity', 'Edit')

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
    <div class="row sameheight-container">
        <div class="col-lg-6">
            <div class="card card-block">
                <form role="form" action="{{route('mahasiswa.password.perbarui')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="form-group">
                        <label for="exampleInputEmail1">Perbarui Password</label>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Password</label>
                        <input type="text" class="form-control" id="nama" placeholder="" name="password"> </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Password Baru</label>
                        <input type="number" class="form-control" id="nim" placeholder="" name="newPassword"> </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Konfirmasi Password Baru</label>
                        <input type="number" class="form-control" id="nim" placeholder="" name="confirmNewPassword"> </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-block">
                <form>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Perbarui Berkas</label>
                    </div>
                    <div class="form-group">
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
            </div>
        </div>
    </div>
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