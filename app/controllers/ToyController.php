<?php

class ToyController extends BaseController {

	public function getInfo($city)
	{
		$toURL = "http://www.cwb.gov.tw/V7/forecast/taiwan/inc/city/$city.htm";
		$post = array();
		$ch = curl_init();
		$options = array(
			CURLOPT_REFERER=>'',
			CURLOPT_URL=>$toURL,
			CURLOPT_VERBOSE=>0,
			CURLOPT_RETURNTRANSFER=>true,
			CURLOPT_USERAGENT=>"Mozilla/4.0 (compatible;)",
			CURLOPT_POST=>true,
			CURLOPT_POSTFIELDS=>http_build_query($post),
		);
		curl_setopt_array($ch, $options);

		$result = curl_exec($ch); 
		curl_close($ch);
		//連接中央氣象局

		preg_match_all('/<table class="FcstBoxTable01" [^>]*[^>]*>(.*)<\/div>/si',$result, $matches, PREG_SET_ORDER);

		preg_match_all('/<td nowrap="nowrap" [^>]*[^>]*>(.*)<\/td>/si',$matches[0][1], $m1, PREG_SET_ORDER);

		$m2 = explode('</td>',$m1[0][1]);
		// print_r($m2);//取得每日資料m2[0~6]
		
		$weather = array();
		for($i=0;$i<=6;$i++){

			preg_match_all('/src=[^>]*[^>](.*)/si',$m2[$i], $m5, PREG_SET_ORDER);//取得天氣圖檔
			$m6 = explode('"',$m5[0][0]);
			$wi='http://www.cwb.gov.tw/V7/'.trim($m6[1],'\.\./\.\./');
			$wtitle = $m6[3];
			$weather[$i]['date'] = date("m-d", mktime(0, 0, 0, date("m"), date("d")+$i+1,date("Y")));
			$weather[$i]['temperature'] = trim(strip_tags($m2[$i]));
			$weather[$i]['title'] = $wtitle;
			$weather[$i]['img'] = $wi;
		}
		return $weather;
	}

	public function getHspTrainInfo()
	{
		$toURL = "http://www.thsrc.com.tw/tw/Article/ArticleContent/7039d17d-1463-4c14-ad93-4d491dedcad5";
		$ch = curl_init();
		$options = array(
			CURLOPT_URL=>$toURL,
			CURLOPT_RETURNTRANSFER=>true,
			CURLOPT_USERAGENT=>"Mozilla/4.0 (compatible;)"
		);
		curl_setopt_array($ch, $options);
		$result = curl_exec($ch); 
		curl_close($ch);
		//連接台灣高鐵
		$regexp_getLinkName = "<a.*?><strong><em>(.*)<\/em><\/strong><\/a>";
		preg_match_all("/$regexp_getLinkName/",$result, $nameMatches, PREG_SET_ORDER);

		$regexp_getLink = 'href=["](.*) target';
		$nameMatchesLength = count($nameMatches)-1;
		$trainLinkInfo = array(); 
		for($i=0;$i<=$nameMatchesLength;$i++){
			preg_match_all("/$regexp_getLink/",$nameMatches[$i][0], $linkMaches, PREG_SET_ORDER);
			$trainLinkInfo[$i]['title'] = $nameMatches[$i][1];
			$trainLinkInfo[$i]['link'] = $linkMaches[0][1];
		}

		return View::make('toys.highSpeedTrainBird')
				->with('trainLinkInfo',$trainLinkInfo);
	}

	public function hspTrainDetail($link)
	{
		$toURL = "http://www.thsrc.com.tw/tw/Article/ArticleContent/".$link;
		$ch = curl_init();
		$options = array(
			CURLOPT_URL=>$toURL,
			CURLOPT_RETURNTRANSFER=>true,
			CURLOPT_USERAGENT=>"Mozilla/4.0 (compatible;)"
		);
		curl_setopt_array($ch, $options);
		$result = curl_exec($ch); 
		curl_close($ch);
		//連接台灣高鐵
		libxml_use_internal_errors(true);
		$doc = new DOMDocument();
  		$doc->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">'.$result);
  		$finder = new DomXPath($doc);
  		$sounthernTableRows = $finder->query("/html/body/table/tbody/tr/td[1]/table/tbody/tr[contains(@class,'Th')]");
  		$northernNodes = $finder->query("/html/body/table/tbody/tr/td[2]/table/tbody/tr[contains(@class,'Th')]");
  		$sounthernTableContents = $finder->query("/html/body/table/tbody/tr/td[1]/table/tbody/tr[not(contains(@class,'Th'))]");
  		$northernTableContents = $finder->query("/html/body/table/tbody/tr/td[2]/table/tbody/tr[not(contains(@class,'Th'))]");

		// titleRow equals array length
		//getTitle of table
  		$titleRow = 0;
  		$title = array();
  		$dateTitleList = array();
  		foreach ($sounthernTableRows as $row) {
		    // fetch all 'td' inside this 'tr'
		    $td = $finder->query('td', $row);

		    if ($td->length == 1) {
		        $title[$titleRow]['direction'] = $td->item(0)->textContent;
		    } else { 
		        $title[$titleRow]['trainNumber'] = $td->item(0)->textContent;
		        $title[$titleRow]['time'] = $td->item(1)->textContent;

		        for($j=2;$j<=$td->length-1;$j++){
		        	$dateTitleList[$j-2] = $td->item($j)->textContent;
		        }
		        $title[$titleRow]['date'] = $dateTitleList;
		    }
		    $titleRow++;
		}

		$southernContents = $this->hspTrainParse($sounthernTableContents,$finder);
		$northernContents = $this->hspTrainParse($northernTableContents,$finder);
		return View::make('toys.highSpeedTrainInfo')
				->with('southernContents',$southernContents)
				->with('northernContents',$northernContents)
				->with('title',$title);	
	}

