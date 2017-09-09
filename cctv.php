<!DOCTYPE html>
<html>
<head>
	<title>CCTV LIVE</title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.6/css/bootstrap-modal.min.css" />
	<style type="text/css">
		ul {
		    max-width:1024px;
		    text-align:center;
		}

		ul li {
		    display:inline;
		}

		ul li div {
			float:left;
		    margin:5px;
		}

		.modal.fade.in {
			top : 0 !important;
		}

		.modal {
		  text-align: center;
		  padding: 0!important;
		}

		.modal:before {
		  content: '';
		  display: inline-block;
		  height: 100%;
		  vertical-align: middle;
		  margin-right: -4px;
		}

		.modal-dialog {
		  display: inline-block;
		  text-align: left;
		  vertical-align: middle;
		}

		.btn-custom {
			margin-bottom: 5px;
		}

		body { 
		  background: url(blue-happiness.jpg) no-repeat center center fixed; 
		  -webkit-background-size: cover;
		  -moz-background-size: cover;
		  -o-background-size: cover;
		  background-size: cover;
		}
	</style>
</head>
<body>

<!-- CONTAINER CCTV -->
<div class="container-fluid">
	<div class="row">
	<div align="center" class="col-md-12">
		<br />
		<table style="width:1024px;">
		<tr>
			<th>
				<ul style="margin-left:7px;" class="pull-left" id="cctv_category"></ul>
			</th>
		</tr>
		<tr>
			<td>
				<div class="input-group" style="margin-left:47px;margin-right: 15px;">
				  <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
				  <input id="search_cctv" type="text" class="form-control" placeholder="Search cctv name or location...">
				</div>
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td>
				<ul id="cctv_list"></ul>
			</td>
		</tr>
		</table>
	</div>
	</div>
</div>

<!-- MODAL CCTV -->
<div class="modal fade" id="cctv_modal" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 id="h_modal_title" class="modal-title">[title]</h4>
            </div>
            <div class="modal-body" >
                <img id="image-body" style="width:568px;height:500px;" class="thumbnail" src="" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div id="camera_cache" style="display:none; visibility:hidden"> 
  <iframe id="core_cache" src="http://rttmc.dephub.go.id/rttmc/livecctv"></iframe>
