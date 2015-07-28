<?php


add_action('lp_init_module_redirect', 'lp_keyword_shorcode_retrieve_keyword');
add_action('lp_init', 'lp_keyword_shorcode_retrieve_keyword');
function lp_keyword_shorcode_retrieve_keyword()
{
	if (isset($_SERVER['HTTP_REFERER']))
	{
		$referrer = $_SERVER['HTTP_REFERER'];

		preg_match('/q=(.*?)(&|\z)/', $referrer,$matches);
		($matches) ? $keywords = $matches[1] : $keywords = '';
		
		if (!empty($keywords))
		{
			//echo $keywords;
			$keywords = urldecode($keywords);
			$keywords = str_replace('site:','',$keywords);
			$keywords = str_replace('n/a','',$keywords);
			$keywords = str_replace('site:','',$keywords);						
			$keyword = lp_keyword_shortcode_decode_unicode_url($keywords);	
			
			$_SESSION['lp-ext-keyword'] = $keyword;
			$_SESSION['replacement-keyword'] = $keyword;
			return;
		}
	}
			
	if (isset($_REQUEST['keyword'])) 
		$keyword = $_REQUEST['keyword'];

	if (!$keyword&&isset($_REQUEST['q'])) 
		$keyword = $_REQUEST['q'];

	if ($keyword)
	{
		$keyword = urldecode($keyword);
		$_SESSION['lp-ext-keyword'] = $keyword;
		setcookie('lp-ext-keyword', $keyword,time()+3600,"/");
		$_SESSION['replacement-keyword'] = $keyword;
	}
}
		
add_shortcode('lp-keyword', 'lp_keyword_shortcode_callback');
function lp_keyword_shortcode_callback($args)
{
	extract(shortcode_atts(array(
		  'default' => null
	), $args));
	
	if (isset($_SESSION['replacement-keyword']))
	{
		return $_SESSION['replacement-keyword'];
	}			
	else if (isset($_SESSION['lp-ext-keyword']))
	{
		return $_SESSION['lp-ext-keyword'];
	}
	else if (!empty($default))
	{
		return $default;
	}
	else
	{
		return "";
	}
}


add_shortcode('lp-keyword-random', 'lp_keyword_random_shortcode_callback');
function lp_keyword_random_shortcode_callback($args)
{

	extract(shortcode_atts(array(
		  'keywords' => null
	), $args));
	 
	if (!isset($_SESSION['lp-ext-keyword-select'])&&$keywords)
	{
		$keywords = explode( ',' , $keywords );
		$keywords = array_filter($keywords);
		
		if (count($keywords)>0) 
		{
			$rand_key = array_rand($keywords);
			$_SESSION['lp-ext-keyword-select'] = trim($keywords[$rand_key]);
			$keyword_replacement = $keywords[$rand_key];
		}
	}

	if (isset($keyword_replacement))
	{
		return $keyword_replacement;
	}	
	else if (isset($_SESSION['lp-ext-keyword-select']))
	{
		return $_SESSION['lp-ext-keyword-select'];
	}
	else
	{
		return "";
	}
}


add_filter( 'the_title', 'do_shortcode' );

function lp_keyword_shortcode_decode_unicode_url($str)
{
  $res = '';

  $i = 0;
  $max = strlen($str) - 6;
  while ($i <= $max)
  {
	$character = $str[$i];
	if ($character == '%' && $str[$i + 1] == 'u')
	{
	  $value = hexdec(substr($str, $i + 2, 4));
	  $i += 6;

	  if ($value < 0x0080) // 1 byte: 0xxxxxxx
		$character = chr($value);
	  else if ($value < 0x0800) // 2 bytes: 110xxxxx 10xxxxxx
		$character =
			chr((($value & 0x07c0) >> 6) | 0xc0)
		  . chr(($value & 0x3f) | 0x80);
	  else // 3 bytes: 1110xxxx 10xxxxxx 10xxxxxx
		$character =
			chr((($value & 0xf000) >> 12) | 0xe0)
		  . chr((($value & 0x0fc0) >> 6) | 0x80)
		  . chr(($value & 0x3f) | 0x80);
	}
	else
	  $i++;

	$res .= $character;
  }

  return $res . substr($str, $i);
}

?>