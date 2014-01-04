/*
 ____        _ _    ___  ____
/ ___|  __ _| | |_ / _ \/ ___|
\___ \ / _` | | __| | | \___ \
 ___) | (_| | | |_| |_| |___) |
|____/ \__,_|_|\__|\___/|____/

SaltOS: Framework to develop Rich Internet Applications
Copyright (C) 2007-2014 by Josep Sanz Campderrós
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

if(typeof(__updateregistros__)=="undefined" && typeof(parent.__updateregistros__)=="undefined") {
	"use strict";
	var __updateregistros__=1;

	function update_registros() {
		var aplicacion=$("select[name$=id_aplicacion]");
		var registro=$("select[name$=id_registro]");
		if(registros_defaults=="") registros_defaults=$(registro).html();
		var data="action=ajax&query=registros&id_aplicacion="+$(aplicacion).val();
		$.ajax({
			url:"xml.php",
			data:data,
			type:"get",
			success:function(response) {
				var options=registros_defaults;
				var original=$(registro).attr("original");
				$("root>rows>row",response).each(function() {
					var id=$("id",this).text();
					var nombre=$("nombre",this).text();
					var selected=(id==original)?"selected='selected'":"";
					options+="<option value='"+id+"' "+selected+">"+nombre+"</option>";
				});
				$(registro).html(options);
			},
			error:function(XMLHttpRequest,textStatus,errorThrown) {
				errorcontent(XMLHttpRequest.status,XMLHttpRequest.statusText);
			}
		});
	}

}

"use strict";
var registros_defaults="";

$(function() { update_registros(); });
