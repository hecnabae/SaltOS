<?php
/*
 ____        _ _    ___  ____
/ ___|  __ _| | |_ / _ \/ ___|
\___ \ / _` | | __| | | \___ \
 ___) | (_| | | |_| |_| |___) |
|____/ \__,_|_|\__|\___/|____/

SaltOS: Framework to develop Rich Internet Applications
Copyright (C) 2012 by Josep Sanz Campderrós
More information in http://www.saltos.net or info@saltos.net

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
// GLOBALIZE SOME VARS
global $_CONFIG;
global $_LANG;
// LOAD MAIN CONFIGURATION
$_CONFIG=eval_attr(xml2array("install/xml/config.xml"));
if(getDefault("ini_set")) eval_iniset(getDefault("ini_set"));
if(getParam("env_path")) $_CONFIG["putenv"]["PATH"]=getParam("env_path");
if(getParam("env_lang")) $_CONFIG["putenv"]["LANG"]=getParam("env_lang");
if(getDefault("putenv")) eval_putenv(getDefault("putenv"));
// LOAD LANGUAGE
$lang=getParam("lang",getDefault("lang"));
$style=getParam("style",getDefault("style"));
$iconset=getParam("iconset",getDefault("iconset"));
$_LANG=eval_attr(xml2array("install/xml/lang/$lang.xml"));
$_ICONSET=eval_attr(xml2array("xml/iconset.xml"));
// SOME ALLOWED ACTIONS
if(getParam("action")=="themeroller") {
	$_CONFIG["cache"]["useimginline"]="false";
	include("php/action/".getParam("action").".php");
}
// SOME DEFINES
define("__UI__","class='ui-state-default ui-corner-all'");
define("__IMG__","style='vertical-align:middle'");
define("__BACK__","<a href='javascript:history.back()' ".__UI__."><img src='".$_ICONSET[$iconset]['return']."' ".__IMG__."/>&nbsp;".LANG("back")."</a>");
define("__NEXT__","<a href='javascript:document.form.submit()' ".__UI__."><img src='".$_ICONSET[$iconset]['accept']."' ".__IMG__."/>&nbsp;".LANG("next")."</a>");
define("__TEST__","<a href='javascript:window.location.reload()' ".__UI__."><img src='".$_ICONSET[$iconset]['refresh']."' ".__IMG__."/>&nbsp;".LANG("test")."</a>");
define("__INSTALL__","<a href='javascript:document.form.submit()' ".__UI__."><img src='".$_ICONSET[$iconset]['accept']."' ".__IMG__."/>&nbsp;".LANG("install")."</a>");
define("__SALTOS__","<a href='javascript:document.form.submit()' ".__UI__."><img src='".$_ICONSET[$iconset]['accept']."' ".__IMG__."/>&nbsp;".LANG("saltos")."</a>");
define("__GREEN__","<span style='color:#007700'><b>");
define("__RED__","<span style='color:#770000'><b>");
define("__BOLD__","<span><b>");
define("__COLOR__","</b></span>");
define("__YES__",__GREEN__.LANG("yes").__COLOR__);
define("__NO__",__RED__.LANG("no").__COLOR__);
define("__DIV1__","class='ui-widget-header ui-corner-tl ui-corner-tr' style='margin:0px auto;padding:5px'");
define("__DIV2__","class='ui-widget-content ui-corner-bl ui-corner-br' style='margin:0px auto 2px auto;padding:5px'");
define("__DIV3__","style='margin:10px auto;padding:0px;text-align:right'");
define("__BR__","<br/>");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<link xmlns="" href="img/favicon.ico" rel="shortcut icon">
		<title><?php echo LANG("title")." - ".get_name_version_revision(); ?></title>
		<link href="css/default.css" rel="stylesheet" type="text/css"></link>
		<script type="text/javascript" src="lib/jquery/jquery-1.9.0.min.js"></script>
		<link href="<?php echo getDefault("stylepre").$style.getDefault("stylepost"); ?>" rel="stylesheet" type="text/css"></link>
		<script type="text/javascript" src="lib/jquery/jquery-ui-1.10.0.min.js"></script>
	</head>
	<body>
		<div class="ui-layout-north" style="margin-left:auto;margin-right:auto;width:800px">
			<div class="ui-widget">
				<div class="ui-widget-header ui-corner-bottom">
					<div class="texto"><?php echo LANG("title")." - ".get_name_version_revision(); ?></div>
				</div>
			</div>
		</div>
		<div class="ui-layout-center" style="margin-left:auto;margin-right:auto;width:800px">
			<div class="ui-widget">
				<form name="form">
					<?php if(intval(getParam("step"))==0) { ?>
						<div <?php echo __DIV1__; ?>>
							<?php echo LANG("step")." 0 - ".LANG("language"); ?>
						</div>
						<div <?php echo __DIV2__; ?>>
							<input type="hidden" name="step" value="1"/>
							<?php echo __BOLD__; ?><?php echo LANG("welcome_message"); ?><?php echo __COLOR__; ?><?php echo __BR__; ?>
							<?php echo __BR__; ?>
							<?php echo LANG("lang_message"); ?>
							<?php $temp=eval_attr(xml2array("xml/common/langs.xml")); ?>
							<?php $langs=array(); ?>
							<?php foreach($temp["rows"] as $row) $langs[$row["value"]]=$row["label"]; ?>
							<select name="lang" onchange="document.form.step.value='0';document.form.submit()" <?php echo __UI__; ?>>
								<?php foreach($langs as $key=>$val) { ?>
									<?php $selected=($lang==$key)?"selected":""; ?>
									<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $val; ?></option>
								<?php } ?>
							</select>
							<?php echo __BR__; ?>
							<?php echo LANG("style_message"); ?>
							<?php $temp=eval_attr(xml2array("xml/common/styles.xml")); ?>
							<?php if(!isset($temp["rows"]) && isset($temp["rows#1"])) { $temp["rows"]=$temp["rows#1"]; unset($temp["rows#1"]); } ?>
							<?php $styles=array(); ?>
							<?php foreach($temp["rows"] as $row) $styles[$row["value"]]=$row["label"]; ?>
							<select name="style" onchange="document.form.step.value='0';document.form.submit()" <?php echo __UI__; ?>>
								<?php foreach($styles as $key=>$val) { ?>
									<?php $selected=($style==$key)?"selected":""; ?>
									<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $val; ?></option>
								<?php } ?>
							</select>
							<?php echo __BR__; ?>
							<?php echo LANG("iconset_message"); ?>
							<?php $temp=eval_attr(xml2array("xml/common/iconset.xml")); ?>
							<?php $iconsets=array(); ?>
							<?php foreach($temp["rows"] as $row) $iconsets[$row["value"]]=$row["label"]; ?>
							<select name="iconset" onchange="document.form.step.value='0';document.form.submit()" <?php echo __UI__; ?>>
								<?php foreach($iconsets as $key=>$val) { ?>
									<?php $selected=($iconset==$key)?"selected":""; ?>
									<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $val; ?></option>
								<?php } ?>
							</select>
							<?php echo __BR__; ?>
							<?php echo __BR__; ?>
							<?php echo __BOLD__; ?><?php echo LANG("begin_message"); ?><?php echo __COLOR__; ?><?php echo __BR__; ?>
						</div>
						<div <?php echo __DIV3__; ?>>
							<?php echo __NEXT__; ?>
						</div>
					<?php } elseif(intval(getParam("step"))==1) { ?>
						<div <?php echo __DIV1__; ?>>
							<?php echo LANG("step")." 1 - ".LANG("is_writable"); ?>
						</div>
						<div <?php echo __DIV2__; ?>>
							<input type="hidden" name="step" value="2"/>
							<?php $cancontinue=1; ?>
							<?php $_CONFIG["dirs"][]=getcwd()."/xml"; ?>
							<?php foreach(getDefault("dirs") as $dir) { ?>
								<?php $cancontinue&=($iswritable=is_writable($dir)); ?>
								<?php echo substr($dir,-4,4)==".xml"?LANG("file").":":LANG("directory").":"; ?> <?php echo $dir; ?>: <?php echo $iswritable?__YES__:__NO__; ?><?php echo __BR__; ?>
							<?php } ?>
						</div>
						<div <?php echo __DIV1__; ?>>
							<?php echo LANG("step")." 1 - ".LANG("env_vars"); ?>
						</div>
						<div <?php echo __DIV2__; ?>>
							<?php echo LANG("env_path"); ?>: <input type="text" size="40" onchange="document.form.step.value='1';document.form.submit()" <?php echo __UI__; ?> name="env_path" value="<?php echo getDefault("putenv/PATH"); ?>"/><?php echo __BR__; ?>
							<?php echo LANG("env_lang"); ?>: <input type="text" size="20" onchange="document.form.step.value='1';document.form.submit()" <?php echo __UI__; ?> name="env_lang" value="<?php echo getDefault("putenv/LANG"); ?>"/><?php echo __BR__; ?>

						</div>
						<div <?php echo __DIV1__; ?>>
							<?php echo LANG("step")." 1 - ".LANG("is_executable"); ?>
						</div>
						<div <?php echo __DIV2__; ?>>
							<?php $cancontinue2=1; ?>
							<?php $procesed=array(); ?>
							<?php foreach(getDefault("commands") as $index=>$command) { ?>
								<?php if(substr($index,0,2)!="__" && substr($index,-2,2)!="__" && !in_array($command,$procesed)) { ?>
									<?php $cancontinue2&=($exists=check_commands($command)); ?>
									<?php echo LANG("executable"); ?>: <?php echo $exists?trim(ob_passthru(getDefault("commands/which")." ".str_replace(array("__INPUT__"),array($command),getDefault("commands/__which__")))):$command; ?>: <?php echo $exists?__YES__:__NO__; ?><?php echo __BR__; ?>
									<?php $procesed[]=$command; ?>
								<?php } ?>
							<?php } ?>
						</div>
						<div <?php echo __DIV3__; ?>>
							<?php echo __BACK__; ?>
							<?php if(!$cancontinue || !$cancontinue2) echo __TEST__; ?>
							<?php if($cancontinue) echo __NEXT__; ?>
						</div>
						<?php unset($_GET["step"]); ?>
						<?php unset($_GET["env_path"]); ?>
						<?php unset($_GET["env_lang"]); ?>
						<?php foreach($_GET as $key=>$val) echo "<input type='hidden' name='$key' value='$val'/>"; ?>
					<?php } elseif(intval(getParam("step"))==2) { ?>
						<div <?php echo __DIV1__; ?>>
							<?php echo LANG("step")." 2 - ".LANG("database_link"); ?>
						</div>
						<div <?php echo __DIV2__; ?>>
							<?php $cancontinue=1; ?>
							<?php if(!getParam("dbtype")) { ?>
								<input type="hidden" name="step" value="2"/>
								<?php echo LANG("select_dbtype"); ?>
								<select name="dbtype" <?php echo __UI__; ?>>
									<option value="pdo_sqlite">SQLite3 (PDO)<?php echo LANG("select_prefered"); ?></option>
									<option value="sqlite3">SQLite3 (extension)</option>
									<option value="pdo_mysql">MariaDB &amp; MySQL (PDO)</option>
									<option value="mysql">MariaDB &amp; MySQL (extension)</option>
									<option value="mysqli">MariaDB &amp; MySQL (improved extension)</option>
								</select>
							<?php } elseif(in_array(getParam("dbtype"),array("pdo_sqlite","sqlite3"))) { ?>
								<input type="hidden" name="step" value="3"/>
								<?php $dbtypes=array("pdo_sqlite"=>"SQLite3 (PDO)","sqlite3"=>"SQLite3 (extension)"); ?>
								<?php echo LANG("selected_dbtype"); ?>: <?php echo __GREEN__.$dbtypes[getParam("dbtype")].__COLOR__; ?><?php echo __BR__; ?>
								<?php $dbfile=getDefault("db/file"); ?>
								<?php if(!file_exists($dbfile)) touch($dbfile); ?>
								<?php $cancontinue&=($iswritable=is_writable($dbfile)); ?>
								<?php echo LANG("dbfile"); ?>: <?php echo $dbfile; ?>: <?php echo $iswritable?__YES__:__NO__; ?><?php echo __BR__; ?>
								<?php $_CONFIG["db"]["type"]=getParam("dbtype"); ?>
								<?php capture_next_error(); db_connect(); $error=get_clear_error(); ?>
								<?php if(stripos($error,"try to install")!==false) show_php_error(); ?>
								<?php $cancontinue&=($error==""); ?>
								<?php echo LANG("dbtest"); ?>: <?php echo $error==""?__YES__:__NO__; ?><?php echo __BR__; ?>
							<?php } elseif(in_array(getParam("dbtype"),array("pdo_mysql","mysql","mysqli"))) { ?>
								<?php $dbtypes=array("pdo_mysql"=>"MariaDB &amp; MySQL (PDO)","mysql"=>"MariaDB &amp; MySQL (extension)","mysqli"=>"MariaDB &amp; MySQL (improved extension)"); ?>
								<?php echo LANG("selected_dbtype"); ?>: <?php echo __GREEN__.$dbtypes[getParam("dbtype")].__COLOR__; ?><?php echo __BR__; ?>
								<?php if(!getParam("dbhost") || !getParam("dbport") || !getParam("dbname")) { ?>
									<input type="hidden" name="step" value="2"/>
									<?php echo LANG("dbhost"); ?>: <input type="text" size="20" <?php echo __UI__; ?> name="dbhost" value="<?php echo getDefault("db/host"); ?>"/><?php echo __BR__; ?>
									<?php echo LANG("dbport"); ?>: <input type="text" size="20" <?php echo __UI__; ?> name="dbport" value="<?php echo getDefault("db/port"); ?>"/><?php echo __BR__; ?>
									<?php echo LANG("dbuser"); ?>: <input type="text" size="20" <?php echo __UI__; ?> name="dbuser" value="<?php echo getDefault("db/user"); ?>"/><?php echo __BR__; ?>
									<?php echo LANG("dbpass"); ?>: <input type="text" size="20" <?php echo __UI__; ?> name="dbpass" value="<?php echo getDefault("db/pass"); ?>"/><?php echo __BR__; ?>
									<?php echo LANG("dbname"); ?>: <input type="text" size="20" <?php echo __UI__; ?> name="dbname" value="<?php echo getDefault("db/name"); ?>"/><?php echo __BR__; ?>
								<?php } else { ?>
									<input type="hidden" name="step" value="3"/>
									<?php echo LANG("dbhost"); ?>: <?php echo __GREEN__.getParam("dbhost").__COLOR__; ?><?php echo __BR__; ?>
									<?php echo LANG("dbport"); ?>: <?php echo __GREEN__.getParam("dbport").__COLOR__; ?><?php echo __BR__; ?>
									<?php echo LANG("dbuser"); ?>: <?php echo getParam("dbuser")?__GREEN__.getParam("dbuser").__COLOR__:__RED__.LANG("undefined").__COLOR__; ?><?php echo __BR__; ?>
									<?php echo LANG("dbpass"); ?>: <?php echo getParam("dbpass")?__GREEN__.getParam("dbpass").__COLOR__:__RED__.LANG("undefined").__COLOR__; ?><?php echo __BR__; ?>
									<?php echo LANG("dbname"); ?>: <?php echo __GREEN__.getParam("dbname").__COLOR__; ?><?php echo __BR__; ?>
									<?php $_CONFIG["db"]["type"]=getParam("dbtype"); ?>
									<?php $_CONFIG["db"]["host"]=getParam("dbhost"); ?>
									<?php $_CONFIG["db"]["port"]=getParam("dbport"); ?>
									<?php $_CONFIG["db"]["user"]=getParam("dbuser"); ?>
									<?php $_CONFIG["db"]["pass"]=getParam("dbpass"); ?>
									<?php $_CONFIG["db"]["name"]=getParam("dbname"); ?>
									<?php capture_next_error(); db_connect(); $error=get_clear_error(); ?>
									<?php if(stripos($error,"try to install")!==false) show_php_error(); ?>
									<?php $cancontinue&=($error==""); ?>
									<?php echo LANG("dbtest"); ?>: <?php echo $error==""?__YES__:__NO__; ?><?php echo __BR__; ?>
								<?php } ?>
							<?php } ?>
						</div>
						<div <?php echo __DIV3__; ?>>
							<?php echo __BACK__; ?>
							<?php if(!$cancontinue) echo __TEST__; ?>
							<?php if($cancontinue) echo __NEXT__; ?>
						</div>
						<?php unset($_GET["step"]); ?>
						<?php foreach($_GET as $key=>$val) echo "<input type='hidden' name='$key' value='$val'/>"; ?>
					<?php } elseif(intval(getParam("step"))==3) { ?>
						<div <?php echo __DIV1__; ?>>
							<?php echo LANG("step")." 3 - ".LANG("admin_account"); ?>
						</div>
						<div <?php echo __DIV2__; ?>>
							<?php $cancontinue=1; ?>
							<?php if(!getParam("user") || !getParam("pass") || !getParam("timezone")) { ?>
								<input type="hidden" name="step" value="3"/>
								<?php echo LANG("user"); ?>: <input type="text" size="20" <?php echo __UI__; ?> name="user" value="<?php echo getParam("user")?getParam("user"):"admin"; ?>"/><?php echo __BR__; ?>
								<?php echo LANG("pass"); ?>: <input type="text" size="20" <?php echo __UI__; ?> name="pass" value="<?php echo getParam("pass")?getParam("pass"):"admin"; ?>"/><?php echo __BR__; ?>
								<?php echo LANG("email"); ?>: <input type="text" size="40" <?php echo __UI__; ?> name="email" value="<?php echo getParam("email")?getParam("email"):""; ?>"/> (<?php echo LANG("optional"); ?>)<?php echo __BR__; ?>
								<input type="checkbox" name="forcessl" id="forcessl" value="1" style="vertical-align:-15%"/><label for="forcessl"><?php echo LANG("forcessl"); ?></label><?php echo __BR__; ?>
								<?php echo LANG("timezone"); ?>:
								<?php $temp=eval_attr(xml2array("xml/common/timezones.xml")); ?>
								<?php $timezone=$temp["value"]; ?>
								<?php $timezones=array(); ?>
								<?php foreach($temp["rows"] as $row) $timezones[$row["value"]]=$row["label"]; ?>
								<select name="timezone" <?php echo __UI__; ?>>
									<?php foreach($timezones as $key=>$val) { ?>
										<?php $selected=($timezone==$key)?"selected":""; ?>
										<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $val; ?></option>
									<?php } ?>
								</select>
								<?php echo __BR__; ?>
								<input type="checkbox" name="streetdata" id="streetdata" value="1" style="vertical-align:-15%"/><label for="streetdata"><?php echo LANG("streetdata"); ?></label><?php echo __BR__; ?>
								<input type="checkbox" name="exampledata" id="exampledata" value="1" style="vertical-align:-15%"/><label for="exampledata"><?php echo LANG("exampledata"); ?></label><?php echo __BR__; ?>
								<?php $cancontinue=0; ?>
								<?php unset($_GET["user"]); ?>
								<?php unset($_GET["pass"]); ?>
								<?php unset($_GET["email"]); ?>
								<?php unset($_GET["forcessl"]); ?>
								<?php unset($_GET["timezone"]); ?>
								<?php unset($_GET["streetdata"]); ?>
								<?php unset($_GET["exampledata"]); ?>
							<?php } else { ?>
								<input type="hidden" name="step" value="4"/>
								<?php echo LANG("user"); ?>: <?php echo __GREEN__.getParam("user").__COLOR__; ?><?php echo __BR__; ?>
								<?php echo LANG("pass"); ?>: <?php echo __GREEN__.getParam("pass").__COLOR__; ?><?php echo __BR__; ?>
								<?php echo LANG("email"); ?>: <?php echo getParam("email")?__GREEN__.getParam("email").__COLOR__:__RED__.LANG("undefined").__COLOR__; ?><?php echo __BR__; ?>
								<?php echo LANG("forcessl"); ?>: <?php echo getParam("forcessl")?__GREEN__.__YES__.__COLOR__:__RED__.__NO__.__COLOR__; ?><?php echo __BR__; ?>
								<?php echo LANG("timezone"); ?>: <?php echo __GREEN__.getParam("timezone").__COLOR__; ?><?php echo __BR__; ?>
								<?php echo LANG("streetdata"); ?>: <?php echo getParam("streetdata")?__GREEN__.__YES__.__COLOR__:__RED__.__NO__.__COLOR__; ?><?php echo __BR__; ?>
								<?php echo LANG("exampledata"); ?>: <?php echo getParam("exampledata")?__GREEN__.__YES__.__COLOR__:__RED__.__NO__.__COLOR__; ?><?php echo __BR__; ?>
							<?php } ?>
						</div>
						<div <?php echo __DIV3__; ?>>
							<?php echo __BACK__; ?>
							<?php echo __NEXT__; ?>
						</div>
						<?php unset($_GET["step"]); ?>
						<?php foreach($_GET as $key=>$val) echo "<input type='hidden' name='$key' value='$val'/>"; ?>
					<?php } elseif(intval(getParam("step"))==4) { ?>
						<input type="hidden" name="step" value="5"/>
						<div <?php echo __DIV1__; ?>>
							<?php echo LANG("step")." 4 - ".LANG("install_input"); ?>
						</div>
						<div <?php echo __DIV2__; ?>>
							<b><?php echo LANG("language"); ?></b><?php echo __BR__; ?>
							<?php $temp=eval_attr(xml2array("xml/common/langs.xml")); ?>
							<?php $langs=array(); ?>
							<?php foreach($temp["rows"] as $row) $langs[$row["value"]]=$row["label"]; ?>
							<?php echo LANG("lang"); ?>: <?php echo __GREEN__.$langs[getParam("lang",getDefault("lang"))]." (".getParam("lang",getDefault("lang")).")".__COLOR__.__BR__; ?>
							<?php $temp=eval_attr(xml2array("xml/common/styles.xml")); ?>
							<?php if(!isset($temp["rows"]) && isset($temp["rows#1"])) { $temp["rows"]=$temp["rows#1"]; unset($temp["rows#1"]); } ?>
							<?php $styles=array(); ?>
							<?php foreach($temp["rows"] as $row) $styles[$row["value"]]=$row["label"]; ?>
							<?php echo LANG("style"); ?>: <?php echo __GREEN__.$styles[getParam("style",getDefault("style"))]." (".getParam("style",getDefault("style")).")".__COLOR__.__BR__; ?>
							<?php $temp=eval_attr(xml2array("xml/common/iconset.xml")); ?>
							<?php $iconsets=array(); ?>
							<?php foreach($temp["rows"] as $row) $iconsets[$row["value"]]=$row["label"]; ?>
							<?php echo LANG("iconset"); ?>: <?php echo __GREEN__.$iconsets[getParam("iconset",getDefault("iconset"))]." (".getParam("iconset",getDefault("iconset")).")".__COLOR__.__BR__; ?>
							<b><?php echo LANG("is_writable"); ?></b><?php echo __BR__; ?>
							<?php $_CONFIG["dirs"][]=getcwd()."/xml"; ?>
							<?php foreach(getDefault("dirs") as $dir) { ?>
								<?php $iswritable=is_writable($dir); ?>
								<?php echo substr($dir,-4,4)==".xml"?LANG("file").":":LANG("directory").":"; ?> <?php echo $dir; ?>: <?php echo $iswritable?__YES__:__NO__; ?><?php echo __BR__; ?>
							<?php } ?>
							<b><?php echo LANG("env_vars"); ?></b><?php echo __BR__; ?>
							<?php echo LANG("env_path"); ?>: <?php echo __GREEN__.getParam("env_path",getDefault("putenv/PATH")).__COLOR__.__BR__; ?>
							<?php echo LANG("env_lang"); ?>: <?php echo __GREEN__.getParam("env_lang",getDefault("putenv/LANG")).__COLOR__.__BR__; ?>
							<b><?php echo LANG("is_executable"); ?></b><?php echo __BR__; ?>
							<?php $procesed=array(); ?>
							<?php foreach(getDefault("commands") as $index=>$command) { ?>
								<?php if(substr($index,0,2)!="__" && substr($index,-2,2)!="__" && !in_array($command,$procesed)) { ?>
									<?php $exists=check_commands($command); ?>
									<?php echo LANG("executable"); ?>: <?php echo $exists?trim(ob_passthru(getDefault("commands/which")." ".str_replace(array("__INPUT__"),array($command),getDefault("commands/__which__")))):$command; ?>: <?php echo $exists?__YES__:__NO__; ?><?php echo __BR__; ?>
									<?php $procesed[]=$command; ?>
								<?php } ?>
							<?php } ?>
							<b><?php echo LANG("database_link"); ?>:</b><?php echo __BR__; ?>
							<?php if(in_array(getParam("dbtype",getDefault("db/type")),array("pdo_sqlite","sqlite3"))) { ?>
								<?php $dbtypes=array("pdo_sqlite"=>"SQLite3 (PDO)","sqlite3"=>"SQLite3 (extension)"); ?>
								<?php echo LANG("selected_dbtype"); ?>: <?php echo __GREEN__.$dbtypes[getParam("dbtype",getDefault("db/type"))].__COLOR__; ?><?php echo __BR__; ?>
								<?php $dbfile=getDefault("db/file"); ?>
								<?php echo LANG("dbfile"); ?>: <?php echo __GREEN__.$dbfile.__COLOR__; ?><?php echo __BR__; ?>
							<?php } elseif(in_array(getParam("dbtype",getDefault("db/type")),array("pdo_mysql","mysql","mysqli"))) { ?>
								<?php $dbtypes=array("pdo_mysql"=>"MariaDB &amp; MySQL (PDO)","mysql"=>"MariaDB &amp; MySQL (extension)","mysqli"=>"MariaDB &amp; MySQL (improved extension)"); ?>
								<?php echo LANG("selected_dbtype"); ?>: <?php echo __GREEN__.$dbtypes[getParam("dbtype",getDefault("db/type"))].__COLOR__; ?><?php echo __BR__; ?>
								<?php echo LANG("dbhost"); ?>: <?php echo __GREEN__.getParam("dbhost",getDefault("db/host")).__COLOR__; ?><?php echo __BR__; ?>
								<?php echo LANG("dbport"); ?>: <?php echo __GREEN__.getParam("dbport",getDefault("db/port")).__COLOR__; ?><?php echo __BR__; ?>
								<?php echo LANG("dbuser"); ?>: <?php echo getParam("dbuser")?__GREEN__.getParam("dbuser").__COLOR__:__RED__.LANG("undefined").__COLOR__; ?><?php echo __BR__; ?>
								<?php echo LANG("dbpass"); ?>: <?php echo getParam("dbpass")?__GREEN__.getParam("dbpass").__COLOR__:__RED__.LANG("undefined").__COLOR__; ?><?php echo __BR__; ?>
								<?php echo LANG("dbname"); ?>: <?php echo __GREEN__.getParam("dbname",getDefault("db/name")).__COLOR__; ?><?php echo __BR__; ?>
							<?php } ?>
							<b><?php echo LANG("admin_account"); ?>:</b><?php echo __BR__; ?>
							<?php echo LANG("user"); ?>: <?php echo __GREEN__.getParam("user","admin").__COLOR__; ?><?php echo __BR__; ?>
							<?php echo LANG("pass"); ?>: <?php echo __GREEN__.getParam("pass","admin").__COLOR__; ?><?php echo __BR__; ?>
							<?php echo LANG("email"); ?>: <?php echo getParam("email")?__GREEN__.getParam("email").__COLOR__:__RED__.LANG("undefined").__COLOR__; ?><?php echo __BR__; ?>
							<?php echo LANG("forcessl"); ?>: <?php echo getParam("forcessl")?__GREEN__.__YES__.__COLOR__:__RED__.__NO__.__COLOR__; ?><?php echo __BR__; ?>
							<?php echo LANG("timezone"); ?>: <?php echo __GREEN__.getParam("timezone",getDefault("ini_set/date.timezone")).__COLOR__; ?><?php echo __BR__; ?>
							<?php echo LANG("streetdata"); ?>: <?php echo getParam("streetdata")?__GREEN__.__YES__.__COLOR__:__RED__.__NO__.__COLOR__; ?><?php echo __BR__; ?>
							<?php echo LANG("exampledata"); ?>: <?php echo getParam("exampledata")?__GREEN__.__YES__.__COLOR__:__RED__.__NO__.__COLOR__; ?><?php echo __BR__; ?>
						</div>
						<div <?php echo __DIV3__; ?>>
							<?php echo __BACK__; ?>
							<?php echo __INSTALL__; ?>
						</div>
						<?php unset($_GET["step"]); ?>
						<?php foreach($_GET as $key=>$val) echo "<input type='hidden' name='$key' value='$val'/>"; ?>
					<?php } elseif(intval(getParam("step"))==5) { ?>
						<div <?php echo __DIV1__; ?>>
							<?php echo LANG("step")." 5 - ".LANG("install_output"); ?>
						</div>
						<div <?php echo __DIV2__; ?>>
							<?php
								// CONNECT TO DATABASE
								$_CONFIG["db"]["type"]=getParam("dbtype",getDefault("db/type"));
								$_CONFIG["db"]["host"]=getParam("dbhost",getDefault("db/host"));
								$_CONFIG["db"]["port"]=getParam("dbport",getDefault("db/port"));
								$_CONFIG["db"]["user"]=getParam("dbuser",getDefault("db/user"));
								$_CONFIG["db"]["pass"]=getParam("dbpass",getDefault("db/pass"));
								$_CONFIG["db"]["name"]=getParam("dbname",getDefault("db/name"));
								if(in_array(getParam("dbtype",getDefault("db/type")),array("pdo_sqlite","sqlite3"))) {
									$dbfile=getDefault("db/file");
									if(!file_exists($dbfile)) touch($dbfile);
								}
								capture_next_error(); db_connect(); $error=get_clear_error();
								if(stripos($error,"try to install")!==false) show_php_error();
								// SAVE THE config.xml WITH THE NEW CONFIGURATION
								echo current_datetime().": ".LANG("config").": ";
								$config=array();
								// SET DATABASE CONFIGURATION
								set_array($config,"node",array(
									"value"=>"",
									"#attr"=>array("include"=>"xml/config.xml.old","replace"=>"true")
								));
								set_array($config,"node",array(
									"value"=>array("type"=>getParam("dbtype",getDefault("db/type"))),
									"#attr"=>array("path"=>"db/type","replace"=>"true")
								));
								set_array($config,"node",array(
									"value"=>array("host"=>getParam("dbhost",getDefault("db/host"))),
									"#attr"=>array("path"=>"db/host","replace"=>"true")
								));
								set_array($config,"node",array(
									"value"=>array("port"=>getParam("dbport",getDefault("db/port"))),
									"#attr"=>array("path"=>"db/port","replace"=>"true")
								));
								set_array($config,"node",array(
									"value"=>array("user"=>getParam("dbuser",getDefault("db/user"))),
									"#attr"=>array("path"=>"db/user","replace"=>"true")
								));
								set_array($config,"node",array(
									"value"=>array("pass"=>getParam("dbpass",getDefault("db/pass"))),
									"#attr"=>array("path"=>"db/pass","replace"=>"true")
								));
								set_array($config,"node",array(
									"value"=>array("name"=>getParam("dbname",getDefault("db/name"))),
									"#attr"=>array("path"=>"db/name","replace"=>"true")
								));
								// SET MORE THINGS
								set_array($config,"node",array(
									"value"=>array("lang"=>$lang),
									"#attr"=>array("path"=>"default/lang","replace"=>"true")
								));
								set_array($config,"node",array(
									"value"=>array("style"=>array(
										"value"=>"is_mobile()?'".(is_mobile()?$style:"b")."':'".(is_mobile()?"blue":$style)."'",
										"#attr"=>array("eval"=>"true"))),
									"#attr"=>array("path"=>"default/style","replace"=>"true")
								));
								set_array($config,"node",array(
									"value"=>array("iconset"=>$iconset),
									"#attr"=>array("path"=>"default/iconset","replace"=>"true")
								));
								set_array($config,"node",array(
									"value"=>array("forcessl"=>getParam("forcessl")?"true":"false"),
									"#attr"=>array("path"=>"server/forcessl","replace"=>"true")
								));
								set_array($config,"node",array(
									"value"=>array("date.timezone"=>getParam("timezone",getDefault("ini_set/date.timezone"))),
									"#attr"=>array("path"=>"ini_set/date.timezone","replace"=>"true")
								));
								set_array($config,"node",array(
									"value"=>array("PATH"=>getParam("env_path",getDefault("putenv/PATH"))),
									"#attr"=>array("path"=>"putenv/PATH","replace"=>"true")
								));
								set_array($config,"node",array(
									"value"=>array("LANG"=>getParam("env_lang",getDefault("putenv/LANG"))),
									"#attr"=>array("path"=>"putenv/LANG","replace"=>"true")
								));
								$buffer="<?xml version='1.0' encoding='UTF-8' ?>\n";
								$buffer.=array2xml($config);
								if(file_exists("xml/config.xml") && !file_exists("xml/config.xml.old")) {
									rename("xml/config.xml","xml/config.xml.old");
									file_put_contents("xml/config.xml",$buffer);
									echo __YES__.__BR__;
								} else {
									file_put_contents("xml/config.xml.new",$buffer);
									echo __NO__.__BR__;
								}
								// CREATE THE DATABASE SCHEMA
								echo current_datetime().": ".LANG("dbschema").": ";
								capture_next_error();
								$exists=CONFIG("xml/dbschema.xml");
								get_clear_error();
								if(!$exists) {
									db_schema();
									echo __YES__.__BR__;
								} else {
									echo __NO__.__BR__;
								}
								// INSERT THE STATIC REGISTERS
								echo current_datetime().": ".LANG("dbstatic").": ";
								capture_next_error();
								$exists=CONFIG("xml/dbstatic.xml");
								get_clear_error();
								if(!$exists) {
									db_static();
									echo __YES__.__BR__;
								} else {
									echo __NO__.__BR__;
								}
								// IMPORT DEFAULT CONFIGURATION
								$oldcache=set_use_cache("false");
								$files=glob("install/xml/tbl_*.xml");
								if(is_array($files) && count($files)>0) {
									foreach($files as $file) {
										$table=basename($file,".xml");
										$query="SELECT COUNT(*) FROM $table";
										$numrows=execute_query($query);
										echo current_datetime().": ".LANG("defaultdata").": ".basename($file).": ";
										if(!$numrows) {
											$rows=eval_attr(xml2array($file));
											foreach($rows as $row) {
												$keys=array();
												$vals=array();
												foreach($row as $key=>$val) {
													$keys[]="`${key}`";
													$vals[]=($val=="NULL")?$val:"'${val}'";
												}
												$keys=implode(",",$keys);
												$vals=implode(",",$vals);
												$query="INSERT INTO `$table`($keys) VALUES($vals)";
												db_query($query);
											}
											echo __YES__.__BR__;
										} else {
											echo __NO__.__BR__;
										}
									}
								}
								set_use_cache($oldcache);
								// CREATE THE NEEDED PERMISSIONS TO MAIN GROUP
								$oldcache=set_use_cache("false");
								echo current_datetime().": ".LANG("permissiondata").": ";
								$query="SELECT COUNT(*) FROM tbl_grupos_p";
								$numrows=execute_query($query);
								if(!$numrows) {
									$query="INSERT INTO tbl_grupos_p SELECT NULL id,a.id id_grupo,b.id_aplicacion id_aplicacion,b.id_permiso id_permiso,'1' allow,'0' deny FROM tbl_grupos a,tbl_aplicaciones_p b";
									db_query($query);
									echo __YES__.__BR__;
								} else {
									echo __NO__.__BR__;
								}
								set_use_cache($oldcache);
								// IMPORT STREET INFORMATION USING AN OPTIMIZATION FROM COMMAND LINE
								if(getParam("streetdata")) {
									$oldcache=set_use_cache("false");
									$dbtemp="sql";
									if(in_array(getParam("dbtype",getDefault("db/type")),array("pdo_sqlite","sqlite3"))) $dbtemp="sqlite";
									if(in_array(getParam("dbtype",getDefault("db/type")),array("pdo_mysql","mysql","mysqli"))) $dbtemp="mysql";
									$files=glob("install/sql/tbl_*.$dbtemp.gz");
									if(is_array($files) && count($files)>0) {
										foreach($files as $file) {
											$table=basename($file,".$dbtemp.gz");
											$query="SELECT COUNT(*) FROM $table";
											$numrows=execute_query($query);
											echo current_datetime().": ".LANG("streetdata").": ".basename($file).": ";
											if(!$numrows) {
												if($dbtemp=="sqlite" && check_commands(array(getDefault("commands/zcat_install"),getDefault("commands/sqlite_install")))) {
													ob_passthru(getDefault("commands/zcat_install")." ".str_replace(array("__INPUT__"),array($file),getDefault("commands/__zcat_install__"))." | ".getDefault("commands/sqlite3_install")." ".str_replace(array("__DBFILE__"),array(getDefault("db/file")),getDefault("commands/__sqlite3_install__")));
												} elseif($dbtemp=="mysql" && check_commands(array(getDefault("commands/zcat_install"),getDefault("commands/mysql_install")))) {
													ob_passthru(getDefault("commands/zcat_install")." ".str_replace(array("__INPUT__"),array($file),getDefault("commands/__zcat_install__"))." | ".getDefault("commands/mysql_install")." ".str_replace(array("__DBHOST__","__DBPORT__","__DBUSER__","__DBPASS__","__DBNAME__"),array(getDefault("db/host"),getDefault("db/port"),getDefault("db/user"),getDefault("db/pass"),getDefault("db/name")),getDefault("commands/__mysql_install__")));
												} else {
													foreach(gzfile($file) as $query) db_query($query);
												}
												echo __YES__.__BR__;
											} else {
												echo __NO__.__BR__;
											}
										}
									}
									set_use_cache($oldcache);
								}
								// IMPORT EXAMPLE DATA
								if(getParam("exampledata")) {
									$oldcache=set_use_cache("false");
									$files=glob("install/xml/example/tbl_*.xml");
									if(is_array($files) && count($files)>0) {
										foreach($files as $file) {
											$table=basename($file,".xml");
											$query="SELECT COUNT(*) FROM $table";
											$numrows=execute_query($query);
											echo current_datetime().": ".LANG("exampledata").": ".basename($file).": ";
											if(!$numrows) {
												$rows=eval_attr(xml2array($file));
												foreach($rows as $row) {
													$keys=array();
													$vals=array();
													foreach($row as $key=>$val) {
														$keys[]="`${key}`";
														$vals[]=($val=="NULL")?$val:"'${val}'";
													}
													$keys=implode(",",$keys);
													$vals=implode(",",$vals);
													$query="INSERT INTO `$table`($keys) VALUES($vals)";
													db_query($query);
												}
												echo __YES__.__BR__;
											} else {
												echo __NO__.__BR__;
											}
										}
									}
									set_use_cache($oldcache);
								}
								// DELETE OR RENAME INSTALL FILES
								if(is_link("install")) {
									echo current_datetime().": ".LANG("delete").": ";
									capture_next_error();
									unlink("install");
									get_clear_error();
									if(!file_exists("install")) {
										echo __YES__.__BR__;
									} else {
										echo __NO__.__BR__;
									}
								} else {
									echo current_datetime().": ".LANG("rename").": ";
									capture_next_error();
									rename("install","__install");
									get_clear_error();
									if(!file_exists("install")) {
										echo __YES__.__BR__;
									} else {
										echo __NO__.__BR__;
									}
								}
								// END OF INSTALL
								echo current_datetime().": ".LANG("finish").__BR__;
							?>
						</div>
						<div <?php echo __DIV3__; ?>>
							<?php echo __SALTOS__; ?>
						</div>
						<input type="hidden" name="action" value="login"/>
						<input type="hidden" name="user" value="<?php echo getParam("user","admin"); ?>"/>
						<input type="hidden" name="pass" value="<?php echo getParam("pass","admin"); ?>"/>
						<input type="hidden" name="lang" value="<?php echo getParam("lang",getDefault("lang")); ?>"/>
						<input type="hidden" name="style" value="<?php echo getParam("style",getDefault("style")); ?>"/>
						<input type="hidden" name="iconset" value="<?php echo getParam("iconset",getDefault("iconset")); ?>"/>
					<?php } ?>
				</form>
			</div>
		</div>
	</body>
</html>
<?php
// FORCE TO CLEAR CACHE
$files=glob(get_directory("dirs/cachedir")."*");
if(is_array($files) && count($files)>0) {
	foreach($files as $key=>$file) {
		capture_next_error();
		unlink($file);
		get_clear_error();
	}
}
// THE END
die();
?>