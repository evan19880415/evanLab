@extends('layouts.master')
@section('content')
		<h1>High Speed Train Bird Ticket</h1>
		<br>
		<div class="jumbotron" align="center">
			<h2>高鐵訂票功能及查詢</h2>
			<a class="btn btn-lg btn-primary" href="{{ URL::to('toys/hsp-train-order') }}">訂票功能</a>
			<a class="btn btn-lg btn-success" href="{{ URL::to('toys/hsp-train-check') }}">訂票查詢</a>	
		</div>
		<div id="trainContainer" class="jumbotron">
			<h2>早鳥票資訊</h2>
			@for($i=0;$i<=count($trainLinkInfo)-1;$i++)
				<h3><a class="trainLink" href="#" data-id="{{$trainLinkInfo[$i]['link']}}">{{$trainLinkInfo[$i]['title']}}</a></h3><br>
			@endfor						
		</div>
<script>
	$('.trainLink').click(function(){
		var link = $(this).data('id').split("/");;
		var path = "{{ URL::to('hspTrainDetail') }}";
		window.location.href = path+"/"+link[6];
	});
</script>

@stop