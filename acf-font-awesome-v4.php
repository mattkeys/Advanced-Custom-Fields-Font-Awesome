<?php

class acf_field_font_awesome extends acf_field
{
	// vars
	var $settings, // will hold info such as dir / path
		$defaults; // will hold default field options

	/*
	*  __construct
	*
	*  Set name / label needed for actions / filters
	*
	*  @since	3.6
	*  @date	23/01/13
	*/

	function __construct()
	{
		$this->name = 'font-awesome';
		$this->label = __('Font Awesome Icon');
		$this->category = __("Content",'acf'); // Basic, Content, Choice, etc
		$this->defaults = array(
			'enqueue_fa' 	=>	0,
			'allow_null' 	=>	0,
			'save_format'	=>  'element',
			'default_value'	=>	'',
			'choices'		=>	array(
				'fa-adjust'					=> '&#xf042; fa-adjust',
				'fa-adn'					=> '&#xf170; fa-adn',
				'fa-align-center'			=> '&#xf037; fa-align-center',
				'fa-align-justify'			=> '&#xf039; fa-align-justify',
				'fa-align-left'				=> '&#xf036; fa-align-left',
				'fa-align-right'			=> '&#xf038; fa-align-right',
				'fa-ambulance'				=> '&#xf0f9; fa-ambulance',
				'fa-anchor'					=> '&#xf13d; fa-anchor',
				'fa-android'				=> '&#xf17b; fa-android',
				'fa-angle-double-down'		=> '&#xf103; fa-angle-double-down',
				'fa-angle-double-left'		=> '&#xf100; fa-angle-double-left',
				'fa-angle-double-right'		=> '&#xf101; fa-angle-double-right',
				'fa-angle-double-up'		=> '&#xf102; fa-angle-double-up',
				'fa-angle-down'				=> '&#xf107; fa-angle-down',
				'fa-angle-left'				=> '&#xf104; fa-angle-left',
				'fa-angle-right'			=> '&#xf105; fa-angle-right',
				'fa-angle-up'				=> '&#xf106; fa-angle-up',
				'fa-apple'					=> '&#xf179; fa-apple',
				'fa-archive'				=> '&#xf187; fa-archive',
				'fa-arrow-circle-down'		=> '&#xf0ab; fa-arrow-circle-down',
				'fa-arrow-circle-left'		=> '&#xf0a8; fa-arrow-circle-left',
				'fa-arrow-circle-o-down'	=> '&#xf01a; fa-arrow-circle-o-down',
				'fa-arrow-circle-o-left'	=> '&#xf190; fa-arrow-circle-o-left',
				'fa-arrow-circle-o-right'	=> '&#xf18e; fa-arrow-circle-o-right',
				'fa-arrow-circle-o-up'		=> '&#xf01b; fa-arrow-circle-o-up',
				'fa-arrow-circle-right'		=> '&#xf0a9; fa-arrow-circle-right',
				'fa-arrow-circle-up'		=> '&#xf0aa; fa-arrow-circle-up',
				'fa-arrow-down'				=> '&#xf063; fa-arrow-down',
				'fa-arrow-left'				=> '&#xf060; fa-arrow-left',
				'fa-arrow-right'			=> '&#xf061; fa-arrow-right',
				'fa-arrow-up'				=> '&#xf062; fa-arrow-up',
				'fa-arrows'					=> '&#xf047; fa-arrows',
				'fa-arrows-alt'				=> '&#xf0b2; fa-arrows-alt',
				'fa-arrows-h'				=> '&#xf07e; fa-arrows-h',
				'fa-arrows-v'				=> '&#xf07d; fa-arrows-v',
				'fa-asterisk'				=> '&#xf069; fa-asterisk',
				'fa-automobile'				=> '&#xf1b9; fa-automobile',
				'fa-backward'				=> '&#xf04a; fa-backward',
				'fa-ban'					=> '&#xf05e; fa-ban',
				'fa-bank'					=> '&#xf19c; fa-bank',
				'fa-bar-chart-o'			=> '&#xf080; fa-bar-chart-o',
				'fa-barcode'				=> '&#xf02a; fa-barcode',
				'fa-bars'					=> '&#xf0c9; fa-bars',
				'fa-beer'					=> '&#xf0fc; fa-beer',
				'fa-behance'				=> '&#xf1b4; fa-behance',
				'fa-behance-square'			=> '&#xf1b5; fa-behance-square',
				'fa-bell'					=> '&#xf0f3; fa-bell',
				'fa-bell-o'					=> '&#xf0a2; fa-bell-o',
				'fa-bitbucket'				=> '&#xf171; fa-bitbucket',
				'fa-bitbucket-square'		=> '&#xf172; fa-bitbucket-square',
				'fa-bitcoin'				=> '&#xf15a; fa-bitcoin',
				'fa-bold'					=> '&#xf032; fa-bold',
				'fa-bolt'					=> '&#xf0e7; fa-bolt',
				'fa-bomb'					=> '&#xf1e2; fa-bomb',
				'fa-book'					=> '&#xf02d; fa-book',
				'fa-bookmark'				=> '&#xf02e; fa-bookmark',
				'fa-bookmark-o'				=> '&#xf097; fa-bookmark-o',
				'fa-briefcase'				=> '&#xf0b1; fa-briefcase',
				'fa-btc'					=> '&#xf15a; fa-btc',
				'fa-bug'					=> '&#xf188; fa-bug',
				'fa-building'				=> '&#xf1ad; fa-building',
				'fa-building-o'				=> '&#xf0f7; fa-building-o',
				'fa-bullhorn'				=> '&#xf0a1; fa-bullhorn',
				'fa-bullseye'				=> '&#xf140; fa-bullseye',
				'fa-cab'					=> '&#xf1ba; fa-cab',
				'fa-calendar'				=> '&#xf073; fa-calendar',
				'fa-calendar-o'				=> '&#xf133; fa-calendar-o',
				'fa-camera'					=> '&#xf030; fa-camera',
				'fa-camera-retro'			=> '&#xf083; fa-camera-retro',
				'fa-car'					=> '&#xf1b9; fa-car',
				'fa-caret-down'				=> '&#xf0d7; fa-caret-down',
				'fa-caret-left'				=> '&#xf0d9; fa-caret-left',
				'fa-caret-right'			=> '&#xf0da; fa-caret-right',
				'fa-caret-square-o-down'	=> '&#xf150; fa-caret-square-o-down',
				'fa-caret-square-o-left'	=> '&#xf191; fa-caret-square-o-left',
				'fa-caret-square-o-right'	=> '&#xf152; fa-caret-square-o-right',
				'fa-caret-square-o-up'		=> '&#xf151; fa-caret-square-o-up',
				'fa-caret-up'				=> '&#xf0d8; fa-caret-up',
				'fa-certificate'			=> '&#xf0a3; fa-certificate',
				'fa-chain'					=> '&#xf0c1; fa-chain',
				'fa-chain-broken'			=> '&#xf127; fa-chain-broken',
				'fa-check'					=> '&#xf00c; fa-check',
				'fa-check-circle'			=> '&#xf058; fa-check-circle',
				'fa-check-circle-o'			=> '&#xf05d; fa-check-circle-o',
				'fa-check-square'			=> '&#xf14a; fa-check-square',
				'fa-check-square-o'			=> '&#xf046; fa-check-square-o',
				'fa-chevron-circle-down'	=> '&#xf13a; fa-chevron-circle-down',
				'fa-chevron-circle-left'	=> '&#xf137; fa-chevron-circle-left',
				'fa-chevron-circle-right'	=> '&#xf138; fa-chevron-circle-right',
				'fa-chevron-circle-up'		=> '&#xf139; fa-chevron-circle-up',
				'fa-chevron-down'			=> '&#xf078; fa-chevron-down',
				'fa-chevron-left'			=> '&#xf053; fa-chevron-left',
				'fa-chevron-right'			=> '&#xf054; fa-chevron-right',
				'fa-chevron-up'				=> '&#xf077; fa-chevron-up',
				'fa-child'					=> '&#xf1ae; fa-child',
				'fa-circle'					=> '&#xf111; fa-circle',
				'fa-circle-o'				=> '&#xf10c; fa-circle-o',
				'fa-circle-o-notch'			=> '&#xf1ce; fa-circle-o-notch',
				'fa-circle-thin'			=> '&#xf1db; fa-circle-thin',
				'fa-clipboard'				=> '&#xf0ea; fa-clipboard',
				'fa-clock-o'				=> '&#xf017; fa-clock-o',
				'fa-cloud'					=> '&#xf0c2; fa-cloud',
				'fa-cloud-download'			=> '&#xf0ed; fa-cloud-download',
				'fa-cloud-upload'			=> '&#xf0ee; fa-cloud-upload',
				'fa-cny'					=> '&#xf157; fa-cny',
				'fa-code'					=> '&#xf121; fa-code',
				'fa-code-fork'				=> '&#xf126; fa-code-fork',
				'fa-codepen'				=> '&#xf1cb; fa-codepen',
				'fa-coffee'					=> '&#xf0f4; fa-coffee',
				'fa-cog'					=> '&#xf013; fa-cog',
				'fa-cogs'					=> '&#xf085; fa-cogs',
				'fa-columns'				=> '&#xf0db; fa-columns',
				'fa-comment'				=> '&#xf075; fa-comment',
				'fa-comment-o'				=> '&#xf0e5; fa-comment-o',
				'fa-comments'				=> '&#xf086; fa-comments',
				'fa-comments-o'				=> '&#xf0e6; fa-comments-o',
				'fa-compass'				=> '&#xf14e; fa-compass',
				'fa-compress'				=> '&#xf066; fa-compress',
				'fa-copy'					=> '&#xf0c5; fa-copy',
				'fa-credit-card'			=> '&#xf09d; fa-credit-card',
				'fa-crop'					=> '&#xf125; fa-crop',
				'fa-crosshairs'				=> '&#xf05b; fa-crosshairs',
				'fa-css3'					=> '&#xf13c; fa-css3',
				'fa-cube'					=> '&#xf1b2; fa-cube',
				'fa-cubes'					=> '&#xf1b3; fa-cubes',
				'fa-cut'					=> '&#xf0c4; fa-cut',
				'fa-cutlery'				=> '&#xf0f5; fa-cutlery',
				'fa-dashboard'				=> '&#xf0e4; fa-dashboard',
				'fa-database'				=> '&#xf1c0; fa-database',
				'fa-dedent'					=> '&#xf03b; fa-dedent',
				'fa-delicious'				=> '&#xf1a5; fa-delicious',
				'fa-desktop'				=> '&#xf108; fa-desktop',
				'fa-deviantart'				=> '&#xf1bd; fa-deviantart',
				'fa-digg'					=> '&#xf1a6; fa-digg',
				'fa-dollar'					=> '&#xf155; fa-dollar',
				'fa-dot-circle-o'			=> '&#xf192; fa-dot-circle-o',
				'fa-download'				=> '&#xf019; fa-download',
				'fa-dribbble'				=> '&#xf17d; fa-dribbble',
				'fa-dropbox'				=> '&#xf16b; fa-dropbox',
				'fa-drupal'					=> '&#xf1a9; fa-drupal',
				'fa-edit'					=> '&#xf044; fa-edit',
				'fa-eject'					=> '&#xf052; fa-eject',
				'fa-ellipsis-h'				=> '&#xf141; fa-ellipsis-h',
				'fa-ellipsis-v'				=> '&#xf142; fa-ellipsis-v',
				'fa-empire'					=> '&#xf1d1; fa-empire',
				'fa-envelope'				=> '&#xf0e0; fa-envelope',
				'fa-envelope-o'				=> '&#xf003; fa-envelope-o',
				'fa-envelope-square'		=> '&#xf199; fa-envelope-square',
				'fa-eraser'					=> '&#xf12d; fa-eraser',
				'fa-eur'					=> '&#xf153; fa-eur',
				'fa-euro'					=> '&#xf153; fa-euro',
				'fa-exchange'				=> '&#xf0ec; fa-exchange',
				'fa-exclamation'			=> '&#xf12a; fa-exclamation',
				'fa-exclamation-circle'		=> '&#xf06a; fa-exclamation-circle',
				'fa-exclamation-triangle'	=> '&#xf071; fa-exclamation-triangle',
				'fa-expand'					=> '&#xf065; fa-expand',
				'fa-external-link'			=> '&#xf08e; fa-external-link',
				'fa-external-link-square'	=> '&#xf14c; fa-external-link-square',
				'fa-eye'					=> '&#xf06e; fa-eye',
				'fa-eye-slash'				=> '&#xf070; fa-eye-slash',
				'fa-facebook'				=> '&#xf09a; fa-facebook',
				'fa-facebook-square'		=> '&#xf082; fa-facebook-square',
				'fa-fast-backward'			=> '&#xf049; fa-fast-backward',
				'fa-fast-forward'			=> '&#xf050; fa-fast-forward',
				'fa-fax'					=> '&#xf1ac; fa-fax',
				'fa-female'					=> '&#xf182; fa-female',
				'fa-fighter-jet'			=> '&#xf0fb; fa-fighter-jet',
				'fa-file'					=> '&#xf15b; fa-file',
				'fa-file-archive-o'			=> '&#xf1c6; fa-file-archive-o',
				'fa-file-audio-o'			=> '&#xf1c7; fa-file-audio-o',
				'fa-file-code-o'			=> '&#xf1c9; fa-file-code-o',
				'fa-file-excel-o'			=> '&#xf1c3; fa-file-excel-o',
				'fa-file-image-o'			=> '&#xf1c5; fa-file-image-o',
				'fa-file-movie-o'			=> '&#xf1c8; fa-file-movie-o',
				'fa-file-o'					=> '&#xf016; fa-file-o',
				'fa-file-pdf-o'				=> '&#xf1c1; fa-file-pdf-o',
				'fa-file-photo-o'			=> '&#xf1c5; fa-file-photo-o',
				'fa-file-picture-o'			=> '&#xf1c5; fa-file-picture-o',
				'fa-file-powerpoint-o'		=> '&#xf1c4; fa-file-powerpoint-o',
				'fa-file-sound-o'			=> '&#xf1c7; fa-file-sound-o',
				'fa-file-text'				=> '&#xf15c; fa-file-text',
				'fa-file-text-o'			=> '&#xf0f6; fa-file-text-o',
				'fa-file-video-o'			=> '&#xf1c8; fa-file-video-o',
				'fa-file-word-o'			=> '&#xf1c2; fa-file-word-o',
				'fa-file-zip-o'				=> '&#xf1c6; fa-file-zip-o',
				'fa-files-o'				=> '&#xf0c5; fa-files-o',
				'fa-film'					=> '&#xf008; fa-film',
				'fa-filter'					=> '&#xf0b0; fa-filter',
				'fa-fire'					=> '&#xf06d; fa-fire',
				'fa-fire-extinguisher'		=> '&#xf134; fa-fire-extinguisher',
				'fa-flag'					=> '&#xf024; fa-flag',
				'fa-flag-checkered'			=> '&#xf11e; fa-flag-checkered',
				'fa-flag-o'					=> '&#xf11d; fa-flag-o',
				'fa-flash'					=> '&#xf0e7; fa-flash',
				'fa-flask'					=> '&#xf0c3; fa-flask',
				'fa-flickr'					=> '&#xf16e; fa-flickr',
				'fa-floppy-o'				=> '&#xf0c7; fa-floppy-o',
				'fa-folder'					=> '&#xf07b; fa-folder',
				'fa-folder-o'				=> '&#xf114; fa-folder-o',
				'fa-folder-open'			=> '&#xf07c; fa-folder-open',
				'fa-folder-open-o'			=> '&#xf115; fa-folder-open-o',
				'fa-font'					=> '&#xf031; fa-font',
				'fa-forward'				=> '&#xf04e; fa-forward',
				'fa-foursquare'				=> '&#xf180; fa-foursquare',
				'fa-frown-o'				=> '&#xf119; fa-frown-o',
				'fa-gamepad'				=> '&#xf11b; fa-gamepad',
				'fa-gavel'					=> '&#xf0e3; fa-gavel',
				'fa-gbp'					=> '&#xf154; fa-gbp',
				'fa-ge'					 	=> '&#xf1d1; fa-ge',
				'fa-gear'					=> '&#xf013; fa-gear',
				'fa-gears'					=> '&#xf085; fa-gears',
				'fa-gift'					=> '&#xf06b; fa-gift',
				'fa-git'					=> '&#xf1d3; fa-git',
				'fa-git-square'				=> '&#xf1d2; fa-git-square',
				'fa-github'					=> '&#xf09b; fa-github',
				'fa-github-alt'				=> '&#xf113; fa-github-alt',
				'fa-github-square'			=> '&#xf092; fa-github-square',
				'fa-gittip'					=> '&#xf184; fa-gittip',
				'fa-glass'					=> '&#xf000; fa-glass',
				'fa-globe'					=> '&#xf0ac; fa-globe',
				'fa-google'					=> '&#xf1a0; fa-google',
				'fa-google-plus'			=> '&#xf0d5; fa-google-plus',
				'fa-google-plus-square'		=> '&#xf0d4; fa-google-plus-square',
				'fa-graduation-cap'			=> '&#xf19d; fa-graduation-cap',
				'fa-group'					=> '&#xf0c0; fa-group',
				'fa-h-square'				=> '&#xf0fd; fa-h-square',
				'fa-hacker-news'			=> '&#xf1d4; fa-hacker-news',
				'fa-hand-o-down'			=> '&#xf0a7; fa-hand-o-down',
				'fa-hand-o-left'			=> '&#xf0a5; fa-hand-o-left',
				'fa-hand-o-right'			=> '&#xf0a4; fa-hand-o-right',
				'fa-hand-o-up'				=> '&#xf0a6; fa-hand-o-up',
				'fa-hdd-o'					=> '&#xf0a0; fa-hdd-o',
				'fa-header'					=> '&#xf1dc; fa-header',
				'fa-headphones'				=> '&#xf025; fa-headphones',
				'fa-heart'					=> '&#xf004; fa-heart',
				'fa-heart-o'				=> '&#xf08a; fa-heart-o',
				'fa-history'				=> '&#xf1da; fa-history',
				'fa-home'					=> '&#xf015; fa-home',
				'fa-hospital-o'				=> '&#xf0f8; fa-hospital-o',
				'fa-html5'					=> '&#xf13b; fa-html5',
				'fa-image'					=> '&#xf03e; fa-image',
				'fa-inbox'					=> '&#xf01c; fa-inbox',
				'fa-indent'					=> '&#xf03c; fa-indent',
				'fa-info'					=> '&#xf129; fa-info',
				'fa-info-circle'			=> '&#xf05a; fa-info-circle',
				'fa-inr'					=> '&#xf156; fa-inr',
				'fa-instagram'				=> '&#xf16d; fa-instagram',
				'fa-institution'			=> '&#xf19c; fa-institution',
				'fa-italic'					=> '&#xf033; fa-italic',
				'fa-joomla'					=> '&#xf1aa; fa-joomla',
				'fa-jpy'					=> '&#xf157; fa-jpy',
				'fa-jsfiddle'				=> '&#xf1cc; fa-jsfiddle',
				'fa-key'					=> '&#xf084; fa-key',
				'fa-keyboard-o'				=> '&#xf11c; fa-keyboard-o',
				'fa-krw'					=> '&#xf159; fa-krw',
				'fa-language'				=> '&#xf1ab; fa-language',
				'fa-laptop'					=> '&#xf109; fa-laptop',
				'fa-leaf'					=> '&#xf06c; fa-leaf',
				'fa-legal'					=> '&#xf0e3; fa-legal',
				'fa-lemon-o'				=> '&#xf094; fa-lemon-o',
				'fa-level-down'				=> '&#xf149; fa-level-down',
				'fa-level-up'				=> '&#xf148; fa-level-up',
				'fa-life-bouy'				=> '&#xf1cd; fa-life-bouy',
				'fa-life-ring'				=> '&#xf1cd; fa-life-ring',
				'fa-life-saver'				=> '&#xf1cd; fa-life-saver',
				'fa-lightbulb-o'			=> '&#xf0eb; fa-lightbulb-o',
				'fa-link'					=> '&#xf0c1; fa-link',
				'fa-linkedin'				=> '&#xf0e1; fa-linkedin',
				'fa-linkedin-square'		=> '&#xf08c; fa-linkedin-square',
				'fa-linux'					=> '&#xf17c; fa-linux',
				'fa-list'					=> '&#xf03a; fa-list',
				'fa-list-alt'				=> '&#xf022; fa-list-alt',
				'fa-list-ol'				=> '&#xf0cb; fa-list-ol',
				'fa-list-ul'				=> '&#xf0ca; fa-list-ul',
				'fa-location-arrow'			=> '&#xf124; fa-location-arrow',
				'fa-lock'					=> '&#xf023; fa-lock',
				'fa-long-arrow-down'		=> '&#xf175; fa-long-arrow-down',
				'fa-long-arrow-left'		=> '&#xf177; fa-long-arrow-left',
				'fa-long-arrow-right'		=> '&#xf178; fa-long-arrow-right',
				'fa-long-arrow-up'			=> '&#xf176; fa-long-arrow-up',
				'fa-magic'					=> '&#xf0d0; fa-magic',
				'fa-magnet'					=> '&#xf076; fa-magnet',
				'fa-mail-forward'			=> '&#xf064; fa-mail-forward',
				'fa-mail-reply'				=> '&#xf112; fa-mail-reply',
				'fa-mail-reply-all'			=> '&#xf122; fa-mail-reply-all',
				'fa-male'					=> '&#xf183; fa-male',
				'fa-map-marker'				=> '&#xf041; fa-map-marker',
				'fa-maxcdn'					=> '&#xf136; fa-maxcdn',
				'fa-medkit'					=> '&#xf0fa; fa-medkit',
				'fa-meh-o'					=> '&#xf11a; fa-meh-o',
				'fa-microphone'				=> '&#xf130; fa-microphone',
				'fa-microphone-slash'		=> '&#xf131; fa-microphone-slash',
				'fa-minus'					=> '&#xf068; fa-minus',
				'fa-minus-circle'			=> '&#xf056; fa-minus-circle',
				'fa-minus-square'			=> '&#xf146; fa-minus-square',
				'fa-minus-square-o'			=> '&#xf147; fa-minus-square-o',
				'fa-mobile'					=> '&#xf10b; fa-mobile',
				'fa-mobile-phone'			=> '&#xf10b; fa-mobile-phone',
				'fa-money'					=> '&#xf0d6; fa-money',
				'fa-moon-o'					=> '&#xf186; fa-moon-o',
				'fa-mortar-board'			=> '&#xf19d; fa-mortar-board',
				'fa-music'					=> '&#xf001; fa-music',
				'fa-navicon'				=> '&#xf0c9; fa-navicon',
				'fa-openid'					=> '&#xf19b; fa-openid',
				'fa-outdent'				=> '&#xf03b; fa-outdent',
				'fa-pagelines'				=> '&#xf18c; fa-pagelines',
				'fa-paper-plane'			=> '&#xf1d8; fa-paper-plane',
				'fa-paper-plane-o'			=> '&#xf1d9; fa-paper-plane-o',
				'fa-paperclip'				=> '&#xf0c6; fa-paperclip',
				'fa-paragraph'				=> '&#xf1dd; fa-paragraph',
				'fa-paste'					=> '&#xf0ea; fa-paste',
				'fa-pause'					=> '&#xf04c; fa-pause',
				'fa-paw'					=> '&#xf1b0; fa-paw',
				'fa-pencil'					=> '&#xf040; fa-pencil',
				'fa-pencil-square'			=> '&#xf14b; fa-pencil-square',
				'fa-pencil-square-o'		=> '&#xf044; fa-pencil-square-o',
				'fa-phone'					=> '&#xf095; fa-phone',
				'fa-phone-square'			=> '&#xf098; fa-phone-square',
				'fa-photo'					=> '&#xf03e; fa-photo',
				'fa-picture-o'				=> '&#xf03e; fa-picture-o',
				'fa-pied-piper'				=> '&#xf1a7; fa-pied-piper',
				'fa-pied-piper-alt'			=> '&#xf1a8; fa-pied-piper-alt',
				'fa-pied-piper-square'		=> '&#xf1a7; fa-pied-piper-square',
				'fa-pinterest'				=> '&#xf0d2; fa-pinterest',
				'fa-pinterest-square'		=> '&#xf0d3; fa-pinterest-square',
				'fa-plane'					=> '&#xf072; fa-plane',
				'fa-play'					=> '&#xf04b; fa-play',
				'fa-play-circle'			=> '&#xf144; fa-play-circle',
				'fa-play-circle-o'			=> '&#xf01d; fa-play-circle-o',
				'fa-plus'					=> '&#xf067; fa-plus',
				'fa-plus-circle'			=> '&#xf055; fa-plus-circle',
				'fa-plus-square'			=> '&#xf0fe; fa-plus-square',
				'fa-plus-square-o'			=> '&#xf196; fa-plus-square-o',
				'fa-power-off'				=> '&#xf011; fa-power-off',
				'fa-print'					=> '&#xf02f; fa-print',
				'fa-puzzle-piece'			=> '&#xf12e; fa-puzzle-piece',
				'fa-qq'						=> '&#xf1d6; fa-qq',
				'fa-qrcode'					=> '&#xf029; fa-qrcode',
				'fa-question'				=> '&#xf128; fa-question',
				'fa-question-circle'		=> '&#xf059; fa-question-circle',
				'fa-quote-left'				=> '&#xf10d; fa-quote-left',
				'fa-quote-right'			=> '&#xf10e; fa-quote-right',
				'fa-ra'						=> '&#xf1d0; fa-ra',
				'fa-random'					=> '&#xf074; fa-random',
				'fa-rebel'					=> '&#xf1d0; fa-rebel',
				'fa-recycle'				=> '&#xf1b8; fa-recycle',
				'fa-reddit'					=> '&#xf1a1; fa-reddit',
				'fa-reddit-square'			=> '&#xf1a2; fa-reddit-square',
				'fa-refresh'				=> '&#xf021; fa-refresh',
				'fa-renren'					=> '&#xf18b; fa-renren',
				'fa-reorder'				=> '&#xf0c9; fa-reorder',
				'fa-repeat'					=> '&#xf01e; fa-repeat',
				'fa-reply'					=> '&#xf112; fa-reply',
				'fa-reply-all'				=> '&#xf122; fa-reply-all',
				'fa-retweet'				=> '&#xf079; fa-retweet',
				'fa-rmb'					=> '&#xf157; fa-rmb',
				'fa-road'					=> '&#xf018; fa-road',
				'fa-rocket'					=> '&#xf135; fa-rocket',
				'fa-rotate-left'			=> '&#xf0e2; fa-rotate-left',
				'fa-rotate-right'			=> '&#xf01e; fa-rotate-right',
				'fa-rouble'					=> '&#xf158; fa-rouble',
				'fa-rss'					=> '&#xf09e; fa-rss',
				'fa-rss-square'				=> '&#xf143; fa-rss-square',
				'fa-rub'					=> '&#xf158; fa-rub',
				'fa-ruble'					=> '&#xf158; fa-ruble',
				'fa-rupee'					=> '&#xf156; fa-rupee',
				'fa-save'					=> '&#xf0c7; fa-save',
				'fa-scissors'				=> '&#xf0c4; fa-scissors',
				'fa-search'					=> '&#xf002; fa-search',
				'fa-search-minus'			=> '&#xf010; fa-search-minus',
				'fa-search-plus'			=> '&#xf00e; fa-search-plus',
				'fa-send'					=> '&#xf1d8; fa-send',
				'fa-send-o'					=> '&#xf1d9; fa-send-o',
				'fa-share'					=> '&#xf064; fa-share',
				'fa-share-alt'				=> '&#xf1e0; fa-share-alt',
				'fa-share-alt-square'		=> '&#xf1e1; fa-share-alt-square',
				'fa-share-square'			=> '&#xf14d; fa-share-square',
				'fa-share-square-o'			=> '&#xf045; fa-share-square-o',
				'fa-shield'					=> '&#xf132; fa-shield',
				'fa-shopping-cart'			=> '&#xf07a; fa-shopping-cart',
				'fa-sign-in'				=> '&#xf090; fa-sign-in',
				'fa-sign-out'				=> '&#xf08b; fa-sign-out',
				'fa-signal'					=> '&#xf012; fa-signal',
				'fa-sitemap'				=> '&#xf0e8; fa-sitemap',
				'fa-skype'					=> '&#xf17e; fa-skype',
				'fa-slack'					=> '&#xf198; fa-slack',
				'fa-sliders'				=> '&#xf1de; fa-sliders',
				'fa-smile-o'				=> '&#xf118; fa-smile-o',
				'fa-sort'					=> '&#xf0dc; fa-sort',
				'fa-sort-alpha-asc'			=> '&#xf15d; fa-sort-alpha-asc',
				'fa-sort-alpha-desc'		=> '&#xf15e; fa-sort-alpha-desc',
				'fa-sort-amount-asc'		=> '&#xf160; fa-sort-amount-asc',
				'fa-sort-amount-desc'		=> '&#xf161; fa-sort-amount-desc',
				'fa-sort-asc'				=> '&#xf0de; fa-sort-asc',
				'fa-sort-desc'				=> '&#xf0dd; fa-sort-desc',
				'fa-sort-down'				=> '&#xf0dd; fa-sort-down',
				'fa-sort-numeric-asc'		=> '&#xf162; fa-sort-numeric-asc',
				'fa-sort-numeric-desc'		=> '&#xf163; fa-sort-numeric-desc',
				'fa-sort-up'				=> '&#xf0de; fa-sort-up',
				'fa-soundcloud'				=> '&#xf1be; fa-soundcloud',
				'fa-space-shuttle'			=> '&#xf197; fa-space-shuttle',
				'fa-spinner'				=> '&#xf110; fa-spinner',
				'fa-spoon'					=> '&#xf1b1; fa-spoon',
				'fa-spotify'				=> '&#xf1bc; fa-spotify',
				'fa-square'					=> '&#xf0c8; fa-square',
				'fa-square-o'				=> '&#xf096; fa-square-o',
				'fa-stack-exchange'			=> '&#xf18d; fa-stack-exchange',
				'fa-stack-overflow'			=> '&#xf16c; fa-stack-overflow',
				'fa-star'					=> '&#xf005; fa-star',
				'fa-star-half'				=> '&#xf089; fa-star-half',
				'fa-star-half-empty'		=> '&#xf123; fa-star-half-empty',
				'fa-star-half-full'			=> '&#xf123; fa-star-half-full',
				'fa-star-half-o'			=> '&#xf123; fa-star-half-o',
				'fa-star-o'					=> '&#xf006; fa-star-o',
				'fa-steam'					=> '&#xf1b6; fa-steam',
				'fa-steam-square'			=> '&#xf1b7; fa-steam-square',
				'fa-step-backward'			=> '&#xf048; fa-step-backward',
				'fa-step-forward'			=> '&#xf051; fa-step-forward',
				'fa-stethoscope'			=> '&#xf0f1; fa-stethoscope',
				'fa-stop'					=> '&#xf04d; fa-stop',
				'fa-strikethrough'			=> '&#xf0cc; fa-strikethrough',
				'fa-stumbleupon'			=> '&#xf1a4; fa-stumbleupon',
				'fa-stumbleupon-circle'		=> '&#xf1a3; fa-stumbleupon-circle',
				'fa-subscript'				=> '&#xf12c; fa-subscript',
				'fa-suitcase'				=> '&#xf0f2; fa-suitcase',
				'fa-sun-o'					=> '&#xf185; fa-sun-o',
				'fa-superscript'			=> '&#xf12b; fa-superscript',
				'fa-support'				=> '&#xf1cd; fa-support',
				'fa-table'					=> '&#xf0ce; fa-table',
				'fa-tablet'					=> '&#xf10a; fa-tablet',
				'fa-tachometer'				=> '&#xf0e4; fa-tachometer',
				'fa-tag'					=> '&#xf02b; fa-tag',
				'fa-tags'					=> '&#xf02c; fa-tags',
				'fa-tasks'					=> '&#xf0ae; fa-tasks',
				'fa-taxi'					=> '&#xf1ba; fa-taxi',
				'fa-tencent-weibo'			=> '&#xf1d5; fa-tencent-weibo',
				'fa-terminal'				=> '&#xf120; fa-terminal',
				'fa-text-height'			=> '&#xf034; fa-text-height',
				'fa-text-width'				=> '&#xf035; fa-text-width',
				'fa-th'						=> '&#xf00a; fa-th',
				'fa-th-large'				=> '&#xf009; fa-th-large',
				'fa-th-list'				=> '&#xf00b; fa-th-list',
				'fa-thumb-tack'				=> '&#xf08d; fa-thumb-tack',
				'fa-thumbs-down'			=> '&#xf165; fa-thumbs-down',
				'fa-thumbs-o-down'			=> '&#xf088; fa-thumbs-o-down',
				'fa-thumbs-o-up'			=> '&#xf087; fa-thumbs-o-up',
				'fa-thumbs-up'				=> '&#xf164; fa-thumbs-up',
				'fa-ticket'					=> '&#xf145; fa-ticket',
				'fa-times'					=> '&#xf00d; fa-times',
				'fa-times-circle'			=> '&#xf057; fa-times-circle',
				'fa-times-circle-o'			=> '&#xf05c; fa-times-circle-o',
				'fa-tint'					=> '&#xf043; fa-tint',
				'fa-toggle-down'			=> '&#xf150; fa-toggle-down',
				'fa-toggle-left'			=> '&#xf191; fa-toggle-left',
				'fa-toggle-right'			=> '&#xf152; fa-toggle-right',
				'fa-toggle-up'				=> '&#xf151; fa-toggle-up',
				'fa-trash-o'				=> '&#xf014; fa-trash-o',
				'fa-tree'					=> '&#xf1bb; fa-tree',
				'fa-trello'					=> '&#xf181; fa-trello',
				'fa-trophy'					=> '&#xf091; fa-trophy',
				'fa-truck'					=> '&#xf0d1; fa-truck',
				'fa-try'					=> '&#xf195; fa-try',
				'fa-tumblr'					=> '&#xf173; fa-tumblr',
				'fa-tumblr-square'			=> '&#xf174; fa-tumblr-square',
				'fa-turkish-lira'			=> '&#xf195; fa-turkish-lira',
				'fa-twitter'				=> '&#xf099; fa-twitter',
				'fa-twitter-square'			=> '&#xf081; fa-twitter-square',
				'fa-umbrella'				=> '&#xf0e9; fa-umbrella',
				'fa-underline'				=> '&#xf0cd; fa-underline',
				'fa-undo'					=> '&#xf0e2; fa-undo',
				'fa-university'				=> '&#xf19c; fa-university',
				'fa-unlink'					=> '&#xf127; fa-unlink',
				'fa-unlock'					=> '&#xf09c; fa-unlock',
				'fa-unlock-alt'				=> '&#xf13e; fa-unlock-alt',
				'fa-unsorted'				=> '&#xf0dc; fa-unsorted',
				'fa-upload'					=> '&#xf093; fa-upload',
				'fa-usd'					=> '&#xf155; fa-usd',
				'fa-user'					=> '&#xf007; fa-user',
				'fa-user-md'				=> '&#xf0f0; fa-user-md',
				'fa-users'					=> '&#xf0c0; fa-users',
				'fa-video-camera'			=> '&#xf03d; fa-video-camera',
				'fa-vimeo-square'			=> '&#xf194; fa-vimeo-square',
				'fa-vine'					=> '&#xf1ca; fa-vine',
				'fa-vk'						=> '&#xf189; fa-vk',
				'fa-volume-down'			=> '&#xf027; fa-volume-down',
				'fa-volume-off'				=> '&#xf026; fa-volume-off',
				'fa-volume-up'				=> '&#xf028; fa-volume-up',
				'fa-warning'				=> '&#xf071; fa-warning',
				'fa-wechat'					=> '&#xf1d7; fa-wechat',
				'fa-weibo'					=> '&#xf18a; fa-weibo',
				'fa-weixin'					=> '&#xf1d7; fa-weixin',
				'fa-wheelchair'				=> '&#xf193; fa-wheelchair',
				'fa-windows'				=> '&#xf17a; fa-windows',
				'fa-won'					=> '&#xf159; fa-won',
				'fa-wordpress'				=> '&#xf19a; fa-wordpress',
				'fa-wrench'					=> '&#xf0ad; fa-wrench',
				'fa-xing'					=> '&#xf168; fa-xing',
				'fa-xing-square'			=> '&#xf169; fa-xing-square',
				'fa-yahoo'					=> '&#xf19e; fa-yahoo',
				'fa-yen'					=> '&#xf157; fa-yen',
				'fa-youtube'				=> '&#xf167; fa-youtube',
				'fa-youtube-play'			=> '&#xf16a; fa-youtube-play',
				'fa-youtube-square'			=> '&#xf166; fa-youtube-square'
			)
		);

		$this->settings = array(
			'path' => apply_filters('acf/helpers/get_path', __FILE__),
			'dir' => apply_filters('acf/helpers/get_dir', __FILE__),
			'version' => '1.2'
		);

		add_filter('acf/load_field', array( $this, 'maybe_enqueue_font_awesome' ) );

    	parent::__construct();
	}

