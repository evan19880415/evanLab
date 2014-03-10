@extends('layouts.master')
@section('content')

<h1>High Speed Train Bird Ticket</h1>
<br>				
<table class='table'>
	<tr class='bg-primary'>
		<td>車次</td>
		<td>優惠</td>
		<td>出發時間</td>
		<td>到達時間</td>
		<td>訂票</td>
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
			<td><a class="btn btn-xs btn-success ticketOrder" href="#" data-id="{{ $content[$i]['value'] }}">訂票</a></td>
		</tr>
	@endfor
</table>
{{ Form::open(array('url' => 'toys/hsp-train-finished','method' => 'post')) }}
<div id="modal-ticketOrderInfo" class="modal">
	    <div class="modal-dialog">
	      <div class="modal-content">
	        <div class="modal-header">
	            <a href="#" data-dismiss="modal" aria-hidden="true" class="close">×</a>
	            <h3>訂票資訊</h3>
	        </div>
	        <div class="modal-body">
	        	<div id="ticketInfo"></div>
				<div class="form-group numberpicker">
					<label>身分證字號</label>
					<input class="form-control" name="idInputRadio:idNumber" type="text" id="idInputRadio:idNumber">
				</div>
				<div class="form-group numberpicker">
					<label>行動電話</label>
					<input class="form-control" name="eaiPhoneCon:phoneInputRadio:mobilePhone" type="text" id="eaiPhoneCon:phoneInputRadio:mobilePhone">
				</div>
				<div class="form-group">
					{{ Form::hidden('formUrl', Input::old('formUrl'), array('id' => 'formUrl','class' => 'form-control')) }}
				</div>
				<div class="form-group">
					{{ Form::hidden('idInputRadio', Input::old('idInputRadio'), array('id' => 'idInputRadio','class' => 'form-control')) }}
				</div>
	        </div>
	        <div class="modal-footer">
	          {{ Form::submit('OK', array('class' => 'btn btn-default')) }}
	          <a href="#" id="btnOrderInfoNO" data-dismiss="modal" aria-hidden="true" class="btn btn-primary">Cancel</a>
	        </div>
	      </div>
	    </div>
	</div>
<script>
	$(function(){
		$('.ticketOrder').on('click', function(e) {
			e.preventDefault();
			$('#ticketInfo').empty();
		    var path = "{{ URL::to('toys/hsp-train-order-query') }}";
		    var id = $(this).data('id');
		    $.post(path, {
		        'TrainQueryDataViewPanel:TrainGroup' : id,
		        'formUrl' : "{{ $formUrl }}"
		    },function(data){
		    	$('#ticketInfo').append(
		    			"<table class='table'>"+
		    				"<tr class='bg-primary'>"+
		    					"<td>日期</td>"+
		    					"<td>車次</td>"+
		    					"<td>車程</td>"+
		    					"<td>出發</td>"+
		    					"<td>到達</td>"+
		    					"<td>全票</td>"+
		    				"</tr>"+	
		    				"<tr>"+
		    					"<td>"+data[0]['date']+"</td>"+
		    					"<td>"+data[0]['trainNumber']+"</td>"+
		    					"<td>"+data[0]['startLocation']+" -> "+data[0]['destination']+"</td>"+
		    					"<td>"+data[0]['startTime']+"</td>"+
		    					"<td>"+data[0]['destinationTime']+"</td>"+
		    					"<td>"+data[0]['status']+"</td>"+
		    				"</tr>"+
		    				"<tr><td colspan='5'>總票價</td>"+
		    				"<td colspan='1'>"+data[0]['price']+"</td></tr>"+
		    			"</table>"		
		    	);
		    	$('#formUrl').val(data[1]);
		    	$('#idInputRadio').val(id);
		    	$('#modal-ticketOrderInfo').modal('show');
		    });
		});
	});
</script>			
<style type="text/css">
		img {
		    width: 100% !important;
		    max-width: 74px;
		    height: auto !important;
		}
</style>

@stop