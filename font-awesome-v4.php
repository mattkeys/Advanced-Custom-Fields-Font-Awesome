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
				'fa-glass' => __('&#xf000; fa-glass'),
				'fa-music' => __('&#xf001; fa-music'),
				'fa-search' => __('&#xf002; fa-search'),
				'fa-envelope-o' => __('&#xf003; fa-envelope-o'),
				'fa-heart' => __('&#xf004; fa-heart'),
				'fa-star' => __('&#xf005; fa-star'),
				'fa-star-o' => __('&#xf006; fa-star-o'),
				'fa-user' => __('&#xf007; fa-user'),
				'fa-film' => __('&#xf008; fa-film'),
				'fa-th-large' => __('&#xf009; fa-th-large'),
				'fa-th' => __('&#xf00a; fa-th'),
				'fa-th-list' => __('&#xf00b; fa-th-list'),
				'fa-check' => __('&#xf00c; fa-check'),
				'fa-times' => __('&#xf00d; fa-times'),
				'fa-search-plus' => __('&#xf00e; fa-search-plus'),
				'fa-search-minus' => __('&#xf010; fa-search-minus'),
				'fa-power-off' => __('&#xf011; fa-power-off'),
				'fa-signal' => __('&#xf012; fa-signal'),
				'fa-cog' => __('&#xf013; fa-cog'),
				'fa-trash-o' => __('&#xf014; fa-trash-o'),
				'fa-home' => __('&#xf015; fa-home'),
				'fa-file-o' => __('&#xf016; fa-file-o'),
				'fa-clock-o' => __('&#xf017; fa-clock-o'),
				'fa-road' => __('&#xf018; fa-road'),
				'fa-download' => __('&#xf019; fa-download'),
				'fa-arrow-circle-o-down' => __('&#xf01a; fa-arrow-circle-o-down'),
				'fa-arrow-circle-o-up' => __('&#xf01b; fa-arrow-circle-o-up'),
				'fa-inbox' => __('&#xf01c; fa-inbox'),
				'fa-play-circle-o' => __('&#xf01d; fa-play-circle-o'),
				'fa-repeat' => __('&#xf01e; fa-repeat'),
				'fa-refresh' => __('&#xf021; fa-refresh'),
				'fa-list-alt' => __('&#xf022; fa-list-alt'),
				'fa-lock' => __('&#xf023; fa-lock'),
				'fa-flag' => __('&#xf024; fa-flag'),
				'fa-headphones' => __('&#xf025; fa-headphones'),
				'fa-volume-off' => __('&#xf026; fa-volume-off'),
				'fa-volume-down' => __('&#xf027; fa-volume-down'),
				'fa-volume-up' => __('&#xf028; fa-volume-up'),
				'fa-qrcode' => __('&#xf029; fa-qrcode'),
				'fa-barcode' => __('&#xf02a; fa-barcode'),
				'fa-tag' => __('&#xf02b; fa-tag'),
				'fa-tags' => __('&#xf02c; fa-tags'),
				'fa-book' => __('&#xf02d; fa-book'),
				'fa-bookmark' => __('&#xf02e; fa-bookmark'),
				'fa-print' => __('&#xf02f; fa-print'),
				'fa-camera' => __('&#xf030; fa-camera'),
				'fa-font' => __('&#xf031; fa-font'),
				'fa-bold' => __('&#xf032; fa-bold'),
				'fa-italic' => __('&#xf033; fa-italic'),
				'fa-text-height' => __('&#xf034; fa-text-height'),
				'fa-text-width' => __('&#xf035; fa-text-width'),
				'fa-align-left' => __('&#xf036; fa-align-left'),
				'fa-align-center' => __('&#xf037; fa-align-center'),
				'fa-align-right' => __('&#xf038; fa-align-right'),
				'fa-align-justify' => __('&#xf039; fa-align-justify'),
				'fa-list' => __('&#xf03a; fa-list'),
				'fa-outdent' => __('&#xf03b; fa-outdent'),
				'fa-indent' => __('&#xf03c; fa-indent'),
				'fa-video-camera' => __('&#xf03d; fa-video-camera'),
				'fa-picture-o' => __('&#xf03e; fa-picture-o'),
				'fa-pencil' => __('&#xf040; fa-pencil'),
				'fa-map-marker' => __('&#xf041; fa-map-marker'),
				'fa-adjust' => __('&#xf042; fa-adjust'),
				'fa-tint' => __('&#xf043; fa-tint'),
				'fa-pencil-square-o' => __('&#xf044; fa-pencil-square-o'),
				'fa-share-square-o' => __('&#xf045; fa-share-square-o'),
				'fa-check-square-o' => __('&#xf046; fa-check-square-o'),
				'fa-arrows' => __('&#xf047; fa-arrows'),
				'fa-step-backward' => __('&#xf048; fa-step-backward'),
				'fa-fast-backward' => __('&#xf049; fa-fast-backward'),
				'fa-backward' => __('&#xf04a; fa-backward'),
				'fa-play' => __('&#xf04b; fa-play'),
				'fa-pause' => __('&#xf04c; fa-pause'),
				'fa-stop' => __('&#xf04d; fa-stop'),
				'fa-forward' => __('&#xf04e; fa-forward'),
				'fa-fast-forward' => __('&#xf050; fa-fast-forward'),
				'fa-step-forward' => __('&#xf051; fa-step-forward'),
				'fa-eject' => __('&#xf052; fa-eject'),
				'fa-chevron-left' => __('&#xf053; fa-chevron-left'),
				'fa-chevron-right' => __('&#xf054; fa-chevron-right'),
				'fa-plus-circle' => __('&#xf055; fa-plus-circle'),
				'fa-minus-circle' => __('&#xf056; fa-minus-circle'),
				'fa-times-circle' => __('&#xf057; fa-times-circle'),
				'fa-check-circle' => __('&#xf058; fa-check-circle'),
				'fa-question-circle' => __('&#xf059; fa-question-circle'),
				'fa-info-circle' => __('&#xf05a; fa-info-circle'),
				'fa-crosshairs' => __('&#xf05b; fa-crosshairs'),
				'fa-times-circle-o' => __('&#xf05c; fa-times-circle-o'),
				'fa-check-circle-o' => __('&#xf05d; fa-check-circle-o'),
				'fa-ban' => __('&#xf05e; fa-ban'),
				'fa-arrow-left' => __('&#xf060; fa-arrow-left'),
				'fa-arrow-right' => __('&#xf061; fa-arrow-right'),
				'fa-arrow-up' => __('&#xf062; fa-arrow-up'),
				'fa-arrow-down' => __('&#xf063; fa-arrow-down'),
				'fa-share' => __('&#xf064; fa-share'),
				'fa-expand' => __('&#xf065; fa-expand'),
				'fa-compress' => __('&#xf066; fa-compress'),
				'fa-plus' => __('&#xf067; fa-plus'),
				'fa-minus' => __('&#xf068; fa-minus'),
				'fa-asterisk' => __('&#xf069; fa-asterisk'),
				'fa-exclamation-circle' => __('&#xf06a; fa-exclamation-circle'),
				'fa-gift' => __('&#xf06b; fa-gift'),
				'fa-leaf' => __('&#xf06c; fa-leaf'),
				'fa-fire' => __('&#xf06d; fa-fire'),
				'fa-eye' => __('&#xf06e; fa-eye'),
				'fa-eye-slash' => __('&#xf070; fa-eye-slash'),
				'fa-exclamation-triangle' => __('&#xf071; fa-exclamation-triangle'),
				'fa-plane' => __('&#xf072; fa-plane'),
				'fa-calendar' => __('&#xf073; fa-calendar'),
				'fa-random' => __('&#xf074; fa-random'),
				'fa-comment' => __('&#xf075; fa-comment'),
				'fa-magnet' => __('&#xf076; fa-magnet'),
				'fa-chevron-up' => __('&#xf077; fa-chevron-up'),
				'fa-chevron-down' => __('&#xf078; fa-chevron-down'),
				'fa-retweet' => __('&#xf079; fa-retweet'),
				'fa-shopping-cart' => __('&#xf07a; fa-shopping-cart'),
				'fa-folder' => __('&#xf07b; fa-folder'),
				'fa-folder-open' => __('&#xf07c; fa-folder-open'),
				'fa-arrows-v' => __('&#xf07d; fa-arrows-v'),
				'fa-arrows-h' => __('&#xf07e; fa-arrows-h'),
				'fa-bar-chart-o' => __('&#xf080; fa-bar-chart-o'),
				'fa-twitter-square' => __('&#xf081; fa-twitter-square'),
				'fa-facebook-square' => __('&#xf082; fa-facebook-square'),
				'fa-camera-retro' => __('&#xf083; fa-camera-retro'),
				'fa-key' => __('&#xf084; fa-key'),
				'fa-cogs' => __('&#xf085; fa-cogs'),
				'fa-comments' => __('&#xf086; fa-comments'),
				'fa-thumbs-o-up' => __('&#xf087; fa-thumbs-o-up'),
				'fa-thumbs-o-down' => __('&#xf088; fa-thumbs-o-down'),
				'fa-star-half' => __('&#xf089; fa-star-half'),
				'fa-heart-o' => __('&#xf08a; fa-heart-o'),
				'fa-sign-out' => __('&#xf08b; fa-sign-out'),
				'fa-linkedin-square' => __('&#xf08c; fa-linkedin-square'),
				'fa-thumb-tack' => __('&#xf08d; fa-thumb-tack'),
				'fa-external-link' => __('&#xf08e; fa-external-link'),
				'fa-sign-in' => __('&#xf090; fa-sign-in'),
				'fa-trophy' => __('&#xf091; fa-trophy'),
				'fa-github-square' => __('&#xf092; fa-github-square'),
				'fa-upload' => __('&#xf093; fa-upload'),
				'fa-lemon-o' => __('&#xf094; fa-lemon-o'),
				'fa-phone' => __('&#xf095; fa-phone'),
				'fa-square-o' => __('&#xf096; fa-square-o'),
				'fa-bookmark-o' => __('&#xf097; fa-bookmark-o'),
				'fa-phone-square' => __('&#xf098; fa-phone-square'),
				'fa-twitter' => __('&#xf099; fa-twitter'),
				'fa-facebook' => __('&#xf09a; fa-facebook'),
				'fa-github' => __('&#xf09b; fa-github'),
				'fa-unlock' => __('&#xf09c; fa-unlock'),
				'fa-credit-card' => __('&#xf09d; fa-credit-card'),
				'fa-rss' => __('&#xf09e; fa-rss'),
				'fa-hdd-o' => __('&#xf0a0; fa-hdd-o'),
				'fa-bullhorn' => __('&#xf0a1; fa-bullhorn'),
				'fa-bell' => __('&#xf0f3; fa-bell'),
				'fa-certificate' => __('&#xf0a3; fa-certificate'),
				'fa-hand-o-right' => __('&#xf0a4; fa-hand-o-right'),
				'fa-hand-o-left' => __('&#xf0a5; fa-hand-o-left'),
				'fa-hand-o-up' => __('&#xf0a6; fa-hand-o-up'),
				'fa-hand-o-down' => __('&#xf0a7; fa-hand-o-down'),
				'fa-arrow-circle-left' => __('&#xf0a8; fa-arrow-circle-left'),
				'fa-arrow-circle-right' => __('&#xf0a9; fa-arrow-circle-right'),
				'fa-arrow-circle-up' => __('&#xf0aa; fa-arrow-circle-up'),
				'fa-arrow-circle-down' => __('&#xf0ab; fa-arrow-circle-down'),
				'fa-globe' => __('&#xf0ac; fa-globe'),
				'fa-wrench' => __('&#xf0ad; fa-wrench'),
				'fa-tasks' => __('&#xf0ae; fa-tasks'),
				'fa-filter' => __('&#xf0b0; fa-filter'),
				'fa-briefcase' => __('&#xf0b1; fa-briefcase'),
				'fa-arrows-alt' => __('&#xf0b2; fa-arrows-alt'),
				'fa-users' => __('&#xf0c0; fa-users'),
				'fa-link' => __('&#xf0c1; fa-link'),
				'fa-cloud' => __('&#xf0c2; fa-cloud'),
				'fa-flask' => __('&#xf0c3; fa-flask'),
				'fa-scissors' => __('&#xf0c4; fa-scissors'),
				'fa-files-o' => __('&#xf0c5; fa-files-o'),
				'fa-paperclip' => __('&#xf0c6; fa-paperclip'),
				'fa-floppy-o' => __('&#xf0c7; fa-floppy-o'),
				'fa-square' => __('&#xf0c8; fa-square'),
				'fa-bars' => __('&#xf0c9; fa-bars'),
				'fa-list-ul' => __('&#xf0ca; fa-list-ul'),
				'fa-list-ol' => __('&#xf0cb; fa-list-ol'),
				'fa-strikethrough' => __('&#xf0cc; fa-strikethrough'),
				'fa-underline' => __('&#xf0cd; fa-underline'),
				'fa-table' => __('&#xf0ce; fa-table'),
				'fa-magic' => __('&#xf0d0; fa-magic'),
				'fa-truck' => __('&#xf0d1; fa-truck'),
				'fa-pinterest' => __('&#xf0d2; fa-pinterest'),
				'fa-pinterest-square' => __('&#xf0d3; fa-pinterest-square'),
				'fa-google-plus-square' => __('&#xf0d4; fa-google-plus-square'),
				'fa-google-plus' => __('&#xf0d5; fa-google-plus'),
				'fa-money' => __('&#xf0d6; fa-money'),
				'fa-caret-down' => __('&#xf0d7; fa-caret-down'),
				'fa-caret-up' => __('&#xf0d8; fa-caret-up'),
				'fa-caret-left' => __('&#xf0d9; fa-caret-left'),
				'fa-caret-right' => __('&#xf0da; fa-caret-right'),
				'fa-columns' => __('&#xf0db; fa-columns'),
				'fa-sort' => __('&#xf0dc; fa-sort'),
				'fa-sort-asc' => __('&#xf0dd; fa-sort-asc'),
				'fa-sort-desc' => __('&#xf0de; fa-sort-desc'),
				'fa-envelope' => __('&#xf0e0; fa-envelope'),
				'fa-linkedin' => __('&#xf0e1; fa-linkedin'),
				'fa-undo' => __('&#xf0e2; fa-undo'),
				'fa-gavel' => __('&#xf0e3; fa-gavel'),
				'fa-tachometer' => __('&#xf0e4; fa-tachometer'),
				'fa-comment-o' => __('&#xf0e5; fa-comment-o'),
				'fa-comments-o' => __('&#xf0e6; fa-comments-o'),
				'fa-bolt' => __('&#xf0e7; fa-bolt'),
				'fa-sitemap' => __('&#xf0e8; fa-sitemap'),
				'fa-umbrella' => __('&#xf0e9; fa-umbrella'),
				'fa-clipboard' => __('&#xf0ea; fa-clipboard'),
				'fa-lightbulb-o' => __('&#xf0eb; fa-lightbulb-o'),
				'fa-exchange' => __('&#xf0ec; fa-exchange'),
				'fa-cloud-download' => __('&#xf0ed; fa-cloud-download'),
				'fa-cloud-upload' => __('&#xf0ee; fa-cloud-upload'),
				'fa-user-md' => __('&#xf0f0; fa-user-md'),
				'fa-stethoscope' => __('&#xf0f1; fa-stethoscope'),
				'fa-suitcase' => __('&#xf0f2; fa-suitcase'),
				'fa-bell-o' => __('&#xf0a2; fa-bell-o'),
				'fa-coffee' => __('&#xf0f4; fa-coffee'),
				'fa-cutlery' => __('&#xf0f5; fa-cutlery'),
				'fa-file-text-o' => __('&#xf0f6; fa-file-text-o'),
				'fa-building-o' => __('&#xf0f7; fa-building-o'),
				'fa-hospital-o' => __('&#xf0f8; fa-hospital-o'),
				'fa-ambulance' => __('&#xf0f9; fa-ambulance'),
				'fa-medkit' => __('&#xf0fa; fa-medkit'),
				'fa-fighter-jet' => __('&#xf0fb; fa-fighter-jet'),
				'fa-beer' => __('&#xf0fc; fa-beer'),
				'fa-h-square' => __('&#xf0fd; fa-h-square'),
				'fa-plus-square' => __('&#xf0fe; fa-plus-square'),
				'fa-angle-double-left' => __('&#xf100; fa-angle-double-left'),
				'fa-angle-double-right' => __('&#xf101; fa-angle-double-right'),
				'fa-angle-double-up' => __('&#xf102; fa-angle-double-up'),
				'fa-angle-double-down' => __('&#xf103; fa-angle-double-down'),
				'fa-angle-left' => __('&#xf104; fa-angle-left'),
				'fa-angle-right' => __('&#xf105; fa-angle-right'),
				'fa-angle-up' => __('&#xf106; fa-angle-up'),
				'fa-angle-down' => __('&#xf107; fa-angle-down'),
				'fa-desktop' => __('&#xf108; fa-desktop'),
				'fa-laptop' => __('&#xf109; fa-laptop'),
				'fa-tablet' => __('&#xf10a; fa-tablet'),
				'fa-mobile' => __('&#xf10b; fa-mobile'),
				'fa-circle-o' => __('&#xf10c; fa-circle-o'),
				'fa-quote-left' => __('&#xf10d; fa-quote-left'),
				'fa-quote-right' => __('&#xf10e; fa-quote-right'),
				'fa-spinner' => __('&#xf110; fa-spinner'),
				'fa-circle' => __('&#xf111; fa-circle'),
				'fa-reply' => __('&#xf112; fa-reply'),
				'fa-github-alt' => __('&#xf113; fa-github-alt'),
				'fa-folder-o' => __('&#xf114; fa-folder-o'),
				'fa-folder-open-o' => __('&#xf115; fa-folder-open-o'),
				'fa-smile-o' => __('&#xf118; fa-smile-o'),
				'fa-frown-o' => __('&#xf119; fa-frown-o'),
				'fa-meh-o' => __('&#xf11a; fa-meh-o'),
				'fa-gamepad' => __('&#xf11b; fa-gamepad'),
				'fa-keyboard-o' => __('&#xf11c; fa-keyboard-o'),
				'fa-flag-o' => __('&#xf11d; fa-flag-o'),
				'fa-flag-checkered' => __('&#xf11e; fa-flag-checkered'),
				'fa-terminal' => __('&#xf120; fa-terminal'),
				'fa-code' => __('&#xf121; fa-code'),
				'fa-reply-all' => __('&#xf122; fa-reply-all'),
				'fa-mail-reply-all' => __('&#xf122; fa-mail-reply-all'),
				'fa-star-half-o' => __('&#xf123; fa-star-half-o'),
				'fa-location-arrow' => __('&#xf124; fa-location-arrow'),
				'fa-crop' => __('&#xf125; fa-crop'),
				'fa-code-fork' => __('&#xf126; fa-code-fork'),
				'fa-chain-broken' => __('&#xf127; fa-chain-broken'),
				'fa-question' => __('&#xf128; fa-question'),
				'fa-info' => __('&#xf129; fa-info'),
				'fa-exclamation' => __('&#xf12a; fa-exclamation'),
				'fa-superscript' => __('&#xf12b; fa-superscript'),
				'fa-subscript' => __('&#xf12c; fa-subscript'),
				'fa-eraser' => __('&#xf12d; fa-eraser'),
				'fa-puzzle-piece' => __('&#xf12e; fa-puzzle-piece'),
				'fa-microphone' => __('&#xf130; fa-microphone'),
				'fa-microphone-slash' => __('&#xf131; fa-microphone-slash'),
				'fa-shield' => __('&#xf132; fa-shield'),
				'fa-calendar-o' => __('&#xf133; fa-calendar-o'),
				'fa-fire-extinguisher' => __('&#xf134; fa-fire-extinguisher'),
				'fa-rocket' => __('&#xf135; fa-rocket'),
				'fa-maxcdn' => __('&#xf136; fa-maxcdn'),
				'fa-chevron-circle-left' => __('&#xf137; fa-chevron-circle-left'),
				'fa-chevron-circle-right' => __('&#xf138; fa-chevron-circle-right'),
				'fa-chevron-circle-up' => __('&#xf139; fa-chevron-circle-up'),
				'fa-chevron-circle-down' => __('&#xf13a; fa-chevron-circle-down'),
				'fa-html5' => __('&#xf13b; fa-html5'),
				'fa-css3' => __('&#xf13c; fa-css3'),
				'fa-anchor' => __('&#xf13d; fa-anchor'),
				'fa-unlock-alt' => __('&#xf13e; fa-unlock-alt'),
				'fa-bullseye' => __('&#xf140; fa-bullseye'),
				'fa-ellipsis-h' => __('&#xf141; fa-ellipsis-h'),
				'fa-ellipsis-v' => __('&#xf142; fa-ellipsis-v'),
				'fa-rss-square' => __('&#xf143; fa-rss-square'),
				'fa-play-circle' => __('&#xf144; fa-play-circle'),
				'fa-ticket' => __('&#xf145; fa-ticket'),
				'fa-minus-square' => __('&#xf146; fa-minus-square'),
				'fa-minus-square-o' => __('&#xf147; fa-minus-square-o'),
				'fa-level-up' => __('&#xf148; fa-level-up'),
				'fa-level-down' => __('&#xf149; fa-level-down'),
				'fa-check-square' => __('&#xf14a; fa-check-square'),
				'fa-pencil-square' => __('&#xf14b; fa-pencil-square'),
				'fa-external-link-square' => __('&#xf14c; fa-external-link-square'),
				'fa-share-square' => __('&#xf14d; fa-share-square'),
				'fa-compass' => __('&#xf14e; fa-compass'),
				'fa-caret-square-o-down' => __('&#xf150; fa-caret-square-o-down'),
				'fa-caret-square-o-up' => __('&#xf151; fa-caret-square-o-up'),
				'fa-caret-square-o-right' => __('&#xf152; fa-caret-square-o-right'),
				'fa-eur' => __('&#xf153; fa-eur'),
				'fa-gbp' => __('&#xf154; fa-gbp'),
				'fa-usd' => __('&#xf155; fa-usd'),
				'fa-inr' => __('&#xf156; fa-inr'),
				'fa-jpy' => __('&#xf157; fa-jpy'),
				'fa-rub' => __('&#xf158; fa-rub'),
				'fa-krw' => __('&#xf159; fa-krw'),
				'fa-btc' => __('&#xf15a; fa-btc'),
				'fa-file' => __('&#xf15b; fa-file'),
				'fa-file-text' => __('&#xf15c; fa-file-text'),
				'fa-sort-alpha-asc' => __('&#xf15d; fa-sort-alpha-asc'),
				'fa-sort-alpha-desc' => __('&#xf15e; fa-sort-alpha-desc'),
				'fa-sort-amount-asc' => __('&#xf160; fa-sort-amount-asc'),
				'fa-sort-amount-desc' => __('&#xf161; fa-sort-amount-desc'),
				'fa-sort-numeric-asc' => __('&#xf162; fa-sort-numeric-asc'),
				'fa-sort-numeric-desc' => __('&#xf163; fa-sort-numeric-desc'),
				'fa-thumbs-up' => __('&#xf164; fa-thumbs-up'),
				'fa-thumbs-down' => __('&#xf165; fa-thumbs-down'),
				'fa-youtube-square' => __('&#xf166; fa-youtube-square'),
				'fa-youtube' => __('&#xf167; fa-youtube'),
				'fa-xing' => __('&#xf168; fa-xing'),
				'fa-xing-square' => __('&#xf169; fa-xing-square'),
				'fa-youtube-play' => __('&#xf16a; fa-youtube-play'),
				'fa-dropbox' => __('&#xf16b; fa-dropbox'),
				'fa-stack-overflow' => __('&#xf16c; fa-stack-overflow'),
				'fa-instagram' => __('&#xf16d; fa-instagram'),
				'fa-flickr' => __('&#xf16e; fa-flickr'),
				'fa-adn' => __('&#xf170; fa-adn'),
				'fa-bitbucket' => __('&#xf171; fa-bitbucket'),
				'fa-bitbucket-square' => __('&#xf172; fa-bitbucket-square'),
				'fa-tumblr' => __('&#xf173; fa-tumblr'),
				'fa-tumblr-square' => __('&#xf174; fa-tumblr-square'),
				'fa-long-arrow-down' => __('&#xf175; fa-long-arrow-down'),
				'fa-long-arrow-up' => __('&#xf176; fa-long-arrow-up'),
				'fa-long-arrow-left' => __('&#xf177; fa-long-arrow-left'),
				'fa-long-arrow-right' => __('&#xf178; fa-long-arrow-right'),
				'fa-apple' => __('&#xf179; fa-apple'),
				'fa-windows' => __('&#xf17a; fa-windows'),
				'fa-android' => __('&#xf17b; fa-android'),
				'fa-linux' => __('&#xf17c; fa-linux'),
				'fa-dribbble' => __('&#xf17d; fa-dribbble'),
				'fa-skype' => __('&#xf17e; fa-skype'),
				'fa-foursquare' => __('&#xf180; fa-foursquare'),
				'fa-trello' => __('&#xf181; fa-trello'),
				'fa-female' => __('&#xf182; fa-female'),
				'fa-male' => __('&#xf183; fa-male'),
				'fa-gittip' => __('&#xf184; fa-gittip'),
				'fa-sun-o' => __('&#xf185; fa-sun-o'),
				'fa-moon-o' => __('&#xf186; fa-moon-o'),
				'fa-archive' => __('&#xf187; fa-archive'),
				'fa-bug' => __('&#xf188; fa-bug'),
				'fa-vk' => __('&#xf189; fa-vk'),
				'fa-weibo' => __('&#xf18a; fa-weibo'),
				'fa-renren' => __('&#xf18b; fa-renren'),
				'fa-pagelines' => __('&#xf18c; fa-pagelines'),
				'fa-stack-exchange' => __('&#xf18d; fa-stack-exchange'),
				'fa-arrow-circle-o-right' => __('&#xf18e; fa-arrow-circle-o-right'),
				'fa-arrow-circle-o-left' => __('&#xf190; fa-arrow-circle-o-left'),
				'fa-caret-square-o-left' => __('&#xf191; fa-caret-square-o-left'),
				'fa-dot-circle-o' => __('&#xf192; fa-dot-circle-o'),
				'fa-wheelchair' => __('&#xf193; fa-wheelchair'),
				'fa-vimeo-square' => __('&#xf194; fa-vimeo-square'),
				'fa-try' => __('&#xf195; fa-try'),
				'fa-plus-square-o' => __('&#xf196; fa-plus-square-o')
			)
		);

		$this->settings = array(
			'path' => apply_filters('acf/helpers/get_path', __FILE__),
			'dir' => apply_filters('acf/helpers/get_dir', __FILE__),
			'version' => '1.1.1'
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
						'object'	=>	__("Icon Object",'acf'),
						'class'		=>	__("Icon Class",'acf'),
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
		// Note: This function can be removed if not used
		switch( $field['save_format'] )
		{
			case 'object':
				$icon_array = explode( ' ', $this->defaults['choices'][ $value ] );
				$value = (object) array(
						'unicode' => $icon_array[0],
						'class'	  => $icon_array[1],
						'element' => '<i class="fa ' . $icon_array[1] . '"></i>'
					);
				break;

			case 'unicode':
				$icon_array = explode( ' ', $this->defaults['choices'][ $value ] );
				$value = $icon_array[0];
				break;

			case 'element':
				$value = '<i class="fa ' . $value . '"></i>';
				break;
		}

		return $value;
	}

}

new acf_field_font_awesome();
