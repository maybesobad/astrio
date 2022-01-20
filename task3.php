<?php

$arrayTags = ["<a>", "<b>", "</a>", "</b>", "<div>",  "</b>", "</div>",  "<span>", "<span>", "</span>", "<span>", "</span>", "</span>"];

function test(array $arrayTags): bool
{
	$arrayLowercaseTags = ['<a>', '<b>', '<big>', '<br>', '<em>',
						'<i>', '<img>', '<small>', '<span>',
						'<strong>', '<sub>', '<sup>'];

	if (empty($arrayTags)) {
	 	return false;
	}

	foreach ($arrayTags as $arrayTag => &$value) {
		if ($value[1] == '/') {
			return false;
		}

		$closeTag = '</' . substr($value, 1);
		$keyСloseTag = array_search($closeTag, $arrayTags);

		if ($keyСloseTag === false) {
		 	return false;
		}

		$arrayChildTags = array_slice($arrayTags, $arrayTag + 1, $keyСloseTag - $arrayTag - 1);

		if (!empty($arrayChildTags)) {
			$lowercaseTag = array_search($value, $arrayLowercaseTags);
			if ($lowercaseTag !== false) {
				foreach ($arrayChildTags as $arrayChildTag => $value) {
					$childLowercaseTag = array_search($value, $arrayLowercaseTags);
					if ($childLowercaseTag == false and $value[1] != '/') {
						return false;
					}
				}
			}
			if (!test($arrayChildTags)) {
				return false;
			}
		}
		unset($arrayTags[$arrayTag], $arrayTags[$keyСloseTag]);
	}
	return true;
};

var_dump(test($arrayTags));

?>