	private function hspTrainParse($path,$queryDom)
	{
		$content = array();
		$contentRow = 0;
		$dateContentList = array();

		//getContent of table
		$imgSrcPosition = array();
		foreach ($path as $row) {
		    // fetch all 'td' inside this 'tr'
		    $td = $queryDom->query('td', $row);
		    $imgId = 0;

 			foreach($td as $src){
 				$img = $queryDom->query('img', $src);
 				if($img->length == 1){
 					/*switch($img->item(0)->getAttribute('src')){
 						case '/UploadFiles/Article/a4ff63a8-4021-4726-9762-a913abbe5d6a.jpg':
 							$imgSrcPosition[$imgId] = 1;
 							break;
 						case '/UploadFiles/Article/ef2f197c-0e37-4a5c-acea-bd91a5832ba4.jpg':
 							$imgSrcPosition[$imgId] = 2;
 							break;
 						case '/UploadFiles/Article/01f6e891-6ccb-4db3-969f-b5d1dae14440.jpg':
 							$imgSrcPosition[$imgId] = 3;
 							break;
 						case '/UploadFiles/Article/a0c660d6-fc55-4ab5-b9c3-579abc699205.jpg':
 							$imgSrcPosition[$imgId] = 4;
 							break;			
 					}*/
 					$imgSrcPosition[$imgId] = $img->item(0)->getAttribute('src');
 				}else{
 					$imgSrcPosition[$imgId] = '-';
 				}
 				$imgId++;
 			}

		    $content[$contentRow]['trainNumber'] = $td->item(0)->textContent;
		    $content[$contentRow]['time'] = $td->item(1)->textContent;

		    $content[$contentRow]['date'] = $imgSrcPosition;
		    
		    $contentRow++;
		}
		return $content;

	}

	public function getHspTrainOrder()
	{

		return View::make('toys.highSpeedTrainOrder');
	}

