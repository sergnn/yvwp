<!DOCTYPE html>
<html>
<?	$site_url="http://" . $_SERVER["SERVER_NAME"] . "/";
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
?>
<head>
<meta charset="UTF-8">
<LINK TYPE="text/css" REL="stylesheet" HREF="<?echo $site_url;?>main.css">
<script type="text/javascript">
function Visibility()
{
document.getElementById('add1').style.display='none';
document.getElementById('add2').style.display='block';
//document.getElementById('txt').focus();
}
</script>
<title>YVWP admin panel</title>
</head>
<body><div id=wrapper><div id=content>
<?
$hostname="78.108.84.86";
$uname="u91689";
$pass="1q2w3e";
$db="b91689_yvwp";
mysql_connect($hostname, $uname, $pass);
mysql_select_db($db);
echo "<center><table bgcolor=#333333 >";
echo "<tr><td><a style=\"padding-right:20px\" href=\"" . $site_url . "admin/index.php?page=contacts\">[Редактировать контакты]</a>";
echo "<td><a style=\"padding-left:20px\" href=\"" . $site_url . "admin/index.php?page=galleries\">[Редактировать галереи]</a>";
echo "</table></center><br><br>";

//CONTACTS
if($_REQUEST["page"]=="contacts" || !isset($_REQUEST["page"])){
	if($_REQUEST["act"]=="update"){
		mysql_query("UPDATE contacts SET val = '" . $_REQUEST["cont"] . "'" );
		echo "<div style=\"color:lime\">Контакты успешно изменены</div><br>";
	}
	echo "Контакты:<br><br>";
	echo "<form method=\"POST\" action=\"" . $site_url . "admin/index.php\">";
	echo "<input type=hidden name=\"page\" value=\"contacts\">";
	echo "<input type=hidden name=\"act\" value=\"update\">";
	$res=mysql_query("SELECT * FROM contacts");
	while ($row=mysql_fetch_array($res)) { 
		echo "<textarea cols=40 rows=10 name=\"cont\">";
		echo $row["val"];
		echo "</textarea>";
	}
	echo "<br><input type=\"submit\" value=\"Изменить\">";
}

//GALLERIES

