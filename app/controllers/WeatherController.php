<?php

class WeatherController extends BaseController {

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

		return View::make('weathers.highSpeedTrainBird')
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

		// titleRow equals array length
		//getTitle of table
  		$titleRow = 0;
  		$contentRow = 0;
  		$title = array();
  		$content = array();
  		$dateTitleList = array();
  		$dateContentList = array();
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

		//getContent of table
		$imgSrcPosition = array();
		foreach ($sounthernTableContents as $row) {
		    // fetch all 'td' inside this 'tr'
		    $td = $finder->query('td', $row);
		    $imgId = 0;

 			foreach($td as $src){
 				$img = $finder->query('img', $src);
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

		return View::make('weathers.highSpeedTrainInfo')
				->with('content',$content)
				->with('title',$title);
	}
}