</div>

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="https://cdn.jsdelivr.net/jwplayer/7.1.4/jwplayer.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.6/js/bootstrap-modal.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript">

	var temp_cookie = $.cookie("_load_");
	if(typeof temp_cookie === 'undefined' || !temp_cookie) {
		setTimeout(function() {
			$.cookie("_load_",1);
			location.reload(); 
		},2000);
	}

	$('img').on("error", function () {
    	this.src = "http://www.techkhoji.com/wp-content/uploads/2013/12/No-Connection-Retry-Error1.png";
	});

	// temporary data
	var data = [[1,"GADOG PUNCAK","JALUR BARAT"],[2,"SIMPANG CIAWI","JALUR BARAT"],[3,"PINTU TOL MERAK","JALUR BARAT"],[4,"TERMINAL MERAK","JALUR BARAT"],[5,"CILEUNYI","JALUR SELATAN"],[6,"CILEUNYI ARAH TASIK","JALUR SELATAN"],[7,"POS POLISI NAGREK","JALUR SELATAN"],[8,"JALUR ALT NAGREK HILIR 1","JALUR SELATAN"],[9,"NAGREK (CAGAK)","JALUR SELATAN"],[10,"CIKALEDONG","JALUR SELATAN"],[11,"PASAR LIMBANGAN","JALUR SELATAN"],[12,"JT GENTONG","JALUR SELATAN"],[13,"PASAR MALANGBONG","JALUR SELATAN"],[14,"CIAMIS","JALUR SELATAN"],[15,"WANGON","JALUR SELATAN"],[16,"SUMPIUH","JALUR SELATAN"],[17,"KARANGANYAR","JALUR SELATAN"],[18,"JT KULWARU","JALUR SELATAN"],[19,"TANJUNGPURA KRW 1","JALUR UTARA"],[20,"JL ALT TENGAH KARAWANG","JALUR UTARA"],[21,"PINTU TOL CIKAMPEK","JALUR UTARA"],[22,"SADANG","JALUR UTARA"],[23,"SIMPANG MUTIARA","JALUR UTARA"],[24,"SIMPANG JOMIN","JALUR UTARA"],[25,"JT BALONGGANDU-INDRAMAYU","JALUR UTARA"],[26,"CIASEM","JALUR UTARA"],[27,"SIMPANG CIASEM","JALUR UTARA"],[28,"PASAR ERETAN","JALUR UTARA"],[29,"PAMANUKAN","JALUR UTARA"],[30,"PATROL","JALUR UTARA"],[31,"JT LOSARANG","JALUR UTARA"],[32,"TEGAL GUBUG","JALUR UTARA"],[33,"PINTU TOL KANCI","JALUR UTARA"],[34,"LOSARI","JALUR UTARA"],[35,"JT TANJUNG-CIREBON","JALUR UTARA"],[36,"JT TANJUNG-BREBES","JALUR UTARA"],[37,"SIMPANG PEJAGAN","JALUR UTARA"],[38,"EXIT TOL PEJAGAN","JALUR UTARA"],[39,"KETANGGUNGAN (BUMIAYU)","JALUR UTARA"],[40,"COMAL","JALUR UTARA"],[41,"TERMINAL BEKASI","TERMINAL"],[42,"TERMINAL JOGJAKARTA","TERMINAL"],[43,"TERMINAL CIREBON","TERMINAL"],[44,"TERMINAL TEGAL","TERMINAL"],[45,"TERMINAL SEMARANG","TERMINAL"],[46,"PADALARANG","JALUR TENGAH"],[47,"SUBANG","JALUR TENGAH"],[48,"NAREGONG (SUMEDANG)","JALUR TENGAH"],[49,"KADIPATEN","JALUR TENGAH"]];

	$("#search_cctv").on('keyup', function() {
	    var search_term = this.value.toLowerCase();
	    var result = $.grep(data, function(el) {
	        return el[1].toLowerCase().indexOf(search_term) > -1;
	    });

	    $("#cctv_list").html('');
		for(var i = 0; i < result.length; i++) {  // iterate camera
			$("#cctv_list").append('<li><div><span class="label label-success"><i class="fa fa-camera"></i> '+result[i][1]+'</span><br /><a  href="javascript::void(0);" onclick="cctvModal('+result[i][0]+',\''+result[i][1]+'\')"><img class="thumbnail" width="177" height="155" src="http://rttmc.dephub.go.id/rttmc/_shared/cctv/img.php?a='+result[i][0]+'" /></a></div></li>');
		}
	});

	setTimeout(function() {
		var category = [];
		$("#core_cache").attr("src","");        // cancel over load                       
		for(var i = 0; i < data.length; i++) {  // iterate camera
			category.push(data[i][2]);
			$("#cctv_list").append('<li><div><span class="label label-success"><i class="fa fa-camera"></i> '+data[i][1]+'</span><br /><a href="javascript:void(0);" onclick="cctvModal('+data[i][0]+',\''+data[i][1]+'\')" data-toggle="modal" data-target="#cctv_list"><img class="thumbnail" width="177" height="155" src="http://rttmc.dephub.go.id/rttmc/_shared/cctv/img.php?a='+data[i][0]+'" /></a></div></li>');
		}

		var category = category.filter(function(elem, index, self) {
		    return index == self.indexOf(elem);
		});

		/* all cctv */
		$("#cctv_category").append('<li><button onclick="filterCategory(\'all\');" class="btn btn-primary btn-sm btn-custom" style="margin-right:4px;font-size:10px;"><i class="fa fa-list"></i> ALL</button></li>');	

		/* filter by category */	
		for(var x = 0; x < category.length; x++) {
			$("#cctv_category").append('<li><button onclick="filterCategory(\''+category[x]+'\');" class="btn btn-primary btn-sm btn-custom" style="margin-right:4px;font-size:10px;"><i class="fa fa-map-pin"></i> '+category[x]+'</button></li>');
		}

		/* situation report */
		$("#cctv_category").append('<li><button onclick="filterCategory(\'drone\');" class="btn btn-primary btn-sm btn-custom" style="margin-right:4px;font-size:10px;"><i class="fa fa-video-camera"></i> DRONE</button></li>');

		/* situation report */
		$("#cctv_category").append('<li><button onclick="filterCategory(\'situation_report\');" class="btn btn-primary btn-sm btn-custom" style="margin-right:4px;font-size:10px;"><i class="fa fa-video-camera"></i> SITUATION REPORT</button></li>');

	},1000);

	function filterCategory(category) {
		$("#cctv_list").html('');

		if(category == 'situation_report') {
			$("#cctv_list").append('<li><div id="mediaplayer"></div></li>');
	        jwplayer('mediaplayer').setup({
	            'playlist': [{
	              'sources': [
	                {
	                  'file': 'rtmp://103.253.107.18/live/situation_report'
	                }
	              ]
	            }],
	            'width': 480,
	            'height': 320
	        });
		}

		if(category == 'drone') {
			alert("Camera not found!");
		}
		
		if(category == 'all') {
			for(var i = 0; i < data.length; i++) {  // iterate camera
				$("#cctv_list").append('<li><div><span class="label label-success"><i class="fa fa-camera"></i> '+data[i][1]+'</span><br /><a  href="javascript::void(0);" onclick="cctvModal('+data[i][0]+',\''+data[i][1]+'\')"><img class="thumbnail" width="177" height="155" src="http://rttmc.dephub.go.id/rttmc/_shared/cctv/img.php?a='+data[i][0]+'" /></a></div></li>');
			}
		}

		for(var i = 0; i < data.length; i++) {  // iterate camera
			if(data[i][2] == category) {
				$("#cctv_list").append('<li><div><span class="label label-success"><i class="fa fa-camera"></i> '+data[i][1]+'</span><br /><a href="javascript::void(0);" onclick="cctvModal('+data[i][0]+',\''+data[i][1]+'\')"><img class="thumbnail" width="177" height="155" src="http://rttmc.dephub.go.id/rttmc/_shared/cctv/img.php?a='+data[i][0]+'" /></a></div></li>');
			}
		}
	}

	var handler; // interval handler

	function cctvModal(id,title) {
		$('html, body').animate({ scrollTop: 0 }, 'fast');
		$('#cctv_modal').modal('show');
		$('#h_modal_title').html('<i class="fa fa-map-pin"></i> '+title);
		var cache = 0;
		clearInterval(handler);
		handler = setInterval(function(){
		    $('#image-body').attr('src', 'http://rttmc.dephub.go.id/rttmc/_shared/cctv/img.php?a='+ id +'#'+cache);
		    cache++;
		},500);
	}

	$('#cctv_modal').on('hidden.bs.modal', function () {
		$('#cctv_list').removeAttr('style');
	    $('.modal-backdrop').remove();
	})

</script>
</html>