@extends('core::page.media')
@section('inner-title', "$album->judul - ")
@section('mAlbum', 'opened')

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.0.1/min/dropzone.min.css" rel="stylesheet">
    <style type="text/css">
        .dropzone {
            margin-top: 10px;
            border: 10px solid rgb(246, 248, 250);
        }
        .gambar {
            width: 220px
        }
    </style>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.js"></script>
    <script type="text/javascript">
        Dropzone.options.imageUpload = {
            maxFilesize : 20,
            acceptedFiles: ".jpeg,.jpg,.png,.gif"
        };
    </script>
@endsection

@section('content')
    <section class="box-typical">

        @include('core::layouts.components.top', [
            'judul' => 'Drag File Baru',
            'subjudul' => "List di Album <b>$album->judul</b>",
            'kembali' => route("$prefix.galeri.index", $album->uuid)
        ])
    
        {!! Form::open(['route' => ["$prefix.galeri.store", $album->uuid], 'autocomplete' => 'off', 'class' => 'dropzone', 'files' => true, 'id' => 'image-upload']) !!}
            
            <div class="box-typical-body padding-panel">
                <center>
                    <div class="alert alert-warning text-left">
                        <b>PERHATIAN!</b><br/>
                        Jika upload gagal, kemungkinan file yang diupload terlalu besar. Usahakan besar file dibawah 5 MB / filenya.<br/>
                        Disarankan untuk melakukan kompresi ukuran file foto melalui <a href="https://tinypng.com" target="_blank">https://tinypng.com</a> sebelum melakukan upload.
                    </div>
                    <img src="https://cdn.enterwind.com/template/epanel/img/placeholder-image.svg" class="gambar">
                    <h6 class="text-muted">Drag'n'Drop Gambar yang ingin Anda unggah kesini.</h6>
                </center>
            </div>

        {!! Form::close() !!}

    </section>
@endsection