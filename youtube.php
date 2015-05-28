<?php 
	mysql_connect("localhost","login","haslo");
	@mysql_select_db(baza);

////////////////////////////////////////////////////////////////////////////
if (isset($_GET['ac']) && isset($_POST['youtnazwa']) ){
	$zapytanie = mysql_query('insert into yout (link, nazwa, typ, data) values(
	"'.$_POST['youtlink'].'", "'.$_POST['youtnazwa'].'","'.$_POST['youttyp'].'", "'.strtotime("now").'")');
}
////////////////////////////////////////////////////////////////////////////
	///////////LOGI///////////////////////////////////////////////////////
	$gety='';
	foreach($_GET as $ind => $wynik){
		$gety.=$ind.' === '.$wynik.' ********** ';
	}
	$posty='';
	foreach($_POST as $ind => $wynik){
		$posty.=$ind.' === '.$wynik.' ********** ';
	}
	$zapytanie = mysql_query('insert into logs (ip, data, user, post, get) values(
	"'.$_SERVER['REMOTE_ADDR'].'", "'.strtotime("now").'","'.@$_COOKIE['login'].'", "'.$posty.'","'.$gety.'")');
	/////////////////////////////////////////////////////////////////////////
$tmp = mysql_fetch_array(mysql_query('select * from folders where adres="'.@$_GET['id'].'"'));
if(@$_POST['haslof']==@$tmp[haslo]){@setcookie(@$tmp['adres'], @$tmp['haslo'], time()+3600*24*7);}
$GLOBALS['blad']='';
$GLOBALS['status']='';
$GLOBALS['wlasciciel']='';
$GLOBALS['nazwaus']='df ewrtg regd sfg sdf gfds  g';
if(isset($_POST['login'])){
	$user = new klasaUsera($_POST['login'], md5($_POST['haslo']));
}
elseif(isset($_COOKIE['login'])){
	$user = new klasaUsera($_COOKIE['login'], $_COOKIE['loginp']);
}
if(isset($_GET['id']) && $_GET['id']!=''){
	$sql = mysql_fetch_array(mysql_query('select * from folders where adres = "'.$_GET['id'].'"'));
	$GLOBALS['wlasciciel']=$sql['wlasciciel'];
}

if(!isset($_GET['id']) || @$_GET['id']=='') {
		$kid="pliki";} else{$kid=@$_GET['id'];
	}
