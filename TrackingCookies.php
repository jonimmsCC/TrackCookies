<?php
	
/*------------------------------------*\
   Add UTM Cookies
   -- This sets Tracking Cookies. 
/*------------------------------------*/

function set_ad_cookies() {
	$vars = array('utm_source', 'utm_campaign', 'utm_medium');
  //$parsed = array();
  	foreach ($vars as $k) {
    	if (isset($_GET[$k]) && !isset($_COOKIE['orig_'.$k])) {
        	setcookie('orig_'.$k, $_GET[$k], 0, '/', $domains['cookie_domain']);
    	}
   	}
  
   	if (!isset($_COOKIE['orig_utmsource'])) {
	 	setcookie('orig_utmsource', $_GET['utm_source'], 0, '/', $domains['cookie_domain']);
	}
}
add_action('init', 'set_ad_cookies');

/*------------------------------------*\
  Append UTMs to links.
  - IF you have form submissions, or similar,
  - This will append the UTM string to
  - the end of the URL, so you can see which
  - ADD the user came from. Add your URL below. 
/*------------------------------------*/
function replace_links($text) {
	$url = explode('?', 'https://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
	$addCookiesToLink = "";
	if (strlen($url[1]) > 1) {
		$text = str_replace('yourwebsiteurl', 'yourwebsiteurl?' . $url[1], $text);
	}
		else { //no parameters in URL. Cookies?
		if(isset($_COOKIE['orig_utmsource'])) {
			$addCookiesToLink .= "utm_source=" . $_COOKIE['orig_utmsource'];
		}
		elseif(isset($_COOKIE['orig_utm_source'])) {
			if(strlen($addCookiesToLink) > 0) {
				$addCookiesToLink .= "&";
			}
			$addCookiesToLink .= "utm_source=" . $_COOKIE['orig_utm_source'];
		}
		elseif(isset($_COOKIE['orig_utmmedium'])) {
			if(strlen($addCookiesToLink) > 0) {
				$addCookiesToLink .= "&";
			}	
			$addCookiesToLink .= "utm_medium=" . $_COOKIE['orig_utmmedium'];
		}
		elseif(isset($_COOKIE['orig_utmcampaign'])) {
			if(strlen($addCookiesToLink) > 0) {
				$addCookiesToLink .= "&";
			}	
			$addCookiesToLink .= "utm_campaign=" . $_COOKIE['orig_utmcampaign'];
		}
		elseif(isset($_COOKIE['orig_utm_campaign'])) {
			if(strlen($addCookiesToLink) > 0) {
				$addCookiesToLink .= "&";
			}	
			$addCookiesToLink .= "utm_campaign=" . $_COOKIE['orig_utm_campaign'];
		}
				if(strlen($addCookiesToLink) > 0) {
				//echo $addCookiesToLink;
					$text = str_replace('yourwebsiteurl', 'yourwebsiteurl?' . $addCookiesToLink, $text);
			
		}
		//echo "test1" . $_COOKIE['orig_utm_campaign'] . " 2:" . $_COOKIE['orig_utm_source'] . " 3:" . $_COOKIE['orig_utmcampaign'] . " 4:" . $_COOKIE['orig_utmmedium'] . " 5:" . $_COOKIE['orig_utmsource'];
	}
	return $text;
}

/* --------------------------------*\
	- SET Phone Number Cookies
	- If you have Dynamic Phone numbers
	- to track, then use this code.
/* ---------------------------------*/
function set_phone_cookie() {
	$url = explode('?', 'https://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
	$params = explode('&', $url[1]);
	$paramTotal = count($params);
	For ($i = 1; $i <= $paramTotal; $i++) {
		if (strpos($params[$i-1], 'utm_source') !== false) {
			$theParam = $params[$i-1];
		}
	}
	//find utm_source
	$utmExplode = explode('=', $theParam);
	//explode on "=" to get utm_source equaled
	$utmEquals = $utmExplode[1];
	if (strlen($utmEquals) > 1 || isset($_COOKIE['utm_source_phone'])) {
		if (!isset($_COOKIE['utm_source_phone'])) {
			setcookie('utm_source_phone', $utmEquals, 0, '/');
		}
		else {
			$utmEquals = $_COOKIE['utm_source_phone'];
		}
	}	
}

