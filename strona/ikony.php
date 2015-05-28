<?php
function ikony ($folder)
{
	error_reporting(0);
	$pas = 1;
	$testt = file_exists('./dane/'.$_GET['id'].'/haslo.php'); 
	if ($testt)
	{
		$pas = 0;
		$wplik = fread(fopen('./dane/'.$_GET['id'].'/haslo.php', "r"), filesize('./dane/'.$_GET['id'].'/haslo.php'));
		if ($_POST['whaslo'] == $wplik) $pas = 1;
		if ($_COOKIE[$_GET['id']] == $wplik)$pas = 1;
	}
if ($pas == 1)
{
	//if ($_GET['usun'] == 1){rmdir($_GET['whatrm']);}
	//if ($_GET['usun'] == 2){unlink($_GET['whatrm']);}


	$test = strpos($folder, '.');
	if ($test === false)
	{
		$testt = strpos($folder, 'publiczne');
		if (($testt === false) or ($testt === 0) or ($_COOKIE['login'] == md5('entawyrp')) or ($_POST['haslo'] == 'entawyrp'))
		{	
			foreach(new DirectoryIterator('./dane/'.$folder.'') as $entry)
			{
				if(!$entry->isDot())
				{
					if (!strrpos($entry->getFilename(), '.'))
						echo '  <div class="ikona"><a href="prace.php?id='.$folder.'/'.$entry->getFilename().'&amp;name='.$entry->getFilename().'">
							<img src="./strona/images/ico/folder.png" alt=""/></a><br/><strong><a href="prace.php&amp;id='.$folder.'/'.$entry->getFilename().'&amp;name='.$entry->getFilename().'">
							'.$entry->getFilename().'</a><a href="prace.php?id='.$folder.'&amp;name='.$_GET['name'].'&nbsp;usuięto&nbsp;folder:&nbsp;'.$entry->getFilename().'&amp;usun=1&amp;whatrm=./dane/'.$folder.'/'.$entry->getFilename().'" style="color:green;">*</a></strong></div>';	
				}
			}	
			foreach(new DirectoryIterator('./dane/'.$folder.'') as $entry)
			{
				if(!$entry->isDir())
				{
					$sp = $entry->getFilename();
					$aq1 = strrpos($sp, '.');
					$aq2 = substr($sp,$aq1+1);
					if ($aq2 == 'c++' or $aq2 == 'cpp' or $aq2 == 'c' or $aq2 == 'htm' or $aq2 == 'html' or $aq2 == 'css' or $aq2 == 'sql') $prog=2;
					else $prog=0;
					if($prog==2) echo '<div class="ikona"><a href="strona/geshi.php?url=../dane/'.$folder.'/'.$entry->getFilename().'&lang='.$aq2.'"><img src="./strona/images/ico/';	
					else echo '<div class="ikona"><a href="./dane/'.$folder.'/'.$entry->getFilename().'"><img src="./strona/images/ico/';	
					if ($aq2 == 'txt') 
					{
						echo 'txt.png';
					}
					elseif ($aq2 == 'doc' or $aq2 == 'docx' or $aq2 == 'rtf') 
					{
						echo 'word.png';
					}
					elseif ($aq2 == 'ppt' or $aq2 == 'pptx') 
					{
						echo 'pp.png';
					}
					elseif ($aq2 == 'mp3') 
					{
						echo 'mp3.png';
					}
					elseif ($aq2 == 'pdf') 
					{
						echo 'pdf.png';
					}
					elseif ($aq2 == 'bat' or $aq2 == 'cmd') 
					{
						echo 'bat.png';
					}
					elseif ($aq2 == 'bmp') 
					{
						echo 'bmp.png';
					}
					elseif ($aq2 == 'psd') 
					{
						echo 'psd.png';
					}
					elseif ($aq2 == 'avi') 
					{
						echo 'avi.png';
					}
					elseif ($aq2 == 'mpg') 
					{
						echo 'mpg.png';
					}	
					elseif ($aq2 == 'mov') 
					{
						echo 'mov.png';
					}
					elseif ($aq2 == 'mp4') 
					{
						echo 'mp4.png';
					}
					elseif ($aq2 == 'wav') 
					{
						echo 'wav.png';
					}
					elseif ($aq2 == 'wmv') 
					{
						echo 'wmv.png';
					}
					elseif ($aq2 == 'xls' or $aq2 == 'xlsx') 
					{
						echo 'excel.png';
					}
					elseif ($aq2 == 'exe' or $aq2 == 'msi') 
					{
						echo 'exe.png';
					}
					elseif ($aq2 == 'xml') 
					{
						echo 'xml.png';
						$prog=2;
					}
					elseif ($aq2 == 'iso') 
					{
						echo 'iso.png';
					}	
					elseif ($aq2 == 'inf' or $aq2 == 'info') 
					{
						echo 'inf.png';
					}
					elseif ($aq2 == 'png') 
					{
						echo 'png.png';
					}	
					elseif ($aq2 == 'htm' or $aq2 == 'html') 
					{
						echo 'html.png';
						
					}	
					elseif ($aq2 == 'gif') 
					{
						echo 'gif.png';
					}	
					elseif ($aq2 == 'jpg' or $aq2 == 'jpeg') 
					{
						echo 'jpg.png';
					}
					elseif ($aq2 == 'rar' or $aq2 == 'tar' or $aq2 == 'gz') 
					{
						echo 'rar.png';
					}
					elseif ($aq2 == 'zip' or $aq2 == '7-zip') 
					{
						echo 'zip.png';
					}
					elseif ($aq2 == 'php') 
					{
						echo 'php.png';
						
					}	
					elseif ($aq2 == 'c') 
					{
						echo 'c.png';
						
					}	
					elseif ($aq2 == 'c++' or $aq2 == 'cpp') 
					{
						echo 'cpp.png';
						
					}	
					elseif ($aq2 == 'sql') 
					{
						echo 'sql.png';
						
					}	
					elseif ($aq2 == 'css') 
					{
						echo 'css.png';
					}	
					else
					{
						echo 'nie.png';
					}
					if($prog==2) echo '" alt=""/></a><br/><strong><a href="strona/geshi.php?url=../dane/'.$folder.'/'.$entry->getFilename().'&lang='.$aq2.'">'.$entry->getFilename().'</a></strong><a href="prace.php?id='.$folder.'&amp;name='.$_GET['name'].'&nbsp;usuięto&nbsp;plik:&nbsp;'.$entry->getFilename().'&amp;usun=2&amp;whatrm=./dane/'.$folder.'/'.$entry->getFilename().'" style="color:green;">*</a></div>';
					else echo '" alt=""/></a><br/><strong><a href="./dane/'.$folder.'/'.$entry->getFilename().'">'.$entry->getFilename().'</a></strong><a href="prace.php?id='.$folder.'&amp;name='.$_GET['name'].'&nbsp;usuięto&nbsp;plik:&nbsp;'.$entry->getFilename().'&amp;usun=2&amp;whatrm=./dane/'.$folder.'/'.$entry->getFilename().'" style="color:green;">*</a></div>';
					
				}
			}
		}
	}else echo'<p id="hacker">Wal sie ! Nie wlamiesz sie :)</p>';

}

	else 
	echo '
		<br><p id="titlepas">Katalog zabezpieczony, podaj hasło</p>
			<div id="cnt"><br>
			<form method="post" action="'.$phpself.'">
			<input type="password" name="whaslo" id="formpas" value=""/>
			</form>
		</div>';
}
?>
