 <?php 
	mysql_connect("localhost","login","haslo");
	@mysql_select_db(baza);
	$zapytanie = mysql_query('create table users (
		id int unsigned not null auto_increment primary key,
		login char(20),
		haslo varchar(64),
		email text,
		datareg int,
		datalast int,
		ranga int
		)');
		if($zapytanie)echo 'OK<br>';else echo 'NO<br>';
	$zapytanie = mysql_query('create table folders (
		id int unsigned not null auto_increment primary key,
		adres text,
		wlasciciel text,
		dostep int,
		text text,
		haslo text
		)');if($zapytanie)echo 'OK<br>';else echo 'NO<br>';
	$zapytanie = mysql_query('create table nowe (
		id int unsigned not null auto_increment primary key,
		sciezka text,
		nazwa text
		)');if($zapytanie)echo 'OK<br>';else echo 'NO<br>';
		
	$zapytanie = mysql_query('create table pliki (
		id int unsigned not null auto_increment primary key,
		sciezka text,
		wlasciciel text
		)');if($zapytanie)echo 'OK<br>';else echo 'NO<br>';
		
	$zapytanie = mysql_query('create table licznik (
		id int unsigned not null auto_increment primary key,
		wartosc text
		)');if($zapytanie)echo 'OK<br>';else echo 'NO<br>';
	$zapytanie = mysql_query('create table logs (
		id int unsigned not null auto_increment primary key,
		ip text,
		data int,
		user text,
		post text,
		get text
		)');if($zapytanie)echo 'OK<br>';else echo 'NO<br>';
	$zapytanie = mysql_query('insert into users (login, haslo, email, datareg, datalast, ranga) values(
		"pawel33317", 
		"'.md5('haslo01k').'",
		"pawel33317@gmail.com", 
		"'.strtotime("now").'",
		"'.strtotime("now").'",
		"2"
		)');if($zapytanie)echo 'OK<br>';else echo 'NO<br>';
		
		
	$zapytanie = mysql_query('create table yout (
		id int unsigned not null auto_increment primary key,
		link text,
		nazwa text,
		typ text,
		data int,
		a1 text
		)');if($zapytanie)echo 'OK<br>';else echo 'NO<br>';
	mysql_close();
?>