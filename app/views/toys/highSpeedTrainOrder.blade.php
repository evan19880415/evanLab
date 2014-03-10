@extends('layouts.master')
@section('content')

<h1>訂票資訊</h1>
<br>
{{ Form::open(array('url' => 'toys/hsp-train-query','method' => 'post')) }}
	<div class="form-group">
		{{ Form::label('selectStartStation', '起程站') }}
		{{ Form::select('selectStartStation', array('0' => '台北', '1' => '板橋', '2' => '桃園', '3' => '新竹', '4' => '台中', '5' => '嘉義', '6' => '台南', '7' => '左營'), Input::old('selectStartStation'), array('class' => 'form-control')) }}
	</div>

	<div class="form-group">
		{{ Form::label('selectDestinationStation', '到達站') }}
		{{ Form::select('selectDestinationStation', array('0' => '台北', '1' => '板橋', '2' => '桃園', '3' => '新竹', '4' => '台中', '5' => '嘉義', '6' => '台南', '7' => '左營'), Input::old('selectDestinationStation'), array('class' => 'form-control')) }}
	</div>

	<div class="form-group" style="display: none;">
		<label>車廂種類</label>
		{{ Form::select('trainCon:trainRadioGroup', array('0' => '標準車廂', '1' => '商務車廂'), Input::old('trainCon:trainRadioGroup'), array('class' => 'form-control')) }}
	</div>

	<div class="form-group">
		<label>訂位方式</label>
		{{ Form::select('bookingMethod', array('radio18' => '依時間搜尋合適車次', 'radio20' => '直接輸入車次號碼'), Input::old('bookingMethod'), array('id'=>'bookingMethod','class' => 'form-control')) }}
	</div>

	<div class="form-group">
		<label>去程日期</label>
		<input class="form-control" name="toTimeInputField" type="text" id="toTimeInputField">
	</div>
	<div class="form-group timepicker">	
		<label>時間</label>
		<select name="toTimeTable" class="form-control" id="toTimeTable">
			<option selected="selected" value="">請選擇...</option>
			<option value="1201A">00:00</option>
			<option value="1230A">00:30</option>
			<option value="600A">06:00</option>
			<option value="630A">06:30</option>
			<option value="700A">07:00</option>
			<option value="730A">07:30</option>
			<option value="800A">08:00</option>
			<option value="830A">08:30</option>
			<option value="900A">09:00</option>
			<option value="930A">09:30</option>
			<option value="1000A">10:00</option>
			<option value="1030A">10:30</option>
			<option value="1100A">11:00</option>
			<option value="1130A">11:30</option>
			<option value="1200N">12:00</option>
			<option value="1230P">12:30</option>
			<option value="100P">13:00</option>
			<option value="130P">13:30</option>
			<option value="200P">14:00</option>
			<option value="230P">14:30</option>
			<option value="300P">15:00</option>
			<option value="330P">15:30</option>
			<option value="400P">16:00</option>
			<option value="430P">16:30</option>
			<option value="500P">17:00</option>
			<option value="530P">17:30</option>
			<option value="600P">18:00</option>
			<option value="630P">18:30</option>
			<option value="700P">19:00</option>
			<option value="730P">19:30</option>
			<option value="800P">20:00</option>
			<option value="830P">20:30</option>
			<option value="900P">21:00</option>
			<option value="930P">21:30</option>
			<option value="1000P">22:00</option>
			<option value="1030P">22:30</option>
			<option value="1100P">23:00</option>
			<option value="1130P">23:30</option>
		</select>
	</div>
	<div class="form-group numberpicker">
		<label>車次號碼</label>
		<input class="form-control" name="toTrainIDInputField" type="text" id="toTrainIDInputField">
	</div>
	<div id="backInfo" class="jumbotron">	
		<div class="form-group">
			<label>回程日期</label>
			<input class="form-control" name="backTimeInputField" type="text" id="backTimeInputField">
		</div>
		<div class="form-group timepicker">	
			<label>時間</label>
			<select name="backTimeTable" class="form-control" id="backTimeTable">
				<option selected="selected" value="">請選擇...</option>
				<option value="1201A">00:00</option>
				<option value="1230A">00:30</option>
				<option value="600A">06:00</option>
				<option value="630A">06:30</option>
				<option value="700A">07:00</option>
				<option value="730A">07:30</option>
				<option value="800A">08:00</option>
				<option value="830A">08:30</option>
				<option value="900A">09:00</option>
				<option value="930A">09:30</option>
				<option value="1000A">10:00</option>
				<option value="1030A">10:30</option>
				<option value="1100A">11:00</option>
				<option value="1130A">11:30</option>
				<option value="1200N">12:00</option>
				<option value="1230P">12:30</option>
				<option value="100P">13:00</option>
				<option value="130P">13:30</option>
				<option value="200P">14:00</option>
				<option value="230P">14:30</option>
				<option value="300P">15:00</option>
				<option value="330P">15:30</option>
				<option value="400P">16:00</option>
				<option value="430P">16:30</option>
				<option value="500P">17:00</option>
				<option value="530P">17:30</option>
				<option value="600P">18:00</option>
				<option value="630P">18:30</option>
				<option value="700P">19:00</option>
				<option value="730P">19:30</option>
				<option value="800P">20:00</option>
				<option value="830P">20:30</option>
				<option value="900P">21:00</option>
				<option value="930P">21:30</option>
				<option value="1000P">22:00</option>
				<option value="1030P">22:30</option>
				<option value="1100P">23:00</option>
				<option value="1130P">23:30</option>
			</select>
		</div>
		<div class="form-group numberpicker">
			<label>車次號碼</label>
			<input class="form-control" name="backTrainIDInputField" type="text" id="backTrainIDInputField">
		</div>
	</div>
	<div class="form-group">
		<label>票種及張數</label>
		<select name="ticketPanel:rows:0:ticketAmount" class="form-control" id="ticketPanel:rows:0:ticketAmount">
			<option selected="selected" value="0F">全票 - 0</option>
			<option value="1F">全票 - 1</option>
			<option value="2F">全票 - 2</option>
			<option value="3F">全票 - 3</option>
			<option value="4F">全票 - 4</option>
			<option value="5F">全票 - 5</option>
			<option value="6F">全票 - 6</option>
			<option value="7F">全票 - 7</option>
			<option value="8F">全票 - 8</option>
			<option value="9F">全票 - 9</option>
			<option value="10F">全票 - 10</option>
		</select>
		<br>
		<select name="ticketPanel:rows:1:ticketAmount" class="form-control" style="display: none;">
			<option selected="selected" value="0H">孩童票(6-11歲) - 0</option>
			<option value="1H">孩童票(6-11歲) - 1</option>
			<option value="2H">孩童票(6-11歲) - 2</option>
			<option value="3H">孩童票(6-11歲) - 3</option>
			<option value="4H">孩童票(6-11歲) - 4</option>
			<option value="5H">孩童票(6-11歲) - 5</option>
			<option value="6H">孩童票(6-11歲) - 6</option>
			<option value="7H">孩童票(6-11歲) - 7</option>
			<option value="8H">孩童票(6-11歲) - 8</option>
			<option value="9H">孩童票(6-11歲) - 9</option>
			<option value="10H">孩童票(6-11歲) - 10</option>
		</select>
		<select name="ticketPanel:rows:2:ticketAmount" class="form-control" style="display: none;">
			<option selected="selected" value="0W">愛心票 - 0</option>
			<option value="1W">愛心票 - 1</option>
			<option value="2W">愛心票 - 2</option>
			<option value="3W">愛心票 - 3</option>
			<option value="4W">愛心票 - 4</option>
			<option value="5W">愛心票 - 5</option>
			<option value="6W">愛心票 - 6</option>
			<option value="7W">愛心票 - 7</option>
			<option value="8W">愛心票 - 8</option>
			<option value="9W">愛心票 - 9</option>
			<option value="10W">愛心票 - 10</option>
		</select>
		<select name="ticketPanel:rows:3:ticketAmount" class="form-control" style="display: none;">
			<option selected="selected" value="0E">敬老票(65歲以上) - 0</option>
			<option value="1E">敬老票(65歲以上) - 1</option>
			<option value="2E">敬老票(65歲以上) - 2</option>
			<option value="3E">敬老票(65歲以上) - 3</option>
			<option value="4E">敬老票(65歲以上) - 4</option>
			<option value="5E">敬老票(65歲以上) - 5</option>
			<option value="6E">敬老票(65歲以上) - 6</option>
			<option value="7E">敬老票(65歲以上) - 7</option>
			<option value="8E">敬老票(65歲以上) - 8</option>
			<option value="9E">敬老票(65歲以上) - 9</option>
			<option value="10E">敬老票(65歲以上) - 10</option>
		</select>
		<div class="form-group" style="display: none;">
			{{ Form::hidden('formUrl', Input::old('formUrl'), array('id' => 'formUrl','class' => 'form-control')) }}
		</div>
		<a href="#" id="btnTicketCheck" class="btn btn-primary">開始查詢</a>	
	</div>
	<div id="modal-ticketAuth" class="modal">
	    <div class="modal-dialog">
	      <div class="modal-content">
	        <div class="modal-header">
	            <a href="#" data-dismiss="modal" aria-hidden="true" class="close">×</a>
	            <h3>請輸入下圖中之驗證碼</h3>
	        </div>
	        <div class="modal-body">
	        	<div id="secureImage"></div>
	            <input class="form-control security" name="homeCaptcha:securityCode" type="text" id="homeCaptcha:securityCode">
	        </div>
	        <div class="modal-footer">
	          {{ Form::submit('OK', array('class' => 'btn btn-default')) }}
	          <a href="#" id="btnOrderNO" data-dismiss="modal" aria-hidden="true" class="btn btn-primary">Cancel</a>
	        </div>
	      </div>
	    </div>
	</div>