	/*
	*  maybe_enqueue_font_awesome()
	*
	*  If Enqueue FA is set to true, enqueue it in the footer. We cannot enqueue in the header because wp_head has already been called
	*  
	*/

	function maybe_enqueue_font_awesome( $field )
	{
		if( 'font-awesome' == $field['type'] && $field['enqueue_fa'] ) {
			add_action( 'wp_footer', array( $this, 'frontend_enqueue_scripts' ) );
		}

		return $field;
	}

	/*
	*  create_options()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like bellow) to save extra data to the $field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field	- an array holding all the field's data
	*/

	function create_options($field)
	{
		// defaults?
		$field = array_merge($this->defaults, $field);

		// key is needed in the field names to correctly save the data
		$key = $field['name'];


		// Create Field Options HTML
		?>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("Default Icon", 'acf'); ?></label>
			</td>
			<td>
				<div class="fa-field-wrapper">
					<div class="fa-live-preview"></div>
					<?php

					do_action('acf/create_field', array(
						'type'    =>  'select',
						'name'    =>  'fields[' . $key . '][default_value]',
						'value'   =>  $field['default_value'],
						'class'	  =>  'fontawesome',
						'choices' =>  array_merge( array( 'null' => __("Select",'acf') ), $field['choices'] )
					));

					?>
				</div>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("Return Value",'acf'); ?></label>
				<p class="description"><?php _e("Specify the returned value on front end", 'acf'); ?></p>
			</td>
			<td>
				<?php 
				do_action('acf/create_field', array(
					'type'	=>	'radio',
					'name'	=>	'fields['.$key.'][save_format]',
					'value'	=>	$field['save_format'],
					'choices'	=>	array(
						'element'	=>	__("Icon Element",'acf'),
						'class'		=>	__("Icon Class",'acf'),
						'unicode'	=>	__("Icon Unicode",'acf'),
						'object'	=>	__("Icon Object",'acf'),
					),
					'layout'	=>	'horizontal',
				));
				?>
			</td>
		</tr>

		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("Allow Null?",'acf'); ?></label>
			</td>
			<td>
				<?php 
				do_action('acf/create_field', array(
					'type'	=>	'radio',
					'name'	=>	'fields['.$key.'][allow_null]',
					'value'	=>	$field['allow_null'],
					'choices'	=>	array(
						1	=>	__("Yes",'acf'),
						0	=>	__("No",'acf'),
					),
					'layout'	=>	'horizontal',
				));
				?>
			</td>
		</tr>

		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("Enqueue FontAwesome?",'acf'); ?></label>
				<p class="description"><?php _e("Set to 'Yes' to enqueue FA in the footer on any pages using this field.", 'acf'); ?></p>
			</td>
			<td>
				<?php 
				do_action('acf/create_field', array(
					'type'	=>	'radio',
					'name'	=>	'fields['.$key.'][enqueue_fa]',
					'value'	=>	$field['enqueue_fa'],
					'choices'	=>	array(
						1	=>	__("Yes",'acf'),
						0	=>	__("No",'acf'),
					),
					'layout'	=>	'horizontal',
				));
				?>
			</td>
		</tr>
		<?php

	}

