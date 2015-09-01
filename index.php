<!DOCTYPE html>
<html>
<?	$site_url="http://" . $_SERVER["SERVER_NAME"] . "/";
$hostname="78.108.84.86";
$uname="u91689";
$pass="1q2w3e";

$db="b91689_yvwp";
mysql_connect($hostname, $uname, $pass);
mysql_select_db($db);
$res=mysql_query("SELECT * FROM galleries");
while ($row=mysql_fetch_array($res)) {
	$pages[$row["id"]]=strtolower($row["name"]);
}
$sel=1;
if($_GET["page"]=="contacts"){
	$sel=0;
}elseif(in_array($_GET["page"], $pages)){
	$sel=array_search($_GET["page"], $pages);
}

?>
<head>
<meta charset="utf-8">
<LINK REL="stylesheet" HREF="./main.css">

<meta name="Description" content="Профессиональный свадебный фотограф Ярослав Волков. Студийная и семейная фотография. Нижний Новгород">
<META NAME="distribution" CONTENT="Global">
<meta name="keywords" content="Yaroslav Volkov, Ярослав Волков, свадебный фотограф в Нижегородской области, фотограф на свадьбу в Нижнем Новгороде, фотограф в Балахне, свадьба в НН"/>	
<META NAME="copyright" CONTENT="">
<META NAME="revisit-after" CONTENT="1 Day">
<META NAME="robots" CONTENT="INDEX,FOLLOW">
<META HTTP-EQUIV="content-language" CONTENT="ru">
<META HTTP-EQUIV="pragma" CONTENT="no-cache">
<meta name='yandex-verification' content='49b0d138e0a3c659' />
<title>Yaroslav Volkov - wedding photographer</title>
<? if($sel!=3){?>
<script src="swfobject.js"></script>
<?} ?>
</head>
<body><div id=wrapper><div id=content>
<? if($sel!=1) echo '<a href="' . $site_url . '">'; 
	echo '<img src="' . $site_url . 'img/header.png" alt="Yaroslav Volkov - wedding photographer">';
 if($sel!=1) echo '</a>'; ?>	
<div class=buttons>
<?
	$res=mysql_query("SELECT * FROM galleries");
	while ($row=mysql_fetch_array($res)) {
		if($sel!=$row["id"]) echo '<a href="' . $site_url . 'index.php?page=' . strtolower($row["name"]) . '">';
		echo $row["name"];
		if($sel!=$row["id"]) echo "</a>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	}
	if($sel!=0)
		echo '<a href="' . $site_url . 'index.php?page=contacts">';
	echo 'Contacts';
	if($sel!=0) echo "</a>";
?></div><br>
<? if($sel!=0){?>
	<div id="flashcontent" >AutoViewer requires JavaScript and the Flash Player. <a href="http://www.macromedia.com/go/getflashplayer/">Get Flash here.</a> If you have Flash installed, <a href="index.html?detectflash=false">click to view gallery</a></div>	
	<script type="text/javascript">
		var fo = new FlashObject("viewer.swf", "viewer", "100%", "500", "8", "#000000");	
<?
	echo 'fo.addVariable("xmlURL", "./' . $pages[$sel] . '.xml");';
?>
		fo.write("flashcontent");	
	</script>	
<?}else{
	echo "<div class=text>";
	$res=mysql_query("SELECT * FROM contacts");
	while ($row=mysql_fetch_array($res)) { 
		echo nl2br($row["val"]);
	}
	echo "</div>";
}?>

</div></div>
<noindex><div class=footer>© <a rel="nofollow" href="http://vkontakte.ru/yaroslavvolkov" class=color>Ярослав Волков</a> 2010-<? echo date('Y');?>
	<div class=madeby>Сайт сделал <a rel="nofollow" href="http://srg.bounceme.net/" class=color>Сергей Фомин</a></div>
	</div></noindex>
</body>
</html>