@extends('layouts.master')
@section('content')
@if (Session::has('message'))
	<div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<h1>訂票資訊</h1>
<br>
<table class='table'>
	<tr>
		<td class="bg-primary">訂位代號</td>
		<td>{{$ticketKey}}</td>
	</tr>
	<tr>
		<td class="bg-primary">交易狀態</td>
		<td>{{$ticketStatus}}</td>
	</tr>
	<tr>
		<td class="bg-primary">座位</td>
		<td>{{ $content['seat'] }}</td>
	</tr>
</table>	
<table class='table'>
	<tr class="bg-primary">
		<td>日期</td>
		<td>車次</td>
		<td>車程</td>
		<td>出發</td>
		<td>到達</td>
		<td>小計</td>
	</tr>
	<tr>
		<td>{{ $content['date'] }}</td>
		<td>{{ $content['trainNumber'] }}</td>
		<td>{{ $content['startLocation'].' -> '.$content['destination'] }}</td>	
		<td>{{ $content['startTime'] }}</td>
		<td>{{ $content['destinationTime'] }}</td>
		<td>{{ $content['price'] }}</td>
	</tr>
</table>
<a class="btn btn-primary" href="{{ URL::to('toys/hsp-train-info') }}">返回首頁</a>
@stop