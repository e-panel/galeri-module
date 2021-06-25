@extends('core::page.media')
@section('inner-title', "$album->judul - ")
@section('mAlbum', 'opened')

@section('js')  
    <script src="https://cdn.enterwind.com/template/epanel/js/lib/salvattore/salvattore.min.js"></script>

    <script type="text/javascript" src="https://cdn.enterwind.com/template/epanel/js/lib/match-height/jquery.matchHeight.min.js"></script>
    <script>
        $(function() {
            $('.gallery-item').matchHeight({
                target: $('.gallery-item .gallery-picture')
            });
        });
    </script>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.enterwind.com/template/epanel/css/separate/pages/gallery.min.css">
    @include('core::layouts.partials.datatables')
@endsection

@section('content')
    @if(!$data->count())

        @include('core::layouts.components.kosong', [
            'icon' => 'font-icon font-icon-picture-double',
            'judul' => $album->judul,
            'subjudul' => "Sepertinya Anda belum memiliki Galeri untuk Album $album->judul.", 
            'kembali' => route("$prefix.index"),
            'tambah' => route("$prefix.galeri.create", $album->uuid)
        ])

    @else
        
        @include('core::layouts.components.top', [
            'judul' => 'Daftar Galeri',
            'subjudul' => 'Album: <b>' . $album->judul . '</b>',
            'kembali' => route("$prefix.index"), 
            'tambah' => route("$prefix.galeri.create", $album->uuid), 
            'hapus' => true
        ])

        <div class="card">
            <div class="card-block">

                <div class="box-typical-body">
                    <div class="gallery-grid">
                        @foreach($data as $i => $temp)
                        <div class="gallery-col">
                            <article class="gallery-item" style="height: 158px;">
                                <img class="gallery-picture" src="{{ viewImg($temp->foto, 'm') }}" alt="" height="158">
                                <div class="gallery-hover-layout">
                                    <div class="gallery-hover-layout-in">
                                        {!! Form::open(['method' => 'DELETE', 'route' => ["$prefix.galeri.destroy", $album->uuid, $temp->uuid], 'onsubmit' => 'return confirm("Apa kamu yakin?")']) !!}
                                        <p>by <b>{{ optional($temp->operator)->nama }}</b></p>
                                        <div class="btn-group">
                                            <button type="submit" class="btn">
                                                <i class="font-icon font-icon-trash"></i>
                                            </button>
                                        </div>
                                        <p>{{ $temp->created_at->diffForHumans() }}</p>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </article>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection