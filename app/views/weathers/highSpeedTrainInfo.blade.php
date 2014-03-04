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
	<div id="train">
		<h1>High Speed Train Bird Ticket</h1>
		<br>
		<table class='table'>
			<tr>
				<td>
					<img src="http://www.thsrc.com.tw/UploadFiles/Article/a4ff63a8-4021-4726-9762-a913abbe5d6a.jpg"/>
				</td>
				<td>表限量提供早鳥65折、8折或9折優惠</td>
			</tr>
			<tr>	
				<td>
					<img src="http://www.thsrc.com.tw/UploadFiles/Article/ef2f197c-0e37-4a5c-acea-bd91a5832ba4.jpg"/>
				</td>
				<td>表限量提供早鳥8折或早鳥9折，恕不提供早鳥65折優惠</td>
			</tr>
			<tr>	
				<td>
					<img src="http://www.thsrc.com.tw/UploadFiles/Article/01f6e891-6ccb-4db3-969f-b5d1dae14440.jpg"/>
				</td>
				<td>表全面適用早鳥65折優惠</td>
			</tr>
			<tr>	
				<td>
					<img src="http://www.thsrc.com.tw/UploadFiles/Article/a0c660d6-fc55-4ab5-b9c3-579abc699205.jpg"/>
				</td>
				<td>表限量提供早鳥65折或8折，其餘全面適用早鳥9折優惠</td>
			</tr>
		</table>
		<ul class="nav nav-pills nav-justified">
			<li class="active">
				<a href="#southern" data-toggle="tab">南下列車</a>
			</li>
			<li>
				<a href="#northern" data-toggle="tab">北上列車</a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="southern"> 	
				<table class='table'>
					<tr>
						<td>{{ $title[1]['trainNumber'] }}</td>
						<td>{{ $title[1]['time'] }}</td>
						@for($j=0;$j<=count($title[1]['date'])-1;$j++)
								<td>{{ $title[1]['date'][$j] }}</td>
						@endfor	
					</tr>	
					@for($i=0;$i<=count($southernContents)-1;$i++)
						<tr>
							<td>{{ $southernContents[$i]['trainNumber'] }}</td>
							<td>{{ $southernContents[$i]['time'] }}</td>
							@for($j=2;$j<=count($southernContents[$i]['date'])-1;$j++)
								<td>
									@if($southernContents[$i]['date'][$j] <> '-')
										<img src="http://www.thsrc.com.tw{{ $southernContents[$i]['date'][$j] }}"/>
									@else
										<span>-</span>
									@endif	
								</td>
							@endfor	
						</tr>
					@endfor	
				</table>
			</div>
			<div class="tab-pane" id="northern">
				<table class='table'>
					<tr>
						<td>{{ $title[1]['trainNumber'] }}</td>
						<td>{{ $title[1]['time'] }}</td>
						@for($j=0;$j<=count($title[1]['date'])-1;$j++)
								<td>{{ $title[1]['date'][$j] }}</td>
						@endfor	
					</tr>	
					@for($i=0;$i<=count($northernContents)-1;$i++)
						<tr>
							<td>{{ $northernContents[$i]['trainNumber'] }}</td>
							<td>{{ $northernContents[$i]['time'] }}</td>
							@for($j=2;$j<=count($northernContents[$i]['date'])-1;$j++)
								<td>
									@if($northernContents[$i]['date'][$j] <> '-')
										<img src="http://www.thsrc.com.tw{{ $northernContents[$i]['date'][$j] }}"/>
									@else
										<span>-</span>
									@endif	
								</td>
							@endfor	
						</tr>
					@endfor	
				</table>
			</div>
		</div>	
	</div>

</div>

<!-- Latest compiled and minified JavaScript -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
</body>
</html>