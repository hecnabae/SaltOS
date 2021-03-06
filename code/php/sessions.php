<?php
/*
 ____        _ _    ___  ____
/ ___|  __ _| | |_ / _ \/ ___|
\___ \ / _` | | __| | | \___ \
 ___) | (_| | | |_| |_| |___) |
|____/ \__,_|_|\__|\___/|____/

SaltOS: Framework to develop Rich Internet Applications
Copyright (C) 2007-2016 by Josep Sanz Campderrós
More information in http://www.saltos.org or info@saltos.org

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
function sess_open_handler($save_path,$session_name) {
	return true;
}

function sess_close_handler() {
	return true;
}

function sess_read_handler($id) {
	global $_CONFIG;
	$sess_file=session_save_path()."/".$id;
	$query=make_select_query("tbl_sessions","sess_data",make_where_query(array(
		"sess_file"=>$sess_file
	)));
	$sess_data=execute_query($query);
	if($sess_data!==null) {
		$sess_data=base64_decode($sess_data);
	} else {
		$sess_data="";
	}
	$_CONFIG["sess"]["hash"]=md5($sess_data);
	return($sess_data);
}

function sess_write_handler($id,$sess_data) {
	$sess_file=session_save_path()."/".$id;
	$sess_time=time();
	$sess_hash=md5($sess_data);
	$sess_data=base64_encode($sess_data);
	$query=make_select_query("tbl_sessions","id",make_where_query(array(
		"sess_file"=>$sess_file
	)));
	$exists=execute_query($query);
	if($exists) {
		$query=make_update_query("tbl_sessions",array(
			"sess_data"=>$sess_data,
			"sess_time"=>$sess_time
		),make_where_query(array(
			"sess_file"=>$sess_file
		)));
		if(getDefault("sess/hash")==$sess_hash) {
			$query=make_update_query("tbl_sessions",array(
				"sess_time"=>$sess_time
			),make_where_query(array(
				"sess_file"=>$sess_file
			)));
		}
		db_query($query);
	} else {
		$query=make_insert_query("tbl_sessions",array(
			"sess_file"=>$sess_file,
			"sess_data"=>$sess_data,
			"sess_time"=>$sess_time
		));
		db_query($query);
	}
	return true;
}

function sess_destroy_handler($id) {
	$sess_file=session_save_path()."/".$id;
	$query=make_delete_query("tbl_sessions",make_where_query(array(
		"sess_file"=>$sess_file
	)));
	db_query($query);
	return true ;
}

function sess_gc_handler($maxlifetime) {
	$sess_time=time()-$maxlifetime;
	$query=make_delete_query("tbl_sessions",make_where_query(array(
		"sess_time"=>array("<",$sess_time)
	)));
	db_query($query);
	return true;
}

function sess_init() {
	$ini_set=array(
		"session.gc_maxlifetime"=>getDefault("sess/timeout"),
		"session.gc_probability"=>getDefault("sess/probability"),
		"session.gc_divisor"=>getDefault("sess/divisor"),
		"session.use_trans_sid"=>0,
		"session.use_only_cookie"=>1
	);
	foreach($ini_set as $varname=>$newvalue) ini_set($varname,$newvalue);
	session_set_save_handler("sess_open_handler","sess_close_handler","sess_read_handler","sess_write_handler","sess_destroy_handler","sess_gc_handler");
	session_save_path(getDefault("sess/save_path"));
	session_set_cookie_params(0,dirname(getDefault("server/pathname",getServer("SCRIPT_NAME"))),"",eval_bool(getDefault("server/forcessl")),false);
	session_start();
}

function sess_close() {
	session_write_close();
}

function current_session() {
	$sess_file=session_save_path()."/".session_id();
	$query="SELECT id FROM tbl_sessions WHERE sess_file='${sess_file}'";
	$id=execute_query($query);
	if(!$id) {
		$sess_time=time();
		$query=make_insert_query("tbl_sessions",array(
			"sess_file"=>$sess_file,
			"sess_data"=>"",
			"sess_time"=>$sess_time
		));
		db_query($query);
		$query="SELECT MAX(id) maximo FROM tbl_sessions";
		$id=execute_query($query);
	}
	return $id;
}
?>