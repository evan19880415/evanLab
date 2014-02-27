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
	public function getHspTrainDetail()
	{
		$toURL = "http://www.thsrc.com.tw/tw/Article/ArticleContent/e2b6e806-6db0-4dc0-9e0b-8636652ca4cf";
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
  		$nodes = $finder->query("/html/body/table/tbody/tr/td[1]/table/tbody/tr[1]/td");
		

		return $nodes->item(0)->nodeValue;
	}
}