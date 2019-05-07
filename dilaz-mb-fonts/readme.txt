



add_filter('dilaz_metabox_fonts_target_prefix', function($prefixes) {
	array_push($prefixes, 'my_prefix', 'my_test');
	return $prefixes;
}, 10, 1);