{{ Form::close() }}
<script>
	var backKey = 0;
	$(function(){
		$('.numberpicker').hide();
		$('#backInfo').hide();
		$('#bookingMethod').change(function(){
			if($(this).val()=='radio18'){
				$('.numberpicker').hide();
				$('.timepicker').show();
			}else{
				$('.numberpicker').show();
				$('.timepicker').hide();
			}
		});
		$('#backButton').click(function(){
			if(backKey == 0){
				$('#backInfo').show();
				backKey = 1;
			}else{
				$('#backInfo').hide();
				backKey = 0;
			}
		});

		//handle img security comfirm dialog modal
		$('#btnTicketCheck').on('click', function(e) {
			e.preventDefault();
			$('.security').val("");
			var path = "{{ URL::to('toys/hsp-train-security') }}";
			$('#secureImage').empty();
			//$('#modal-ticketAuth').modal('show');
			$.ajax({
				url: path,
				type: 'GET',
				success: function(data){
					$('#secureImage').append("<img src="+"'data:image/png;base64,"+data.imageUrl+"'/>");
					$('#formUrl').val(data.formUrl);
					$('#modal-ticketAuth').modal('show');
				},	
				error: function(){
					alert('驗證碼讀取失敗，請聯繫資訊人員');        
				}
			});
		});

		$('#toTimeInputField').datepicker({
			    format: "yyyy/mm/dd",
	    		autoclose: true,
	    		language: 'zh-TW'
		});
		var myDate = new Date();
		var dayOfMonth = myDate.getDate();
		myDate.setDate(dayOfMonth + 7);
		
		$("#toTimeInputField").datepicker("setDate", myDate);
		$("#toTimeInputField").datepicker('update');
	});
</script>

<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/locales/bootstrap-datepicker.zh-TW.js"></script>


@stop