if (isset($_POST['upload'])){
if(@$GLOBALS['status']==2 || @$GLOBALS['wlasciciel']==@$GLOBALS['nazwaus'] || @$_GET['id']=='' || !isset($_GET['id'])){
	@$plik_tmp = @$_FILES['plik']['tmp_name'];
	@$plik_nazwa = htmlspecialchars(@$_FILES['plik']['name']);
	@$plik_rozmiar = @$_FILES['plik']['size'];
	if(is_uploaded_file($plik_tmp)){
		if ($plik_rozmiar > 30000000){
			$GLOBALS['op']=1;
		}
		else{
			$i=0;
			do{
				$plik = $kid.'/'.$plik_nazwa;
				$test = file_exists($plik);
				if(!$test){
					$i=1;
					$rozszerzenie = explode(".",$plik_nazwa); $ilosc_czesci = count($rozszerzenie); $ostatnia_czesc = $ilosc_czesci - 1;
					$y=0;
					$plik_nazwa='';
					while($y<$ostatnia_czesc){
						$plik_nazwa=$plik_nazwa.$rozszerzenie[$y];
						if ($y<$ostatnia_czesc-1)$plik_nazwa=$plik_nazwa.'.';
						$y++;
					}
					if (($rozszerzenie[$ostatnia_czesc] == 'php' )or ($rozszerzenie[$ostatnia_czesc] == 'php5') or ($rozszerzenie[$ostatnia_czesc]=='php4'))$roz ='php';//$roz ='phpx'
					else $roz = $rozszerzenie[$ostatnia_czesc];
					$plik_nazwa=$plik_nazwa.'.'.$roz;
				}
				else{
					$rozszerzenie = explode(".",$plik_nazwa); $ilosc_czesci = count($rozszerzenie); $ostatnia_czesc = $ilosc_czesci - 1;
					$y=0;
					$plik_nazwa='';
					while($y<$ostatnia_czesc){
						$plik_nazwa=$plik_nazwa.$rozszerzenie[$y];
						if ($y<$ostatnia_czesc-1)$plik_nazwa=$plik_nazwa.'.';
						$y++;
					}
					if (($rozszerzenie[$ostatnia_czesc] == 'php' )or ($rozszerzenie[$ostatnia_czesc] == 'php5') or ($rozszerzenie[$ostatnia_czesc]=='php4'))$roz ='php';//$roz ='phpx'
					else $roz = $rozszerzenie[$ostatnia_czesc];
					$plik_nazwa=$plik_nazwa.'(n).'.$roz;
				}
			}while($i==0);
			move_uploaded_file($plik_tmp, $kid.'/'.$plik_nazwa);
			if($roz == 'php'){
				$fp = fopen($kid.'/'.$plik_nazwa, "r");
				$stareDane = fread($fp, filesize($kid.'/'.$plik_nazwa));
				fclose($fp);
				$noweDane  = "<?php die() ?>";
				$noweDane .= $stareDane;
				$fp = fopen($kid.'/'.$plik_nazwa, "w");
				fputs($fp, $noweDane);
				fclose($fp);
			}
			$zapytanie = mysql_query('insert into nowe (sciezka, nazwa) values(
										\''.$kid.'/'.$plik_nazwa.'\',
										\''.$plik_nazwa.'\')'); 
			$zapytaniee = mysql_query('insert into pliki (sciezka, wlasciciel) values("'.$kid.'/'.$plik_nazwa.'","'.$_COOKIE['login'].'")'); 
			$GLOBALS['op']=2;
		}
	}
	else $GLOBALS['op']=3;
}
else{$GLOBALS['blad']='BRAK UPRAWNIEŃ';}
}
//logi + wszystkie spost i sget
class klasaPliku{
	public $nazwaPliku;
	public $nazwaPlikuSkrocona;
	public $nazwaPlikuCzysta;
	public $rozszerzeniePliku;
	public $geshiPliku;
	public $htmlPliku;
	public function tworzNazwe($nazwaBezSpacji){	
		$this->nazwaPlikuCzysta = $nazwaBezSpacji;
		$this->nazwaPliku = str_replace(" ", "%20", $nazwaBezSpacji);
		if(strlen($this->nazwaPlikuCzysta)<=36){
			$this->nazwaPlikuSkrocona=$this->nazwaPlikuCzysta;
			if(!strstr(substr($this->nazwaPlikuCzysta, 0, 18), ' ')){
				$tmp=strlen($this->nazwaPlikuCzysta);
				while($tmp>18){
					$this->nazwaPlikuSkrocona[$tmp]=$this->nazwaPlikuSkrocona[$tmp-1];
					$tmp--;
				}
				$this->nazwaPlikuSkrocona[18]=' ';
			}
		}
		else{
			$this->nazwaPlikuSkrocona=substr($this->nazwaPlikuCzysta, 0, 34).'...';
			if(!strstr(substr($this->nazwaPlikuCzysta, 0, 18), ' ')){
				$tmp=32;
				while($tmp>18){
					$this->nazwaPlikuSkrocona[$tmp]=$this->nazwaPlikuSkrocona[$tmp-1];
					$tmp--;
				}
			}
			$this->nazwaPlikuSkrocona[18]=' ';
		}
		
	}
	public function tworzRozszerzenie(){	
		$tmp = strrpos($this->nazwaPliku, '.');
		$this->rozszerzeniePliku = substr($this->nazwaPliku,$tmp+1);
   }
   	public function tworzGeshi(){	
		if ($this->rozszerzeniePliku == 'c++' or $this->rozszerzeniePliku == 'cpp' or $this->rozszerzeniePliku == 'c' or 
			$this->rozszerzeniePliku == 'php' or $this->rozszerzeniePliku == 'htm' or $this->rozszerzeniePliku == 'html' or 
			$this->rozszerzeniePliku == 'css' or $this->rozszerzeniePliku == 'sql' or $this->rozszerzeniePliku == 'xml')
			{$this->geshiPliku=1;}
		else 
			{$this->geshiPliku=0;}
	}
   	public function tworzHtml($folder){	
		if($this->geshiPliku==1){
			$this->htmlPliku = '<div class="ikona"><a href="strona/geshi.php?url=../'.$folder.'/'.
								$this->nazwaPliku.'&amp;lang='.$this->rozszerzeniePliku.'"><img src="img/ico/';
		}
		else{
			$this->htmlPliku =  '<div class="ikona"><a href="'.$folder.'/'.$this->nazwaPliku.'"><img src="img/ico/';
		}
		if ($this->rozszerzeniePliku == 'txt') {$this->htmlPliku .= 'txt.png';}
		elseif ($this->rozszerzeniePliku == 'mp3') {$this->htmlPliku .= 'mp3.png';}
		elseif ($this->rozszerzeniePliku == 'pdf') {$this->htmlPliku .= 'pdf.png';}
		elseif ($this->rozszerzeniePliku == 'bmp') {$this->htmlPliku .= 'bmp.png';}
		elseif ($this->rozszerzeniePliku == 'psd') {$this->htmlPliku .= 'psd.png';}
		elseif ($this->rozszerzeniePliku == 'avi') {$this->htmlPliku .= 'avi.png';}
		elseif ($this->rozszerzeniePliku == 'mpg') {$this->htmlPliku .= 'mpg.png';}
		elseif ($this->rozszerzeniePliku == 'mov') {$this->htmlPliku .= 'mov.png';}
		elseif ($this->rozszerzeniePliku == 'mp4') {$this->htmlPliku .= 'mp4.png';}
		elseif ($this->rozszerzeniePliku == 'wav') {$this->htmlPliku .= 'wav.png';}
		elseif ($this->rozszerzeniePliku == 'wmv') {$this->htmlPliku .= 'wmv.png';}
		elseif ($this->rozszerzeniePliku == 'xml') {$this->htmlPliku .= 'xml.png';}
		elseif ($this->rozszerzeniePliku == 'iso') {$this->htmlPliku .= 'iso.png';}
		elseif ($this->rozszerzeniePliku == 'png') {$this->htmlPliku .= 'png.png';}
		elseif ($this->rozszerzeniePliku == 'gif') {$this->htmlPliku .= 'gif.png';}
		elseif ($this->rozszerzeniePliku == 'php' or $this->rozszerzeniePliku == 'phpx') {$this->htmlPliku .= 'php.png';}
		elseif ($this->rozszerzeniePliku == 'c')   {$this->htmlPliku .= 'c.png';}	
		elseif ($this->rozszerzeniePliku == 'sql') {$this->htmlPliku .= 'sql.png';}
		elseif ($this->rozszerzeniePliku == 'css') {$this->htmlPliku .= 'css.png';}
		elseif ($this->rozszerzeniePliku == 'bat' or $this->rozszerzeniePliku == 'cmd')   {$this->htmlPliku .= 'bat.png';}
		elseif ($this->rozszerzeniePliku == 'xls' or $this->rozszerzeniePliku == 'xlsx')  {$this->htmlPliku .= 'excel.png';}
		elseif ($this->rozszerzeniePliku == 'exe' or $this->rozszerzeniePliku == 'msi')   {$this->htmlPliku .= 'exe.png';}
		elseif ($this->rozszerzeniePliku == 'inf' or $this->rozszerzeniePliku == 'info')  {$this->htmlPliku .= 'inf.png';}
		elseif ($this->rozszerzeniePliku == 'htm' or $this->rozszerzeniePliku == 'html')  {$this->htmlPliku .= 'html.png';}	
		elseif ($this->rozszerzeniePliku == 'c++' or $this->rozszerzeniePliku == 'cpp')   {$this->htmlPliku .= 'cpp.png';}
		elseif ($this->rozszerzeniePliku == 'jpg' or $this->rozszerzeniePliku == 'jpeg')  {$this->htmlPliku .= 'jpg.png';}
		elseif ($this->rozszerzeniePliku == 'zip' or $this->rozszerzeniePliku == '7-zip') {$this->htmlPliku .= 'zip.png';}
		elseif ($this->rozszerzeniePliku == 'ppt' or $this->rozszerzeniePliku == 'pptx')  {$this->htmlPliku .= 'pp.png';}
		elseif ($this->rozszerzeniePliku == 'rar' or $this->rozszerzeniePliku == 'tar'  or $this->rozszerzeniePliku == 'gz') {$this->htmlPliku .= 'rar.png';}
		elseif ($this->rozszerzeniePliku == 'doc' or $this->rozszerzeniePliku == 'docx' or $this->rozszerzeniePliku == 'rtf'){$this->htmlPliku .= 'word.png';}
		else{$this->htmlPliku .= 'nie.png';}
		if(isset($_GET['id']) && $_GET['id']!=''){$tt='id='.@$_GET['id'].'&';$tr=$_GET['id'].'/';}
		else{$tt='';$tr='pliki/';}
		$tmp = mysql_fetch_array(mysql_query('select * from pliki where sciezka="'.$tr.$this->nazwaPlikuCzysta.'"'));
		

		if($this->geshiPliku==1){
			$this->htmlPliku .= '" alt="" title="'.$this->nazwaPlikuCzysta.'"/></a><br><strong><a href="strona/geshi.php?url=../'.$folder.'/'.$this->nazwaPliku.
								'&amp;lang='.$this->rozszerzeniePliku.'">'.$this->nazwaPlikuSkrocona.'</a></strong><br>';
			if (($GLOBALS['status']==2 || $tmp['wlasciciel']==$GLOBALS['nazwaus'] || $GLOBALS['wlasciciel']==$GLOBALS['nazwaus'] )){					
			$this->htmlPliku .= '<a title="usun" href="index.php?'.$tt.'op=up&plk='.$folder.'/'.$this->nazwaPliku.'" style="color:gold;font-weight:bold;"> &times;</a>';
			}
			if (($GLOBALS['status']==2 || $tmp['wlasciciel']==$GLOBALS['nazwaus'] || $GLOBALS['wlasciciel']==$GLOBALS['nazwaus'] )){	
			$this->htmlPliku .= ' | <a title="zmień nazwę" href="index.php?'.$tt.'znn=znn&nnazwa='.$this->nazwaPliku.'" style="color:gold;font-weight:bold;"> &hArr;</a>';
			}
			if (($GLOBALS['status']==2 || $tmp['wlasciciel']==$GLOBALS['nazwaus'] || $GLOBALS['wlasciciel']==$GLOBALS['nazwaus'] ) && (@$_GET['id']!='' && isset($_GET['id']))){
			$this->htmlPliku .= ' | <a title="przenieś wyżej" href="index.php?'.$tt.'op=ww&nmazwa='.$this->nazwaPliku.'" style="color:gold;font-weight:bold;"> &uarr;</a>';
			}
			if (($GLOBALS['status']==2 || $tmp['wlasciciel']==$GLOBALS['nazwaus'] || $GLOBALS['wlasciciel']==$GLOBALS['nazwaus'] )){	
			$this->htmlPliku .= ' | <a title="przenieś do katalogu" href="index.php?'.$tt.'nnn=nnn&nnnazwa='.$this->nazwaPliku.'" style="color:gold;font-weight:bold;"> &darr;</a>';
			}
			$this->htmlPliku .= ' </div>';
		}
		else{
			$this->htmlPliku .= '" alt="" title="'.$this->nazwaPlikuCzysta.'" /></a><br/><strong><a href="'.$folder.'/'.$this->nazwaPliku.'">'.
								$this->nazwaPlikuSkrocona.'</a></strong><br>';
			if (($GLOBALS['status']==2 || $tmp['wlasciciel']==$GLOBALS['nazwaus'] || $GLOBALS['wlasciciel']==$GLOBALS['nazwaus'] )){	
			$this->htmlPliku .= '<a title="usun" href="index.php?'.$tt.'op=up&plk='.$folder.'/'.$this->nazwaPliku.'" style="color:gold;font-weight:bold;"> &times;</a>';
			}
			if (($GLOBALS['status']==2 || $tmp['wlasciciel']==$GLOBALS['nazwaus'] || $GLOBALS['wlasciciel']==$GLOBALS['nazwaus'] )){	
			$this->htmlPliku .= ' | <a title="zmień nazwę" href="index.php?'.$tt.'znn=znn&nnazwa='.$this->nazwaPliku.'" style="color:gold;font-weight:bold;"> &hArr;</a>';
			}
			if (($GLOBALS['status']==2 || $tmp['wlasciciel']==$GLOBALS['nazwaus'] || $GLOBALS['wlasciciel']==$GLOBALS['nazwaus'] ) && (@$_GET['id']!='' && isset($_GET['id']))){
			$this->htmlPliku .= ' | <a title="przenieś wyżej" href="index.php?'.$tt.'op=ww&nmazwa='.$this->nazwaPliku.'" style="color:gold;font-weight:bold;"> &uarr;</a>';
			}
			if (($GLOBALS['status']==2 || $tmp['wlasciciel']==$GLOBALS['nazwaus'] || $GLOBALS['wlasciciel']==$GLOBALS['nazwaus'] )){	
			$this->htmlPliku .= ' | <a title="przenieś do katalogu" href="index.php?'.$tt.'nnn=nnn&nnnazwa='.$this->nazwaPliku.'" style="color:gold;font-weight:bold;"> &darr;</a>';
			}
			$this->htmlPliku .= '</div>';
		}
	}
}
class klasaFolderu{
	public $nazwaFolderu;
	public $nazwaFolderuCzysta;
	public $htmlFolderu;
	public $nazwaFolderuSkrocona;
	public $sciezkaFolderu;
	public function tworzNazwe($nazwaBezSpacji){	
		$this->nazwaFolderuCzysta = $nazwaBezSpacji;
		$this->nazwaFolderu = str_replace(" ", "%20", $nazwaBezSpacji);
		if(strlen($this->nazwaFolderuCzysta)<=36){
			$this->nazwaFolderuSkrocona=$this->nazwaFolderuCzysta;
			if(!strstr(substr($this->nazwaFolderuCzysta, 0, 18), ' ')){
				$tmp=strlen($this->nazwaFolderuCzysta);
				while($tmp>18){
					$this->nazwaFolderuSkrocona[$tmp]=$this->nazwaFolderuSkrocona[$tmp-1];
					$tmp--;
				}
				$this->nazwaFolderuSkrocona[18]=' ';
			}
		}
		else{
			$this->nazwaFolderuSkrocona=substr($this->nazwaFolderuCzysta, 0, 34).'...';
			if(!strstr(substr($this->nazwaFolderuCzysta, 0, 18), ' ')){
				$tmp=32;
				while($tmp>18){
					$this->nazwaFolderuSkrocona[$tmp]=$this->nazwaFolderuSkrocona[$tmp-1];
					$tmp--;
				}
			}
			$this->nazwaFolderuSkrocona[18]=' ';
		}
	}
   	public function tworzHtml($folder){	
		$this->sciezkaFolderu = $folder.'/'.$this->nazwaFolderuCzysta;
		if(isset($_GET['id']) && $_GET['id']!=''){$tt='id='.@$_GET['id'].'&';$tr=$_GET['id'].'/';}
		else{$tt='';$tr='pliki/';}
		$tmp = mysql_fetch_array(mysql_query('select * from folders where adres="'.$tr.$this->nazwaFolderuCzysta.'"'));
		
		$this->htmlFolderu = '<div class="ikona"><a href="index.php?id='.$folder.'/'.$this->nazwaFolderu.'&amp;name='.$this->nazwaFolderu.
						'"><img src="img/ico/folder.png" alt="" title="'.$this->nazwaFolderuCzysta.'" /></a><br/><strong><a title="'.$this->nazwaFolderuCzysta.'" href="index.php?id='.$folder.
						'/'.$this->nazwaFolderu.'&amp;name='.$this->nazwaFolderu.'">'.$this->nazwaFolderuSkrocona.'</a></strong><br>';
		if (($GLOBALS['status']==2 || $tmp['wlasciciel']==$GLOBALS['nazwaus'] || $GLOBALS['wlasciciel']==$GLOBALS['nazwaus'] )){	
			$this->htmlFolderu .= '<a title="usun" href="index.php?'.$tt.'op=uf&plk='.$folder.'/'.$this->nazwaFolderu.'" style="color:gold;font-weight:bold;"> &times;</a>';
		}
		if (($GLOBALS['status']==2 || $tmp['wlasciciel']==$GLOBALS['nazwaus'] || $GLOBALS['wlasciciel']==$GLOBALS['nazwaus'] )){	
			$this->htmlFolderu .= ' | <a title="zmień nazwę" href="index.php?'.$tt.'znn=znn&nnazwa='.$this->nazwaFolderu.'" style="color:gold;font-weight:bold;"> &hArr;</a>';
		}
		if (($GLOBALS['status']==2 || $tmp['wlasciciel']==$GLOBALS['nazwaus'] || $GLOBALS['wlasciciel']==$GLOBALS['nazwaus'] ) && (@$_GET['id']!='' && isset($_GET['id']))){	
			$this->htmlFolderu .= ' | <a title="przenieś wyżej" href="index.php?'.$tt.'op=ww&nmazwa='.$this->nazwaFolderu.'" style="color:gold;font-weight:bold;"> &uarr;</a>';
		}
		if (($GLOBALS['status']==2 || $tmp['wlasciciel']==$GLOBALS['nazwaus'] || $GLOBALS['wlasciciel']==$GLOBALS['nazwaus'] )){	
			$this->htmlFolderu .= ' | <a title="przenieś do katalogu" href="index.php?'.$tt.'nnn=nnn&nnnazwa='.$this->nazwaFolderu.'" style="color:gold;font-weight:bold;"> &darr;</a>';
		}
		$this->htmlFolderu .= '</div>';
	}
}
class klasaUsera{
	public $nazwa;
	public $ranga;
	public $rangaSlownie;
	public $czyZalogowany;
	public function __construct($name, $md5)
	{
		$tmp = mysql_fetch_array(mysql_query('select haslo from users where login="'.$name.'"'));
		if ($md5 == $tmp['haslo']){
			$this->czyZalogowany=1;
			$this->nazwa=$name;
			$GLOBALS['nazwaus']=$name;
			$this->ranga=mysql_fetch_array(mysql_query('select ranga from users where login="'.$this->nazwa.'"'));
			$this->ranga=$this->ranga['ranga'];
			if($this->ranga==0){$this->rangaSlownie='Zbanowany';$GLOBALS['status']=0;}
			elseif($this->ranga==1){$this->rangaSlownie='Użytkownik';$GLOBALS['status']=1;}
			elseif($this->ranga==2){$this->rangaSlownie='Administrator';$GLOBALS['status']=2;}
			@setcookie("login", $name, time()+3600*24*7);
			@setcookie("loginp", $md5, time()+3600*24*7);
		}
		else{
			$this->czyZalogowany=0;
		}
	} 
}
function usunFolder($folderr){
		if(isset($_GET['id']) && $_GET['id']!=''){$tt='id='.@$_GET['id'].'&';$tr=$_GET['id'].'/';}
		else{$tt='';$tr='pliki/';}
		$tmp = mysql_fetch_array(mysql_query('select * from folders where adres="'.$folderr.'"'));
		if (($GLOBALS['status']==2 || $tmp['wlasciciel']==$GLOBALS['nazwaus'] || $GLOBALS['wlasciciel']==$GLOBALS['nazwaus'] )){
	foreach(new DirectoryIterator($folderr) as  $entry){
		if($entry->isDir() && $entry->getFilename()!='..' && $entry->getFilename()!='.'){
			usunFolder($folderr.'/'.$entry->getFilename());
		}
		if(!$entry->isDir()){
			@unlink($folderr.'/'.$entry->getFilename());
			$usuwanie = mysql_query('delete from nowe where sciezka = "'.$folderr.'/'.$entry->getFilename().'"'); 
		}
	}
	$usuwanie = mysql_query('delete from folders where adres = "'.$folderr.'"'); 
	$usuwanie = mysql_query('delete from folders where adres like "'.$folderr.'%"'); 
	rmdir($folderr);}else{$GLOBALS['blad']='BRAK UPRAWNIEŃ';}
}
function usunPlik(){
		if(isset($_GET['id']) && $_GET['id']!=''){$tt='id='.@$_GET['id'].'&';$tr=$_GET['id'].'/';}
		else{$tt='';$tr='pliki/';}
		$tmp = mysql_fetch_array(mysql_query('select * from pliki where sciezka="'.$_GET['plk'].'"'));
		if (($GLOBALS['status']==2 || $tmp['wlasciciel']==$GLOBALS['nazwaus'] || $GLOBALS['wlasciciel']==$GLOBALS['nazwaus'] )){	
		$dousuniecia = str_replace("%20", " ", $_GET['plk']);
		@unlink($dousuniecia);
		$usuwanie = mysql_query('delete from nowe where sciezka = "'.$_GET['plk'].'"'); 
		$usuwanie = mysql_query('delete from pliki where sciezka = "'.$_GET['plk'].'"'); 
	}else{$GLOBALS['blad']='BRAK UPRAWNIEŃ';}
}
function zmienNazwe(){

	if(isset($_GET['id']) && $_GET['id']!=""){$nowy=$_GET['id'];$nowy.='/';}
	else{$nowy='pliki/';}
	$stary = $nowy;
	$temp=$_POST['ninazwa'];
	$stary.=$_GET['nnazwa'];
	while(file_exists($temp)){
		$temp.='(n)';
	}
	$nowy.=$temp;
		if(isset($_GET['id']) && $_GET['id']!=''){$tt='id='.@$_GET['id'].'&';$tr=$_GET['id'].'/';}
		else{$tt='';$tr='pliki/';}
		$tmp = mysql_fetch_array(mysql_query('select * from pliki where sciezka="'.$stary.'"'));
		if (($GLOBALS['status']==2 || $tmp['wlasciciel']==$GLOBALS['nazwaus'] || $GLOBALS['wlasciciel']==$GLOBALS['nazwaus'] )){	
	rename($stary,$nowy);
	if(!is_file($nowy)){
		$sql = mysql_query('update folders set adres = "'.$nowy.'" where adres = "'.$stary.'"');
	}
	else{
		$sql = mysql_query('update pliki set sciezka = "'.$nowy.'" where sciezka = "'.$stary.'"');
		$sql = mysql_query('update nowe set sciezka = "'.$nowy.'", nazwa = "'.$_POST['ninazwa'].'" where sciezka = "'.$stary.'"');
	}
	}else{$GLOBALS['blad']='BRAK UPRAWNIEŃ';}
}
function wyzej(){
	if(isset($_GET['id']) && $_GET['id']!=""){
		$temp=$_GET['id'];
		$adr=$temp;
		$adr.='/';
		$iter2=0;
		$katalog = '';
		$wyrazy = explode("/", $temp);
		while($iter2<count($wyrazy)-1){
			$katalog .=$wyrazy[$iter2];
				$katalog .='/';
			$iter2++;
		}
		$temp=$katalog;
		$temp.=$_GET['nmazwa'];
		$adr.=$_GET['nmazwa'];
		while(file_exists($temp)){
		if(!is_file($temp)){
				$temp=nnnnn($temp);
			}
			else{
				$temp.='(n)';
			}
		}
		if(isset($_GET['id']) && $_GET['id']!=''){$tt='id='.@$_GET['id'].'&';$tr=$_GET['id'].'/';}
		else{$tt='';$tr='pliki/';}
		$tmp = mysql_fetch_array(mysql_query('select * from pliki where sciezka="'.$adr.'"'));
		if (($GLOBALS['status']==2 || $tmp['wlasciciel']==$GLOBALS['nazwaus'] || $GLOBALS['wlasciciel']==$GLOBALS['nazwaus'] ) && (@$_GET['id']!='' && isset($_GET['id']))){
		rename($adr, $temp);   
		if(!is_file($temp)){
			$sql = mysql_query('update folders set adres = "'.$temp.'" where adres = "'.$adr.'"');
		}
		else{
			$sql = mysql_query('update pliki set sciezka = "'.$temp.'" where sciezka = "'.$adr.'"');
			$sql = mysql_query('update nowe set sciezka = "'.$temp.'", nazwa = "'.$_GET['nmazwa'].'" where sciezka = "'.$adr.'"');
		}
	}else{$GLOBALS['blad']='BRAK UPRAWNIEŃ';}
	}
}
function nizej(){
	if(isset($_GET['id']) && $_GET['id']!=""){$fol=$_GET['id'];$fol.='/';}
	else{$fol='pliki/';}
	$folnew=$fol;
	$fol.=$_GET['nnnazwa'];
	$folnew.=$_POST['nnnazwa'];
	$folnew.='/';
	$folnew.=$_GET['nnnazwa'];
	while(file_exists($folnew)){
		if(explode(".",$folnew)){
			$folnew=nnnnn($folnew);
		}
		else{
			$folnew.='(n)';
		}
	}
	if(isset($_GET['id']) && $_GET['id']!=''){$tt='id='.@$_GET['id'].'&';$tr=$_GET['id'].'/';}
	else{$tt='';$tr='pliki/';}
	$tmp = mysql_fetch_array(mysql_query('select * from pliki where sciezka="'.$fol.'"'));
	if (($GLOBALS['status']==2 || $tmp['wlasciciel']==$GLOBALS['nazwaus'] || $GLOBALS['wlasciciel']==$GLOBALS['nazwaus'] )){	
	rename($fol, $folnew);   
	if(!is_file($folnew)){
		$sql = mysql_query('update folders set adres = "'.$folnew.'" where adres = "'.$fol.'"');
	}
	else{
		$sql = mysql_query('update pliki set sciezka = "'.$folnew.'" where sciezka = "'.$fol.'"');
		$sql = mysql_query('update nowe set sciezka = "'.$folnew.'", nazwa = "'.$_GET['nnnazwa'].'" where sciezka = "'.$fol.'"');
	}
	}else{$GLOBALS['blad']='BRAK UPRAWNIEŃ';}
}
function zmienDostepFolderu(){//$_POST
	if(isset($_POST['fopas'])){
		$sql = mysql_fetch_array(mysql_query('select * from folders where adres = "'.$_GET['id'].'"'));
		if($sql){
			$sql = mysql_query('update folders set
					dostep = 	"'.$_POST['fodos'].'",
					text = 	"'.$_POST['foinf'].'",
					haslo = "'.$_POST['fopas'].'"
					where adres = "'.$_GET['id'].'"');
		}
		if(!$sql){
			$sql = mysql_query('INSERT INTO folders(dostep, text, haslo, adres) VALUES ("'.$_POST['fodos'].'", "'.$_POST['foinf'].'", "'.$_POST['fopas'].'", "'.$_GET['id'].'")');
		}
	}
}/////////
function utworzFolder(){
	if($GLOBALS['status']==2 || $GLOBALS['wlasciciel']==$GLOBALS['nazwaus'] || @$_GET['id']=='' || !isset($_GET['id'])){
	if(isset($_GET['id']) && $_GET['id']!=""){$nazwaFolderu=$_GET['id'].'/'.$_POST['foldeer'];}
	else{$nazwaFolderu='pliki/'.$_POST['foldeer'];}
	
	while(file_exists($nazwaFolderu)){
		$nazwaFolderu.='(n)';
	}
	mkdir($nazwaFolderu, 0700);
	chmod($nazwaFolderu, 0777);
	chmod('pliki', 0777);
	$sql = mysql_query('INSERT INTO folders(wlasciciel, adres) VALUES ("'.$_COOKIE['login'].'", "'.$nazwaFolderu.'")');
	}else{$GLOBALS['blad']='BRAK UPRAWNIEŃ';}
}
function pliki(){
	if(isset($_GET['id'])&&$_GET['id']!=''){$folder=$_GET['id'];$folder=str_replace('..','',$folder);}
	else {$folder="pliki";}
if(!file_exists($folder)){return '';}
	foreach(new DirectoryIterator($folder) as $num => $entry){
		if(!$entry->isDir()&& $entry->getFilename()!='..' && $entry->getFilename()!='.'){
			$tablicaPlikow[$num] = new klasaPliku;
			$tablicaPlikow[$num]->tworzNazwe($entry->getFilename());
			$tablicaPlikow[$num]->tworzRozszerzenie();
			$tablicaPlikow[$num]->tworzGeshi();
			$tablicaPlikow[$num]->tworzHtml($folder);
			@$kodPlikow .= $tablicaPlikow[$num]->htmlPliku;
		}
	}
	return @$kodPlikow;
}
function foldery(){
	if(isset($_GET['id']) && $_GET['id']!="" && !empty($_GET['id'])){$folder=$_GET['id'];$folder=str_replace('..','',$folder);}
	else {$folder="pliki";}	
							if(!file_exists($folder)){return 'brak takiego folderu';}
	foreach(new DirectoryIterator($folder) as $num => $entry){
		if($entry->isDir() && $entry->getFilename()!='..' && $entry->getFilename()!='.'){
			$tablicaFolderow[$num] = new klasaFolderu;
			$tablicaFolderow[$num]->tworzNazwe($entry->getFilename());
			$tablicaFolderow[$num]->tworzHtml($folder);
			@$kodFolderow .= $tablicaFolderow[$num]->htmlFolderu;
		}
	}
	return @$kodFolderow;
}	
function user(){
	if(isset($_GET['w'])){
		setcookie("login", 0, time()-1);
		setcookie("loginp", 0, time()-1);
		echo'<meta http-equiv=refresh content="0; url=index.php">';
	}
	elseif(isset($_POST['login'])){
		$user = new klasaUsera($_POST['login'], md5($_POST['haslo']));
	}
	elseif(isset($_COOKIE['login'])){
		$user = new klasaUsera($_COOKIE['login'], $_COOKIE['loginp']);
	}
	else{
		$user = new klasaUsera(1,1);
	}
	if ($user->czyZalogowany == 0){
		$plogowania='<form method="post" action="index.php">
						Login: <br><input type="text" name="login" class="formlog" value=""/><br>
						Hasło:<br><input type="password" name="haslo" class="formlog" value=""/>
						<input type="submit" name="z" id="formlogok" value="ok"/>
					</form>';
	}
	if ($user->czyZalogowany == 1){
		$plogowania='<div class="mennn">Zalogowany jako: <br><a href=""><img src="img/um.png" alt="">'.$user->nazwa.'</a><br>Ranga:<br><a href=""><img src="img/ranga.png" alt=""> '.$user->rangaSlownie.'</a>
			<div id="logg"><a href="index.php?w=1">Wyloguj </a> | <a href="">Panel</a></div>';
	}
	return $plogowania;
}
function nowe(){
	$tresc ='';
	$iter=0;
	$all = mysql_query('select * from nowe ORDER BY id DESC LIMIT 20');
	while(($linia=mysql_fetch_array($all)) && ($iter<10)){
		$iter2=0;
		$katalog = '';
		$wyrazy = explode("/", $linia['sciezka']);
		while($iter2<count($wyrazy)-1){
			$katalog .=$wyrazy[$iter2];
			if($iter2<count($wyrazy)-2){
				$katalog .='/';
			}
			$iter2++;
		}
		$i=0;
		if(strlen($linia['nazwa'])>=22){
			$krotka=substr($linia['nazwa'], 0, 21).'...';
		}
		else{
			$krotka = $linia['nazwa'];
		}
		$czyZabezpieczony = mysql_fetch_array(mysql_query('select * from folders where adres="'.$katalog.'"'));
		if($czyZabezpieczony['dostep']!=1 && $czyZabezpieczony['haslo']==''){
			$tresc.='<a href="'.$linia['sciezka'].'"><img src="img/st.png" alt="">'.$krotka.'</a><br>';
			$iter++;
		}
	}
	return $tresc;
}
function adres(){
	if(isset($_GET['id']) &&$_GET['id']!='' ){return '<a style="color:gold">'.$_GET['id'].'</a>';}
	else return '<a style="color:gold">Katalog Główny - wersja alpha</a>';
}
function nnnnn($plik_nazwa){
	$rozszerzenie = explode(".",$plik_nazwa); 
	$ilosc_czesci = count($rozszerzenie); 
	$ostatnia_czesc = $ilosc_czesci - 1;
	$y=0;
	$plik_nazwa='';
	while($y<$ostatnia_czesc){
		$plik_nazwa=$plik_nazwa.$rozszerzenie[$y];
		if ($y<$ostatnia_czesc-1)$plik_nazwa=$plik_nazwa.'.';
		$y++;
	}
	$roz = $rozszerzenie[$ostatnia_czesc];
	$plik_nazwa=$plik_nazwa.'(n).'.$roz;
	return $plik_nazwa;
}
function czyUser(){
	if(isset($_POST['login']))$name=$_POST['login']; 
	elseif(isset($_COOKIE['login']))$name=$_COOKIE['login'];
	else return 1;
	$tmp = mysql_fetch_array(mysql_query('select haslo from users where login="'.$name.'"'));
	if (md5(@$_POST['haslo']) == @$tmp['haslo'])return 0;
	elseif ($_COOKIE['loginp'] == $tmp['haslo'])return 0;
	else return 1;
}
function rejestracja(){
	if(czyUser()){
		$text ='<article><!--rejestracja-->
				<header><img src="img/rejestracja.png" alt="" /></header>
				<div class="bgup"></div>
				<section id="regg">';
		if(isset($_POST['rlogin'])){
			$login = htmlspecialchars($_POST['rlogin']);
			$password = htmlspecialchars($_POST['rhaslo']);
			$email = htmlspecialchars($_POST['remail']);
			$issetusr = mysql_fetch_array(mysql_query('select login from users where login="'.addslashes($login).'"'));
			if ($issetusr['login']){
				$text .='Podany użytkownik już istnieje.<br>';
			}
			else {
				if (strlen(trim($login)) != strlen($login))
					$text .= 'Na początku i końcu loginu nie może być spacji.<br>';
				elseif (strlen(trim($password)) != strlen($password))
					$text .= 'Na początku i końcu hasła nie może być spacji.<br>';
				elseif (strlen($password) < 4)
					$text .= 'Hasło musi posiadać minimum 4 znaki.<br>';
				elseif (strlen($login) < 3)
					$text .= 'Login musi posiadać minimum 3 znaki.<br>';
				elseif (!preg_match('/^[a-zA-Z0-9\.\-\_]+\@[a-zA-Z0-9\.\-\_]+\.[a-z]{2,4}$/D', $email))
					$text .= 'Podany e-mail nie istnieje. Popraw go.<br>';
				else{
					$zapytanie = mysql_query('insert into users (login, haslo, email, datareg, datalast, ranga) values(
					"'.addslashes($login).'",
					"'.md5($password).'",
					"'.addslashes($email).'", 
					"'.strtotime("now").'",
					"'.strtotime("now").'",
					"1"
					)');
				setcookie("login", $login, time()+3600*24*7);
				setcookie("loginp", md5($password), time()+3600*24*7);
				echo'<meta http-equiv=refresh content="0; url=index.php">';
				}
			}
			$text .='<form method="post" action="index.php">
						Login: <input type="text" name="rlogin" class="formreg" value=""/>
						Hasło: <input type="password" name="rhaslo" class="formreg" value=""/>
						E-mail: <input type="text" name="remail" class="formreg" value=""/>
						<input type="submit" name="haslo" id="formregok" value="zarejestruj"/>
					</form>
					<div class="clear"></div>
					</section>
					<div class="bgdown"></div>
					</article>';
			return $text;
		}	
	else
	return '<article><!--rejestracja-->
			<header><img src="img/rejestracja.png" alt="" /></header>
			<div class="bgup"></div>
			<section id="regg">
				<form method="post" action="index.php">
					Login: <input type="text" name="rlogin" class="formreg" value=""/>
					Hasło: <input type="password" name="rhaslo" class="formreg" value=""/>
					E-mail: <input type="text" name="remail" class="formreg" value=""/>
					<input type="submit" name="haslo" id="formregok" value="zarejestruj"/>
				</form>
				<div class="clear"></div>
			</section>
			<div class="bgdown"></div>
		</article>';
	}	
}
function aktualFolder(){
	if(isset($_GET['id']) && $_GET['id']!=''){
		$wyzszy = explode("/",$_GET['id']); 
		$ilosc_czesci = count($wyzszy); 
		if ($ilosc_czesci<3){
			return '<a style="color:yellow; padding-right:15px; float:right;" href=index.php>wyżej</a>';
		}
		else{
			$ostatnia_czesc = $ilosc_czesci - 1;
			$y=0;
			$newadres='';
			while($y<$ostatnia_czesc){
				$newadres=$newadres.$wyzszy[$y];
				if ($y<$ostatnia_czesc-1)$newadres=$newadres.'/';
				$y++;
			}
			return '<a style="color:yellow; padding-right:15px; float:right;" href=index.php?id='.$newadres.'>wyżej</a>';
		}
	}
}
function blad(){
	if($GLOBALS['blad']!=''){
		$zz= '<div class="bgup"></div><section class="uploadok">';
		$zz.= $GLOBALS['blad'].'</section><div class="bgdown"></div>';
		return $zz;
	}
}
function operacje(){
	switch (@$_GET['op']){
	case 'up': usunPlik(); break;
	case 'zn': zmienNazwe(); break;
	case 'uf': usunFolder($_GET['plk']); break;
	case 'cf': if($GLOBALS['status']==2 || $GLOBALS['wlasciciel']==$GLOBALS['nazwaus'] || $_GET['id']=='' || !isset($_GET['id']))utworzFolder(); break;
	case 'df': if($GLOBALS['status']==2 || $GLOBALS['wlasciciel']==$GLOBALS['nazwaus'])zmienDostepFolderu(); break;
	case 'ww': wyzej(); break;
	case 'nn': nizej(); break;
	}
}
operacje();
$GLOBALS['user']=user();
?>
<!DOCTYPE html>
<head>
	<meta charset="utf-8" />
	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->     
	<link rel="stylesheet" href="style.css" />
	<link rel="Shortcut icon" href="img/favi.png" />
	<meta name="keywords" content="programowanie, informatyle, serwery, linux" />
	<title>haks.pl</title>
</head>
<body>
	<header>
		<div id="bgupn"></div>
		<section><!--///////////LOGO-->
			<div id="topss">
				<a href="index.php"><img src="img/logo.png" alt="" /></a>
			</div>
			<div id="tops">
				<a href="" class="topsi"><img src="img/facebook.png" alt=""></a>
				<a href="mailto:pawel33317@gmail.com" class="topsi"><img src="img/mail.png" alt=""></a>
				<a href="index.php" class="topsi"><img src="img/home.png" alt=""></a>
			</div>
			<div class="clear"></div>
		</section>
		<div id="bgdownn"></div>
	</header>
	<nav>
		<div class="bgupm"></div>
		<section><!--///////////MENU-->
			<div class="menn"><img src="img/menu.png" alt=""> MENU</div>
			<a href="index.php"><img src="img/st.png" alt=""> Główna - pliki</a><br>
			<a href="youtube.php"><img src="img/st.png" alt=""> YouTube</a><br>
			<a href=""><img src="img/st.png" alt=""> Proxy</a><br>
			<a href=""><img src="img/st.png" alt=""> Galeria</a><br>
			<a href=""><img src="img/st.png" alt=""> Inne</a><br>
			<a href="mailto:pawel33317@gmail.com"><img src="img/st.png" alt=""> Kontakt</a><br>
			<div class="clear"></div>
		</section>
		<div class="bgdownm"></div>
		<!--///////////PANEL LOGOWANIA-->
		<div class="bgupm"></div>
		<section>
			<div class="menn"><img src="img/log.png" alt=""> PANEL LOGOWANIA</div>
			<div class="mennn">	
				<?php echo $GLOBALS['user'];?>	
			</div>
			<div class="clear"></div>
		</section>
		<div class="bgdownm"></div>
		<div class="bgupm"></div>
		<section><!--///////////NOWOŚCI-->
			<div class="menn"><img src="img/news.png" alt=""> NOWOŚCI</div>
<!------------------------------------------------------------------------------------------------------>
			<?php echo 'zmiana nowe na youtube z nazwy'/*nowe()*/;?>
			<div class="clear"></div>
		</section>
		<div class="bgdownm"></div>
	</nav>
	<div id="contents">
		<article>
			<header><img src="img/pliki.png" alt="" /></header>
			<div class="clear"></div>
			<!--/////////////////////POKAZUJE LINK JAK JESTESMY W PODFOLDERZE-->
			<div class="bgup"></div><section class="uploadok">
			<?php echo 'Typ: '.@$_GET['typ'].'';?>
			</section><div class="bgdown"></div>
			<?php echo blad();?> 
			<div class="bgup"></div>
			<!--/////////////////////PLIKI I FOLDERY-->
			<section class="uploadok" style="text-align:center; padding-bottom:15px;">	
			<?php 
				if (!isset($_GET['typ'])){
					echo'<ul>
						<li><a href="youtube.php?typ=muza">muza</a></li><br>
						<li><a href="youtube.php?typ=ciekawe">ciekawe</a></li><br>
						<li><a href="youtube.php?typ=kabarety">kabarety</a></li><br>
						<li><a href="youtube.php?typ=wpadki">wpadki</a></li><br>
						<li><a href="youtube.php?typ=filmy">filmy</a></li><br>
						</ul>';
				}
				else{
					$all = mysql_query('select * from yout where typ="'.$_GET['typ'].'" ORDER BY data DESC LIMIT 20');
					while($linia=mysql_fetch_array($all)){
						$teemp=$linia['link'];
						$teemp = str_replace("watch?v=", "embed/", $teemp);
						echo '<strong>'.$linia['nazwa'].'</strong><br><iframe width="560" height="315" src="'.$teemp.'" frameborder="0" allowfullscreen></iframe><br>';
					}
				}
			?>
				<!--/////////////////WYSWIETLANIE PLIKOW////CZY ZALOGOWANY HASLO BLOK INFORMACYJNY JEZELI INF ZABEZPIECZYC-->
			<div class="clear"></div>
			</section>
			<div class="bgdown"></div>
			<!--/////////////BLOK NIZEJ////////////////-->
			<?php //jezeli admin/*'.@$_GET['typ'].'&*/
			echo'<section class="uploadok">
				<form method="post" action="youtube.php?ac=dodaj">
					<strong>DODAJ: </strong>
					<strong>nazwa: </strong><input type="text" name="youtnazwa" value="" class="formreg"/>
					<strong>link: </strong><input type="text" name="youtlink" value="" class="formreg"/>
						<strong>typ: </strong><select name="youttyp" class="formreg">
							<option value="muza">muza</option>
							<option value="ciekawe">ciekawe</option>
							<option value="kabarety">kabarety</option>
							<option value="wpadki">wpadki</option>
							<option value="filmy">filmy</option>
						</select>
					<input type="submit" name="upload" id="formlogokup" value="Dodaj"/>
				</form><div class="clear"></div>
			</section>
			<div class="bgdown"></div>';?>

				
		</article>
		<!--///////////////////REJESTRACJA-->
		<?php echo rejestracja(); ?>
	</div>
	<div class="clear"></div>
	<footer id="finish"><!--stopka-->
		<p>Copyright &copy; 2013. Created by Paweł Czubak.<br>Valid <a href="http://validator.w3.org/check?uri=http%3A%2F%2Fhaks.pl%2Findex.php&amp;charset=%28detect+automatically%29&amp;doctype=Inline&amp;group=0">HTML 5</a> and <a href="http://jigsaw.w3.org/css-validator/validator?uri=http%3A%2F%2Fhaks.pl%2Findex.php&amp;profile=css3&amp;usermedium=all&amp;warning=1&amp;vextwarning=&amp;lang=pl-PL">CSS 3</a>.</p>
	</footer>
</body>
</html>