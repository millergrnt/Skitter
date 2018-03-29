const http = require('http');
const elasticsearch = require('elasticsearch');
const url = require('url');
const hostname = '127.0.0.1';
const port = 61234;
const express = require('express');
const bodyParser = require('body-parser');
var app = express();
app.use( bodyParser.urlencoded({
	extended: true
}));

var skitID = 2;

/*
	Create the elastic search object
*/
var client = new elasticsearch.Client({
	host: 'localhost:9200',
	log: 'trace'
});


/*
	Ping the Elasticsearch Cluster to make sure it is alive and well
*/
client.ping({
	requestTimeout: 10000,
}, function(error) {
	if(error){
		console.error('elasticsearch cluster is down!');
	} else {
		console.log('All is well');
	}
});

/**
	Get rid of any old skits that might be left over from last time I ran the server.
*/
client.search({
	index: 'skits',
	body: {
		query: {
			match: {
				ownerID: 1
			}
		}
	}
}).then(function(resp){
	resp.hits.hits.forEach(function(hit){
		client.delete({
			index: 'skits',
			type: 'skit',
			id: hit._id
		}, function(err, resp, status) {
			console.log(err);
		});
	});
});

/**
	Let's add in some dummy Skits to start with
*/
client.index({
	index: "skits",
	type: "skit",
	body: {
		"skitID": 0,
		"ownerID": 1,
		"content": "Hello World",
		"likes": 69,
		"isReply": "N",
		"replyTo": 0
	}
}, function(err, resp, status) {
	if(err){
		console.log(err);
	}
	console.log(resp);
});

/**
	Anotha one
*/
client.index({
	index: "skits",
	type: "skit",
	body: {
		"skitID": 1,
		"ownerID": 1,
		"content": "This is the second skit",
		"likes": 420,
		"isReply": "N",
		"replyTo": 0
	}
}, function(err, resp, status) {
	if(err){
		console.log(err);
	}
	console.log(resp);
});

/**
	getSkits API
*/
app.get('/getSkits', function (req, res){
	var url_parse = url.parse(req.url, true);
	var query = url_parse.query;
	var hits = "";

	client.search({
		index: 'skits',
		body: {
			sort: [{ "skitID": {"order": "desc"} }],
			query: {
				match: {
					ownerID: parseInt(query.id)
				}
			}
		},
	}).then(function(resp){
		if(resp.hits.total > 0){
			resp.hits.hits.forEach(function(hit){
				res.write(hit._source.ownerID + "," + hit._source.content + "," + hit._source.likes + "\n");
			});
		} else {
			console.log("No skits");
		}
		res.end();
	}, function(err){
		if(err){
			console.trace(err.message);
			res.statusCode = 500;
			res.setHeader('Content-Type', 'text/plain');
		} else {
			res.statusCode = 200;
			res.setHeader('Content-Type', 'text/plain');
		}
	});
})

/**
	addSkits API
*/
app.post('/addSkit', function(req, res){
	if(req.method != 'POST'){
		res.write('Illegal format encountered.');
		res.end();
	}

	var body = '';
	req.on('data', function(data){
		if(data.length > 1e6){
			req.connection.destroy();
		} else {
			body += data;
		}
	});

	req.on('end', function(){
		var post = qs.parse(body);
		res.write("\nQS Parse Data:\n" + post);
		res.end();
	})

	client.index({
		index: "skits",
		type: "skit",
		body: {
			"skitID": skitID++,
			"ownerID": req.body.user_id,
			"content": req.body.content,
			"likes": 0,
			"isReply": "N",
			"replyTo": 0
		},
		refresh: true
	}, function(err, resp, status) {
		if(err){
			res.write("Error creating Skit.");
			res.end();
		} else {
			res.end();
		}
	});
});

app.listen(61234, function(){
	console.log("Dev app listening on 61234");
});