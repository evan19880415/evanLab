@extends('layouts.master')
@section('content')
	<div id="weather" class="jumbotron">
		<h1>Taiwan Forecast</h1>
		<br>
		<div class="form-group">
			<select id="locationMenu" class="form-control">
				<option value="Taipei_City" selected="selected">Choose a location</option>  
				<option value="Taipei_City">臺北市</option>  
				<option value="New_Taipei_City">新北市</option>  
				<option value="Taichung_City">臺中市</option>  
				<option value="Tainan_City">臺南市</option>  
				<option value="Kaohsiung_City">高雄市</option>  
				<option value="Keelung_City">基隆市</option>  
				<option value="Taoyuan_County">桃園縣</option>  
				<option value="Hsinchu_City">新竹市</option>  
				<option value="Hsinchu_County">新竹縣</option>  
				<option value="Miaoli_County">苗栗縣</option>  
				<option value="Changhua_County">彰化縣</option>  
				<option value="Nantou_County">南投縣</option>  
				<option value="Yunlin_County">雲林縣</option>  
				<option value="Chiayi_City">嘉義市</option>  
				<option value="Chiayi_County">嘉義縣</option>  
				<option value="Pingtung_County">屏東縣</option>  
				<option value="Yilan_County">宜蘭縣</option>  
				<option value="Hualien_County">花蓮縣</option>  
				<option value="Taitung_County">臺東縣</option>  
				<option value="Penghu_County">澎湖縣</option>  
				<option value="Kinmen_County">金門縣</option>  
				<option value="Lienchiang_County">連江縣</option>          
			</select>
		</div>
		<br>
		<div id="weatherContainer" align='center'>
			<h2>Extended Forecast</h2>
			<br>
			<div id="loading"></div>
			<div id="weatherInfo" class='row'>
				
			</div>		
		</div>
	</div>
<script>
$(function(){
	$("#weatherContainer").hide();
	$("#locationMenu").change(function() {
		$("#weatherContainer").show('fast');
		$('#weatherInfo').empty();
		$('#loading').append("<img src='"+"{{ asset('ajax-loader.gif') }}"+"'/>");
		$.get("{{ url('toys/info')}}"+"/"+$(this).val(), 
			function(data) {
				var model = $('#weatherInfo');
				model.empty();
				$.each(data, function(index, element) {
					model.append("<div align='center' class='col-md-2'>"+
							"<table class='table' style='margin-top:10px;border:2px solid black;'>"+
								"<tr align='center' style='border:2px solid black;'>"+
									"<td>"+ element.date +"</td>"+
								"</tr>"+
								"<tr align='center' style='border:2px solid black;'>"+
									"<td>"+ "<img src='"+element.img+"'/>"+"</td>"+
								"</tr>"+
								"<tr align='center' style='border:2px solid black;'>"+
									"<td>"+ element.temperature +"</td>"+
								"</tr>"+
							"</table>"+	
						"</div>");
			$('#loading').empty();
			});
		});
	});
});	
</script>
@stop