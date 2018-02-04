<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Bebas Laboratorium</title>
    <link rel="stylesheet" href="{{ asset('modular/css/vendor.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}"/>
    <style>
    </style>
</head>
<body>
    <div id="material">
        <div></div>
        <div></div>
        <div></div>
    </div>
    <div class="jumbotron">
        <h1>Surat Bebas Laboratorium</h1>

        <ul class="list-group">
            <li class="list-group-item">
                <a href="{{ route('mahasiswa.login') }}">
                    <h3>Unduh Surat</h3>
                    <p>Melakukan pengecekan dan pengunduhan surat setelah pengajuan</p>
                </a>
            </li>
            <li class="list-group-item">
                <a href="{{ route('mahasiswa.ajukan') }}">
                    <h3>Pengajuan Surat</h3>
                    <p>Melakukan pengajuan surat bebas laboratorium</p>
                </a>
            </li>
            <li class="list-group-item">
                <a href="{{ route('user.login') }}">
                    <h3>Masuk Kalab & Kasublab</h3>
                    <p>Mengelola pengajuan surat oleh kalab dan kasublab</p>
                </a>
            </li>
        </ul>
    </div>
    <script src="{{ asset('modular/js/vendor.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>