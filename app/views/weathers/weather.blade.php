<!-- app/views/caes/index.blade.php -->

<!DOCTYPE html>
<html>
<head>
	<title>Taiwan Forecast</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
	<!-- Optional theme -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap-theme.min.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container">
	<div id="weather" class="jumbotron">
		<h1>Taiwan Forecast</h1>
		<div class="form-group">
			<label>Choose the location:</label>
			<select id="locationMenu" class="form-control">  
				<option value="Taipei_City" selected="selected">臺北市</option>  
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
			<table id="weatherInfo" class='table'>
				<tr>
				</tr>
			</table>		
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
		$.get("{{ url('weather/info')}}"+"/"+$(this).val(), 
			function(data) {
				var model = $('#weatherInfo');
				model.empty();
				$.each(data, function(index, element) {
					model.append("<td align='center'>"+
							"<table>"+
								"<tr>"+
									"<td>"+ element.date +"<td>"+
								"</tr>"+
								"<tr>"+
									"<td>"+ "<img src='"+element.img+"'/>"+"<td>"+
								"</tr>"+
								"<tr>"+
									"<td>"+ element.temperature +"<td>"+
								"</tr>"+
							"</table>"+	
						"</td>");
			$('#loading').empty();
			});
		});
	});
});	
</script>
<!-- Latest compiled and minified JavaScript -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
</body>
</html>