	/*
	*  create_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function create_field( $field )
	{	
		if( 'object' == $field['save_format'] )
			$field['value'] = array( $field['value']->class );

		// value must be array
		if( !is_array($field['value']) )
		{
			// perhaps this is a default value with new lines in it?
			if( strpos($field['value'], "\n") !== false )
			{
				// found multiple lines, explode it
				$field['value'] = explode("\n", $field['value']);
			}
			else
			{
				$field['value'] = array( $field['value'] );
			}
		}
		
		// trim value
		$field['value'] = array_map('trim', $field['value']);
		
		// html
		echo '<div class="fa-field-wrapper">';
		echo '<div class="fa-live-preview"></div>';
		echo '<select id="' . $field['id'] . '" class="' . $field['class'] . ' fa-select2-field" name="' . $field['name'] . '" >';	
		
		// null
		if( $field['allow_null'] )
		{
			echo '<option value="null">- ' . __("Select",'acf') . ' -</option>';
		}
		
		// loop through values and add them as options
		if( is_array($field['choices']) )
		{
			foreach( $field['choices'] as $key => $value )
			{
				$selected = $this->find_selected( $key, $field['value'], $field['save_format'], $field['choices'] );
				echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
			}
		}

		echo '</select>';
		echo '</div>';
	}

	function find_selected( $needle, $haystack, $type, $choices )
	{
		switch( $type )
		{
			case 'object':
			case 'element':
				$search = array( '<i class="fa ', '"></i>' );
				$string = str_replace( $search, '', $haystack[0] );
				break;

			case 'class':
				$string = $haystack[0];
				break;
		}

		if( $string == $needle )
			return 'selected="selected"';

		return '';
	}

	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add css + javascript to assist your create_field() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function input_admin_enqueue_scripts()
	{
		// register acf scripts
		wp_enqueue_script('acf-input-font-awesome-select2', $this->settings['dir'] . 'js/select2/select2.min.js', array(), $this->settings['version']);
		wp_enqueue_script('acf-input-font-awesome-edit-input', $this->settings['dir'] . 'js/edit_input.js', array(), $this->settings['version']);
		wp_enqueue_style('acf-input-font-awesome-input', $this->settings['dir'] . 'css/input.css', array(), $this->settings['version']);
		wp_enqueue_style('acf-input-font-awesome-fa', $this->settings['dir'] . 'css/fontawesome.css', array(), $this->settings['version']);
		wp_enqueue_style('acf-input-font-awesome-select2-css', $this->settings['dir'] . 'css/select2.css', array(), $this->settings['version']);
	}

	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add css + javascript to assist your create_field_options() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function field_group_admin_enqueue_scripts()
	{
		// register acf scripts
		wp_enqueue_script('font-awesome-select2', $this->settings['dir'] . 'js/select2/select2.min.js', array(), $this->settings['version']);
		wp_enqueue_script('font-awesome-create-input', $this->settings['dir'] . 'js/create_input.js', array(), $this->settings['version']);
		wp_enqueue_style('acf-input-font-awesome-input', $this->settings['dir'] . 'css/input.css', array(), $this->settings['version']);
		wp_enqueue_style('acf-input-font-awesome-fa', $this->settings['dir'] . 'css/fontawesome.css', array(), $this->settings['version']);
		wp_enqueue_style('acf-input-font-awesome-select2-css', $this->settings['dir'] . 'css/select2.css', array(), $this->settings['version']);
	}

	/*
	*  frontend_enqueue_scripts()
	*
	*  This action is called in the wp_enqueue_scripts action on the front end.
	*  
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts
	*  @type	action
	*/

