@extends('layouts.global')

@section('activity', 'ajukan pemohonan')

@section('content')
    <form role="form">
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
                <option>Option one</option>
                <option>Option two</option>
                <option>Option three</option>
                <option>Option four</option>
            </select>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password"> </div>
        <div class="form-group">
            <button type="button" class="btn btn-info">Submit</button>
        </div>
        <input id="inputfile" type="file" style="display: none" class="dz-message-block">
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
@endsection