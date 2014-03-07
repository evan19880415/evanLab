@extends('layouts.master')
@section('content')
	<div id="train" class="jumbotron">
		<h1>High Speed Train Bird Ticket</h1>
		<br>
		<div id="trainContainer" align='center'>
			@for($i=0;$i<=count($trainLinkInfo)-1;$i++)
				<h3><a class="trainLink" href="#" data-id="{{$trainLinkInfo[$i]['link']}}">{{$trainLinkInfo[$i]['title']}}</a></h3><br>
			@endfor						
		</div>
	</div>
<script>
	$('.trainLink').click(function(){
		var link = $(this).data('id').split("/");;
		var path = "{{ URL::to('hspTrainDetail') }}";
		window.location.href = path+"/"+link[6];
	});
</script>

@stop