	function frontend_enqueue_scripts()
	{
		wp_register_style('font-awesome', $this->settings['dir'] . 'css/fontawesome.css', array(), $this->settings['version']);

		wp_enqueue_style(array(
			'font-awesome'
		));
	}

	/*
	*  load_value()
	*
	*  This filter is appied to the $value after it is loaded from the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value - the value found in the database
	*  @param	$post_id - the $post_id from which the value was loaded from
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$value - the value to be saved in te database
	*/

	function load_value($value, $post_id, $field)
	{
		switch( $field['save_format'] )
		{
			case 'object':
				$icon_unicode_string = $this->defaults['choices'][ $value ];
				$icon_unicode_arr = explode( ' ', $icon_unicode_string );
				$icon_unicode = $icon_unicode_arr[0];
				$value = (object) array(
						'unicode' => $icon_unicode,
						'class'	  => $value,
						'element' => '<i class="fa ' . $value . '"></i>'
					);
				break;

			case 'unicode':
				$icon_unicode_string = $this->defaults['choices'][ $value ];
				$icon_unicode_arr = explode( ' ', $icon_unicode_string );
				$value = $icon_unicode_arr[0];
				break;

			case 'element':
				$value = '<i class="fa ' . $value . '"></i>';
				break;
		}

		return $value;
	}

}

new acf_field_font_awesome();