if($_REQUEST["page"]=="galleries"){
	if(is_numeric($_REQUEST["sub"])){
		//DELETE GAL
		if($_REQUEST["act"]=="del"){
			if(isset($_REQUEST["p"])){
				unlink($DOCUMENT_ROOT . "/" . strtolower($_REQUEST["p"]));
				$res=mysql_query("SELECT * FROM galleries WHERE id=" . $_REQUEST["sub"]);
				$row=mysql_fetch_array($res); 
				$dir=$DOCUMENT_ROOT . "/" . strtolower($row["name"]) . "/";
				include_once("./cxml.php");
				echo "<div style=\"color:lime\">Фото удалено</div><br>";
			}else{
				$res=mysql_query("SELECT * FROM galleries WHERE id=" . $_REQUEST["sub"]);
				while ($row=mysql_fetch_array($res)) { 
					unlink($DOCUMENT_ROOT . "/" . strtolower($row["name"]) . ".xml");
					$files=scandir($DOCUMENT_ROOT . "/" . strtolower($row["name"]));
					foreach($files as $f){
						if(is_file($DOCUMENT_ROOT . "/" . strtolower($row["name"]) . "/" . $f)){
						unlink($DOCUMENT_ROOT . "/" . strtolower($row["name"]) . "/" . $f);
						}
					}
					rmdir($DOCUMENT_ROOT . "/" . strtolower($row["name"]));
				}
				mysql_query("DELETE FROM galleries WHERE id=" . $_REQUEST["sub"]);
				echo "<div style=\"color:lime\">Галерея удалена</div><br>";
			}
		}
		//ADD GAL
		if($_REQUEST["act"]=="add"){
			$res=mysql_query("SELECT * FROM galleries WHERE id=" . $_REQUEST["sub"]);
			$row=mysql_fetch_array($res); 
			$files=scandir($DOCUMENT_ROOT . "/" . strtolower($row["name"]) . "/");
			$fname=array_pop($files);
			$fname=explode(".", $fname);
			$fname=$fname[0];
			$fname++;
			copy($_FILES["file"]["tmp_name"],$DOCUMENT_ROOT . "/" . strtolower($row["name"]) . "/" . $fname . ".jpg");
			$dir=$DOCUMENT_ROOT . "/" . strtolower($row["name"]) . "/";
			include_once("./cxml.php");
			echo "<div style=\"color:lime\">Файл добавлен</div><br>";
		}
		//PRINT GAL
		$res=mysql_query("SELECT * FROM galleries WHERE id=" . $_REQUEST["sub"]);
		while ($row=mysql_fetch_array($res)) { 
			echo "<h2><b>" . $row["name"] . "</b></h2><br>";
			$str = file_get_contents($site_url . strtolower($row["name"]) . ".xml");
			$xml = new SimpleXMLElement($str);
			$i=0;
			foreach ($xml->image as $image){
				$i++;
				echo "<div style=\"position:relative; max-height:100px;display:inline-block; text-align:right; \" onmouseover=\"document.getElementById('del" . $i . "').style.display='inline-block';\" onmouseout=\"document.getElementById('del" . $i . "').style.display='none';\">";
				echo "<img  height=100 src=\"" . $site_url . $image->filename . "\">&nbsp;";
				echo "<a href=\"" . $site_url . "admin/index.php?page=galleries&act=del&p=" . $image->filename . "&sub=" . $_REQUEST["sub"] . "\" id=del" . $i . " style=\"padding: 0; margin: 0; bottom:0px; display:none; position:absolute; right:10px;\"><img src=\"delete.png\" alt=\"[x]\"></a>";
				echo "</div>";
				if($i%5==0) echo "<br>";
			}
		}
		echo "<br><br><br>\r\n<form enctype=\"multipart/form-data\" method=\"POST\" action=\"" . $site_url . "admin/index.php\">";
		echo "\r\n<input type=hidden name=\"page\" value=\"galleries\">";
		echo "\r\n<input type=hidden name=\"sub\" value=\"" . $_REQUEST["sub"] . "\">";
		echo "\r\n<input type=hidden name=\"act\" value=\"add\">";
		echo "\r\n<input type=file name=\"file\">";
		echo "\r\n<br><input type=submit value=\"Загрузить файл\">";
		echo "\r\n</form>";
	}else{
		if($_REQUEST["act"]=="add"){
			$output = fopen($DOCUMENT_ROOT . "/" . strtolower($_REQUEST["name"]) . ".xml","wb");
			fwrite($output,'<?xml version="1.0" encoding="UTF-8"?>
');
			fwrite($output,'<gallery title="" frameColor="0xFFFFFF" frameWidth="0" imagePadding="20" displayTime="6">
');
			fwrite($output,'</gallery>');
			fclose($output);
			mkdir($DOCUMENT_ROOT . "/" . strtolower($_REQUEST["name"]));
			mysql_query("INSERT INTO galleries (id,name) VALUES('','" . htmlspecialchars($_REQUEST["name"]) . "');");
		}
		$res=mysql_query("SELECT * FROM galleries");
		echo "<h2><b>Галереи</b></h2><br>";
		while ($row=mysql_fetch_array($res)) { 
			echo "<a href=\"".$site_url."admin/index.php?page=galleries&sub=" . $row["id"] . "\">" . $row["name"] ."</a>";
			echo "&nbsp;<a href=\"".$site_url."admin/index.php?page=galleries&sub=" . $row["id"] . "&act=del\" style=\"color:red\">[x]</a>";
			echo "<br>";
		}
		echo "\r\n<br><a id=add1 onCLick=\"Visibility()\" style=\"color:lime\">[добавить галерею]</a>";
		echo "\r\n<form style=\"display:none\" id=add2 method=\"POST\" action=\"" . $site_url . "admin/index.php\">";
		echo "<input type=hidden name=\"page\" value=\"galleries\">";
		echo "<input type=hidden name=\"act\" value=\"add\">";
		echo "<input id=txt type=text name=\"name\" size=40 onkeypress=\"if(event.keyCode==13) this.submit();\" autofocus>";
		echo "</form>";
		
	}
}

?>
</div></div>
	<div class=footer>© <a href="http://vkontakte.ru/yaroslavvolkov" class=color>Ярослав Волков</a> 2010
	<div class=madeby>Сайт сделал <a href="http://srg.bounceme.net/" class=color>Сергей Фомин</a></div>
	</div>
</body>
</html>