	public function getHspTrainSecurity()
	{

		$toURL = "https://irs.thsrc.com.tw/IMINT";

		$pageResult = $this->sendHttpsRuest($toURL);
		
	  	return 	array('imageUrl'=>base64_encode($pageResult[0]),'formUrl'=>$pageResult[1]);
	  	//return dirname(dirname(dirname(__FILE__)))."/CAcerts/cookies.txt";	
	  	
	}
	public function sendHttpsRuest($toURL){
		$cookie = dirname(dirname(dirname(__FILE__)))."/CAcerts/cookies.txt";
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_HEADER,0); 
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)");
		curl_setopt($ch, CURLOPT_URL, $toURL);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		//curl_setopt($ch, CURLOPT_CAINFO, getcwd() . "/CAcerts/AddTrustExternalCARoot.crt");
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		$result = curl_exec($ch);
		$response = curl_getinfo($ch);

		//preg_match_all('/Set-Cookie:(.*);/iU',$result,$str);
		//$cookie = $str[1];

		if ($response['http_code'] == 301 || $response['http_code'] == 302){
			curl_setopt($ch,CURLOPT_COOKIEFILE,$cookie);
			$result = curl_exec($ch);
			$response = curl_getinfo($ch);
		}

		libxml_use_internal_errors(true);
		$doc = new DOMDocument();
	  	$doc->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">'.$result);
	  	$finder = new DomXPath($doc);
	  	$securityImage = $finder->query('//*[@id="BookingS1Form_homeCaptcha_passCode"]');
	  	$securityform = $finder->query('//*[@id="BookingS1Form"]');

	  	$imageUrl = "https://irs.thsrc.com.tw".$securityImage->item(0)->getAttribute('src');
	  	$formUrl =  "https://irs.thsrc.com.tw".$securityform->item(0)->getAttribute('action');
	  	curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
	  	curl_setopt($ch, CURLOPT_URL, $imageUrl);
	  	$result = curl_exec($ch);

		curl_close($ch);
		return array($result,$formUrl);
	}

	public function postHspTrainQuery()
	{

		$toURL = "https://irs.thsrc.com.tw/IMINT";
		$formUrl = Input::get('formUrl');
		$ticketData = array(
			'selectStartStation'=>Input::get('selectStartStation'),
			'selectDestinationStation'=>Input::get('selectDestinationStation'),
			'trainCon:trainRadioGroup'=>Input::get('trainCon:trainRadioGroup'),
			'bookingMethod'=>Input::get('bookingMethod'),
			'toTimeInputField'=>Input::get('toTimeInputField'),
			'toTimeTable'=>Input::get('toTimeTable'),
			'toTrainIDInputField'=>Input::get('toTrainIDInputField'),
			'backTimeInputField'=>Input::get('backTimeInputField'),
			'backTimeTable'=>Input::get('backTimeTable'),
			'backTrainIDInputField'=>Input::get('backTrainIDInputField'),
			'ticketPanel:rows:0:ticketAmount'=>Input::get('ticketPanel:rows:0:ticketAmount'),
			'ticketPanel:rows:1:ticketAmount'=>Input::get('ticketPanel:rows:1:ticketAmount'),
			'ticketPanel:rows:2:ticketAmount'=>Input::get('ticketPanel:rows:2:ticketAmount'),
			'ticketPanel:rows:3:ticketAmount'=>Input::get('ticketPanel:rows:3:ticketAmount'),
			'homeCaptcha:securityCode'=>Input::get('homeCaptcha:securityCode'),
			'offPeakTrainSearchContainer:onlyQueryOffPeak'=>'on');

		$pageResult = $this->sendHttpsRuestPost($toURL,$ticketData,$formUrl);

		libxml_use_internal_errors(true);
		$doc = new DOMDocument();
	  	$doc->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">'.$pageResult);
	  	$finder = new DomXPath($doc);
	  	$securityform = $finder->query('//*[@id="BookingS2Form"]');
	  	$contentTable = $finder->query("//*[@id='BookingS2Form_TrainQueryDataViewPanel']/table/tr[not(contains(@class,'section_subtitle'))]");

	  	$prevLink = $finder->query('//*[@id="BookingS2Form_TrainQueryDataViewPanel_PreAndLaterTrainContainer_preTrainLink"]');
	  	$nextLink = $finder->query('//*[@id="BookingS2Form_TrainQueryDataViewPanel_PreAndLaterTrainContainer_laterTrainLink"]');
	  	//$formUrl = $securityform->item(0)->getAttribute('action');

		//getContent of table
  		$contentRow = 0;
  		$content = array();
  		$dateContentList = array();
  		foreach ($contentTable as $row) {
		    // fetch all 'td' inside this 'tr'
		    $td = $finder->query('td', $row);
		    $inputValue = $finder->query('./input',$td->item(0)); 
		    $img = $finder->query('./img',$td->item(2)); 
   
		    $content[$contentRow]['value'] = $inputValue->item(0)->getAttribute('value');
		    $content[$contentRow]['number'] = $td->item(1)->textContent;
		    if($img->length <>0){
		    	$content[$contentRow]['discount'] = $img->item(0)->getAttribute('src');
		    }else{
		    	$content[$contentRow]['discount'] = "";
		    }
		    $content[$contentRow]['startTime'] = $td->item(3)->textContent;
		    $content[$contentRow]['destinationTime'] = $td->item(4)->textContent;

		    $contentRow++;
		}

	  	//return 	$contentTable->item(0)->nodeValue;
	  	return View::make('toys.highSpeedTrainContent')
	  			->with("content",$content);
	  	
	}

	public function sendHttpsRuestPost($toURL,$ticketData,$formUrl){
		$cookie = dirname(dirname(dirname(__FILE__)))."/CAcerts/cookies.txt";
		$postData = '';
   		//create name value pairs seperated by &
   		foreach($ticketData as $k => $v){ 
      		$postData .= $k . '='.$v.'&'; 
   		}
   		rtrim($postData, '&');
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_HEADER,0); 
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch,CURLOPT_COOKIEFILE,$cookie);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);  
	  	curl_setopt($ch, CURLOPT_URL, $formUrl);
	  	$result = curl_exec($ch);
	  	$response = curl_getinfo($ch);

	  	if ($response['http_code'] == 301 || $response['http_code'] == 302){
	  		curl_setopt($ch,CURLOPT_COOKIEFILE,$cookie);
	  		curl_setopt($ch, CURLOPT_HTTPGET, true);
			curl_setopt($ch, CURLOPT_URL, $response['redirect_url']);
			$result = curl_exec($ch);
			$response = curl_getinfo($ch);
		}
		
		curl_close($ch);
		return $result;
	}

	
}