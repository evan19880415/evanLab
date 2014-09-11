<?php

class DropboxController extends Controller {

	public function dropboxFolder()
	{
		$toURL = "https://www.dropbox.com/sh/3wyiu6ahmjdxgxn/AACTjahF8LU7Vg3B3OrW-wPda?dl=0";
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_HEADER,0); 
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  	curl_setopt($ch, CURLOPT_URL, $toURL);
		$result = curl_exec($ch);
		curl_close($ch);
		//連接Dropbox
		libxml_use_internal_errors(true);
		$doc = new DOMDocument();
		$doc->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">'.$result);
  		$finder = new DomXPath($doc);
  		$content = array();
  		$mediaNode = $finder->query('//*[@id="gallery-view-media"]/li');
  		foreach ($mediaNode as $row) {
			$aNode = $finder->query('a', $row);
			array_push($content,$aNode->item(0)->getAttribute('href'));
		}
		return $content;	
	}

	public function dropboxDetail()
	{
		$content = $this->dropboxFolder();
		$imgSrc = array();
		foreach($content as $link) {
			$toURL = $link;
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_HEADER,0); 
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)");
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		  	curl_setopt($ch, CURLOPT_URL, $toURL);
			$result = curl_exec($ch);
			curl_close($ch);

			//連接Dropbox
			libxml_use_internal_errors(true);
			$doc = new DOMDocument();
			$doc->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">'.$result);
	  		$finder = new DomXPath($doc);
	  		$imgNode = $finder->query('//*[@id="download_button_link"]');
	  		array_push($imgSrc,$imgNode->item(0)->getAttribute('href'));
		}

		return View::make('dropbox.dropboxDetail')
		  			->with("imgSrc",$imgSrc);
	}

}