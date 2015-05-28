<!DOCTYPE html>
	<head>
        <meta charset="windows-1250" />
        <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->     
        <link rel="stylesheet" href="strona/style.css" />
		<link rel="shortcut icon" href="strona/images/favi.png" />
        <style>
            article, aside, footer, header, nav, section{
                display: block;
            }
        </style>
        <title>Haks.pl</title>
	</head>

	<body>
	<?php
		include 'geshi/geshi.php';
		$language = $_GET['lang'];
		$dane = fread(fopen($_GET['url'], "r"), filesize($_GET['url']));
		echo '<pre>';
		$ddd = new GeSHi($dane, $language);
		echo $ddd->parse_code();
		echo '</pre>';
	?>
	</body>