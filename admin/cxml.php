<?
$files=scandir($dir);
$output = fopen( $DOCUMENT_ROOT . "/" . strtolower($row["name"]) . ".xml" ,"wb");
fwrite($output,'<?xml version="1.0" encoding="UTF-8"?>
');
fwrite($output,'<gallery title="" frameColor="0xFFFFFF" frameWidth="0" imagePadding="20" displayTime="6">
');
foreach($files as $f){
	if(is_file($dir . $f)){
	$size=getimagesize($dir . $f);
		fwrite($output,'<image>
   <filename>./' . strtolower($row["name"]) . '/' . $f . '</filename>
   <caption></caption>
   <width>' . $size[0] . '</width>
   <height>' . $size[1] . '</height>
</image>
');
	}
}
fwrite($output,'</gallery>');
fclose($output);
?>