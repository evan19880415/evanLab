@extends('layouts.master')
@section('content')

<h1>High Speed Train Bird Ticket</h1>
<br>				
<table class='table'>
	<tr>
		<td>車次</td>
		<td>優惠</td>
		<td>出發時間</td>
		<td>到達時間</td>
	</tr>
	@for($i=0;$i<=count($content)-1;$i++)
		<tr>
			<td>{{ $content[$i]['number'] }}</td>
			<td>
				@if($content[$i]['discount']<>"")
					<img src="https://irs.thsrc.com.tw/{{ $content[$i]['discount'] }}"/></td>
				@endif
			</td>
			<td>{{ $content[$i]['startTime'] }}</td>	
			<td>{{ $content[$i]['destinationTime'] }}</td>	
		</tr>
	@endfor
</table>
			
<style type="text/css">
		img {
		    width: 100% !important;
		    max-width: 74px;
		    height: auto !important;
		}
</style>

@stop