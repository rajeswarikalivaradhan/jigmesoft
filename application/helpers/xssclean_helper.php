<?php defined('BASEPATH') OR exit('No direct script access allowed');
function xssclean($str) {
    if (is_array($str)) {
        /*while (list($key) = each($str)) {
            $str[$key] = $xssclean($str[$key]);
        }*/
        foreach ($str as &$value) {
            $value = xssclean($value);
        }
        return $str;
    }
    $str = _remove_invisible_characters($str);
    $str = preg_replace('|\&([a-z\_0-9]+)\=([a-z\_0-9]+)|i', _xss_hash() . "\\1=\\2", $str);
    $str = preg_replace('#(&\#?[0-9a-z]{2,})([\x00-\x20])*;?#i', "\\1;\\2", $str);
    $str = preg_replace('#(&\#x?)([0-9A-F]+);?#i', "\\1\\2;", $str);
    $str = str_replace(_xss_hash(), '&', $str);
    $str = rawurldecode($str);
    $str = preg_replace_callback("/[a-z]+=([\'\"]).*?\\1/si", '_convert_attribute', $str);
    $str = preg_replace_callback("/<\w+.*?(?=>|<|$)/si", '_html_entity_decode_callback', $str);
    $str = _remove_invisible_characters($str);
    $str = _remove_tabs($str);
    $str = _never_allowed_str($str);
    $str = _never_allowed_regx($str);
    $str = str_replace(array('<?', '?' . '>'), array('&lt;?', '?&gt;'), $str);
    $str = _never_allowed_words($str);
    do {
        $original = $str;
        if (preg_match("/<a/i", $str)) {
            $str = preg_replace_callback("#<a\s+([^>]*?)(>|$)#si", '_js_link_removal', $str);
        }
        if (preg_match("/<img/i", $str)) {
            $str = preg_replace_callback("#<img\s+([^>]*?)(\s?/?>|$)#si", '_js_img_removal', $str);
        }
        if (preg_match("/script/i", $str) OR preg_match("/xss/i", $str)) {
            $str = preg_replace("#<(/*)(script|xss)(.*?)\>#si", '', $str);
        }
    } while ($original != $str);
    unset($original);
    $event_handlers = array('[^a-z_\-]on\w*', 'xmlns');
    $str            = preg_replace("#<([^><]+?)(" . implode('|', $event_handlers) . ")(\s*=\s*[^><]*)([><]*)#i", "<\\1\\4", $str);
    $naughty        = 'alert|applet|audio|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|iframe|input|isindex|layer|link|meta|object|plaintext|style|script|textarea|title|video|xml|xss';
    $str            = preg_replace_callback('#<(/*\s*)(' . $naughty . ')([^><]*)([><]*)#is', '_sanitize_naughty_html', $str);
    $str            = preg_replace('#(alert|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si', "\\1\\2&#40;\\3&#41;", $str);
    $str            = _never_allowed_str($str);
    $str            = _never_allowed_regx($str);
    return $str;
}
function _remove_invisible_characters($str) {
    $VARNONDISPLAYABLES = array('/%0[0-8bcef]/', '/%1[0-9a-f]/', '/[\x00-\x08]/', '/\x0b/', '/\x0c/', '/[\x0e-\x1f]/');
    do {
        $cleaned = $str;
        $str     = preg_replace($VARNONDISPLAYABLES, '', $str);
    } while ($cleaned != $str);
    return $str;
}
function _xss_hash() {
    $VARXSSHASH = '';
    if ($VARXSSHASH == '') {
        if (phpversion() >= 4.2) {
            mt_srand();
        } else {
            mt_srand(hexdec(substr(md5(microtime()), -8)) & 0x7fffffff);
        }
        $VARXSSHASH = md5(time() + mt_rand(0, 1999999999));
    }
    return $VARXSSHASH;
}
function _convert_attribute($match) {
    return str_replace(array('>', '<', '\\'), array('&gt;', '&lt;', '\\\\'), $match[0]);
}
function _html_entity_decode_callback($match) {
    $charset = "UTF-8";
    return _html_entity_decode($match[0], strtoupper($charset));
}
function _never_allowed_str($str) {
    $VARNEVERALLOWEDSTR = array('document.cookie' => '', 'document.write' => '', '.parentNode' => '', '.innerHTML' => '', 'window.location' => '', '-moz-binding' => '', '<!--' => '&lt;!--', '-->' => '--&gt;', '<![CDATA[' => '&lt;![CDATA[');
    foreach ($VARNEVERALLOWEDSTR as $key => $val) {
        $str = str_replace($key, $val, $str);
    }
    return $str;
}
function _never_allowed_regx($str) {
    $VARNEVERALLOWEDREGX = array("javascript\s*:" => '', "expression\s*(\(|&\#40;)" => '', "vbscript\s*:" => '', "Redirect\s+302" => '');
    foreach ($VARNEVERALLOWEDREGX as $key => $val) {
        $str = preg_replace("#" . $key . "#i", $val, $str);
    }
    return $str;
}
function _never_allowed_words($str) {
    $VARNEVERALLOWEDWORDS = array('javascript', 'expression', 'vbscript', 'script', 'applet', 'alert', 'document', 'write', 'cookie', 'window');
    foreach ($VARNEVERALLOWEDWORDS as $word) {
        $temp = '';
        for ($i = 0, $wordlen = strlen($word); $i < $wordlen; $i++) {
            $temp .= substr($word, $i, 1) . "\s*";
        }
        $str = preg_replace_callback('#(' . substr($temp, 0, -3) . ')(\W)#is', '_compact_exploded_words', $str);
    }
    return $str;
}
function _js_link_removal($match) {
    $attributes = _filter_attributes(str_replace(array('<', '>'), '', $match[1]));
    return str_replace($match[1], preg_replace("#href=.*?(alert\(|alert&\#40;|javascript\charset\=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si", "", $attributes), $match[0]);
}
function _js_img_removal($match) {
    $attributes = _filter_attributes(str_replace(array('<', '>'), '', $match[1]));
    return str_replace($match[1], preg_replace("#src=.*?(alert\(|alert&\#40;|javascript\charset\=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si", "", $attributes), $match[0]);
}
function _sanitize_naughty_html($matches) {
    echo "<pre>";
    print_r($matches);
    echo "</pre>";
    $str = '&lt;' . $matches[1] . $matches[2] . $matches[3];
    $str .= str_replace(array('>', '<'), array('&gt;', '&lt;'), $matches[4]);
    return $str;
}
function _remove_tabs($str) {
    if (strpos($str, "\t") !== FALSE) {
        $str = str_replace("\t", ' ', $str);
    }
    return $str;
}
function _compact_exploded_words($matches) {
    return preg_replace('/\s+/s', '', $matches[1]) . $matches[2];
}
function _html_entity_decode($str, $charset = 'UTF-8') {
    if (stristr($str, '&') === FALSE) return $str;
    if (function_exists('html_entity_decode') && (strtolower($charset) != 'utf-8' OR version_compare(phpversion(), '5.0.0', '>='))) {
        $str = html_entity_decode($str, ENT_COMPAT, $charset);
        $str = preg_replace('~&#x(0*[0-9a-f]{2,5})~ei', 'chr(hexdec("\\1")', $str);
        return preg_replace('~&#([0-9]{2,4})~e', 'chr(\\1)', $str);
    }
    $str = preg_replace('~&#x(0*[0-9a-f]{2,5});{0,1}~ei', 'chr(hexdec("\\1")', $str);
    $str = preg_replace('~&#([0-9]{2,4});{0,1}~e', 'chr(\\1)', $str);
    if (stristr($str, '&') === FALSE) {
        $str = strtr($str, array_flip(get_html_translation_table(HTML_ENTITIES)));
    }
    return $str;
}
function _filter_attributes($str) {
    $out = '';
    if (preg_match_all('#\s*[a-z\-]+\s*=\s*(\042|\047)([^\\1]*?)\\1#is', $str, $matches)) {
        foreach ($matches[0] as $match) {
            $out .= preg_replace("#/\*.*?\*/#s", '', $match);
        }
    }
    return $out;
}
function _htmlentities($str) {
    if (is_array($str)) {
        while (list($key) = each($str)) {
            $str[$key] = _htmlentities($str[$key]);
        }
        return $str;
    }
    if (is_string($str)) {
        $str = htmlentities($str, ENT_QUOTES);
    }
    return $str;
}
function _striptags($str) {
    if (is_array($str)) {
        while (list($key) = each($str)) {
            $str[$key] = _striptags($str[$key]);
        }
        return $str;
    }
    if (is_string($str)) {
        $str   = html_entity_decode($str, ENT_COMPAT, 'UTF-8');
        $str   = strip_tags($str);
        $regex = "/<\/?\w+((\s+(\w|\w[\w-]*\w)(\s*=\s*(?:\".*?\"|'.*?'|[^'\">\s]+))?)+\s*|\s*)\/?>/i";
        $str   = preg_replace($regex, '', $str);
    }
    return $str;
}
?>