<div class="box-typical-body padding-panel">
	<div class="form-group row {{ $errors->first('judul', 'form-group-error') }}">
		<label class="col-sm-2 form-control-label">Nama Kegiatan <span class="color-red">*</span></label>
		<div class="col-sm-10">
			{!! Form::text('judul', null, ['class' => 'form-control']) !!}
			{!! $errors->first('judul', '<span class="text-muted"><small>:message</small></span>') !!}
		</div>
	</div>
	<div class="form-group row {{ $errors->first('tgl_terbit', 'form-group-error') }}">
		<label class="col-sm-2 form-control-label">Tanggal Kegiatan <span class="color-red">*</span></label>
		<div class="col-sm-10">
			{!! Form::text('tgl_terbit', isset($edit) ? date('Y-m-d', strtotime($edit->created_at)) : null, ['class' => 'form-control flatpickr']) !!}
			{!! $errors->first('tgl_terbit', '<span class="text-muted"><small>:message</small></span>') !!}
		</div>
	</div>
</div>