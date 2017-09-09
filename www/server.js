/**
 * npm install clarifai
 * npm install rtsp-ffmpeg
 */
 const Clarifai = require('clarifai');
 var express = require('express');
 var path = require('path');
 var app = express();
 var server = require('http').Server(app);
 var io = require('socket.io')(server);
 var rtsp = require('rtsp-ffmpeg');
 var numeral = require('numeral');
 var request = require('request').defaults({ encoding: null }); 

 // set the view engine to ejs
app.set('view engine', 'ejs');

// buat folder assets
app.use(express.static(__dirname + '/public'));

// mysql database
var mysql = require('mysql');

var con = mysql.createConnection({
  host: "localhost",
  user: "root",
  password: "",
  database: "db_cctv"
});

con.connect(function(err) {
  if (err) throw err;
  //console.log("Mysql database is connected!");
});

// use rtsp = require('rtsp-ffmpeg') instead if you have install the package
server.listen(6147, function(){
	//console.log('Listening on localhost:6147');
});

// POST
var bodyParser = require('body-parser')
app.use( bodyParser.json() );       // to support JSON-encoded bodies
app.use(bodyParser.urlencoded({     // to support URL-encoded bodies
  extended: true
}));

//------------------------------------------------------------------/
// AI using Clarifai
//------------------------------------------------------------------/

const clarifai = new Clarifai.App({
 apiKey: 'af3fa551cb3e4dc594578017abf25588' // this app using api-key from fajarrdp@gmail.com
});

// Prediction on general model using video API
//clarifai.models.predict(Clarifai.GENERAL_MODEL, 'https://samples.clarifai.com/3o6gb3kkXfLvdKEZs4.gif', {video: true}).then(log).catch(log);

// Provide feedback
// predict the contents of an image by passing in a url

var threshold = 0.92824214; /* limit floor */
// callback(value (float),status(boolean));
function b64Clarifai(base64Image,callback) {
	var result = 0;
	clarifai.models.predict('ambulance', base64Image).then(
	  function(response) {
	  	var arr = response.rawData.outputs[0].data.concepts;
	  	console.log(arr);
	  	if(arr instanceof Array) {
	  		// iterate
	  		for(var i = 0; i < arr.length; i++) {
	  			// search ambulance only
	 	  		if(arr[i].id == 'ambulance') {
		  			var val = numeral(arr[i].value).value();
		  			result = val;
		  			if(val > threshold && val < 2) {
		  				console.log("Ambulance detected!"); // debug
		  				callback(val,true); // do something
		  			} else {
		  				callback(val,false); // do something
		  				console.log("Ambulance not detected!"); // debug
		  			}
		  		}
	  		}
	  	}
	  },
	  function(err) {
	    console.error(err);
	  }
	);
	return result;
}

// use for scanning by url
function scanningByImageUrl(url) {
	request.get(url, function (error, response, body) {
	    if (!error && response.statusCode == 200) {
	        var data = new Buffer(body).toString('base64');
	        b64Clarifai(data,function(value,status) {
	        	console.log("value is : "+value);
	        });
	    }
	});
}

scanningByImageUrl('https://fixyourcoffee.files.wordpress.com/2008/03/photo_8861_200711142.jpg');	

// INIT Camera Sources
// create rtsp source when init 
con.query("SELECT CONCAT('camera',id) as camera, rtsp as source FROM cctv_source", function (err, _result, fields) {
	if (err) throw err;

		_result.map(function(uri, i) {

			var result = {};

				//console.log("uri source : "+uri.source+" , uri camera : "+uri.camera);
				
				// Start camera stream
				result.stream = new rtsp.FFMpeg({input: uri.source, resolution: '320x240', quality: 3});
				result.stream.on('start', function() {
					//console.log('stream ' + uri.camera + ' started');
				});
				result.stream.on('stop', function() {
					//console.log('stream ' + uri.camera + ' stopped');
				});

				// Namespace socket.io
				// example /camera[n]
				io.of("/"+uri.camera).on('connection', function(wsocket) {
				 	var pipeStream = function(data) {
				 	  var b64str = data.toString('base64');
				 	  wsocket.emit('data', b64str);
				 	  //b64Clarifai(b64str);

				 	  // if using base64 bytes
				 	  //var buf = new Buffer(b64str, 'base64');
				 	  //console.log(buf);
				 	};
				 	result.stream.on('data', pipeStream);
				 	wsocket.on('disconnect', function() {
				 	  result.stream.removeListener('data', pipeStream);
				 	});
				});

				// Camera name
				result.camera = uri.camera;

				return result;
		});

		io.on('connection', function(socket) {
			socket.emit('start', _result.length);
		});
});

//------------------------------------------------------------------/
// Controllers
//------------------------------------------------------------------/

// Routes
app.get('/', function (req, res) {
	res.render("pages/index");
});

app.get('/createGroup', function (req, res) {
	res.render("pages/createGroup");
});

app.get('/listGroup', function (req, res) {
	con.query("SELECT * FROM cctv_group", function (err, result, fields) {
		if (err) throw err;
		res.render("pages/listGroup", {
			data : result
		});
	});
});

app.get('/deleteGroup/:id', function (req, res) {
	con.query("DELETE FROM cctv_group WHERE id = '"+req.params.id+"'", function (err, result, fields) {
		if (err) throw err;
		res.redirect('/listGroup');
	});
})

app.get('/editGroup/:id',function(req, res) {
	res.render("pages/editGroup");
});

app.get('/lihatCCTV/:id',function(req, res) {
	res.render("pages/lihatCCTV", {
		data : req.params.id
	});
});

// event to save group of cctv
app.post('/saveGroup',function(req, res) {
	con.query("INSERT INTO cctv_group (group_name) VALUES ('"+req.body.groupName+"')", function (err, result, fields) {
		if (err) throw err;
		var lastInsertId = result.insertId;
		for(var i = 0; i < req.body.selectCCTV.length; i++) {
			con.query("INSERT INTO cctv_detail (cctv_group_id, cctv_source_id) VALUES ('"+lastInsertId+"','"+req.body.selectCCTV[i]+"')", function (err, result, fields) {
				if (err) throw err;
			});
		}
		res.redirect('/listGroup');
	});
});


//------------------------------------------------------------------/
// Rest Services
//------------------------------------------------------------------/

app.get('/getAllCCTV', function (req, res) {
	con.query("SELECT * FROM cctv_source", function (err, result, fields) {
		if (err) throw err;
		res.setHeader('Content-Type', 'application/json');
		res.send(result);
	});
});

app.get('/getCCTVByID/:id', function (req, res) {
	con.query("SELECT * FROM cctv_source WHERE id = '"+req.params.id+"'", function (err, result, fields) {
		if (err) throw err;
		res.setHeader('Content-Type', 'application/json');
		res.send(result);
	});
});

app.get('/getCCTVByGroupId/:id',function(req, res) {
	con.query(
		"SELECT *, cctv_source.id as ctmid, cctv_group.id as cgid, cctv_detail.id as cdid FROM cctv_detail LEFT JOIN cctv_group ON cctv_group.id = cctv_detail.cctv_group_id LEFT JOIN cctv_source ON cctv_source.id = cctv_detail.cctv_source_id WHERE cctv_group.id = '"+req.params.id+"' ", function (err, result, fields) {
		if (err) throw err;
		res.setHeader('Content-Type', 'application/json');
		res.send(result);
	});
});