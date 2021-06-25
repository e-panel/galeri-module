@extends('core::page.media')
@section('inner-title', "Edit $title - ")
@section('mAlbum', 'opened')

@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.5.1/flatpickr.min.css">
@endsection

@section('js')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.5.1/flatpickr.min.js"></script>
	<script>
		$().ready(function () {
			$('.flatpickr').flatpickr({
    			dateFormat: "Y-m-d",
			});
		});
	</script>
@endsection

@section('content')
	<section class="box-typical">

		{!! Form::model($edit, ['route' => ["$prefix.update", $edit->uuid], 'autocomplete' => 'off', 'method' => 'PUT']) !!}

	    	@include('core::layouts.components.top', [
                'judul' => 'Edit ' . str_replace('Modul ', '', $title),
                'subjudul' => 'Silahkan lakukan perubahan sesuai dengan kebutuhan Anda.',
                'kembali' => route("$prefix.index")
            ])
	    
	        <div class="card">
                @include("$view.form")
                @include('core::layouts.components.submit')
            </div>

	    {!! Form::close() !!}

	</section>
@endsection