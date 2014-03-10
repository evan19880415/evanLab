@extends('layouts.master')
@section('content')

<h1>查詢訂票</h1>
<br>
{{ Form::open(array('url' => 'toys/hsp-train-check-ticket','method' => 'post')) }}
	<div class="form-group">
		<label>身分證字號</label>
		<input class="form-control" name="idInputRadio:rocId" type="text" id="idInputRadio:rocId">
	</div>

	<div class="form-group">
		<label>訂票代號</label>
		<input class="form-control" name="orderId" type="text" id="orderId">
	</div>
	{{ Form::submit('查詢', array('class' => 'btn btn-primary')) }}
{{ Form::close() }}

@stop