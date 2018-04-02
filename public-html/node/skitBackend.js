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

var skitID = 6;

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
	This is for testing purposes only
*/
client.search({
	index: 'skits',
	body: {
		query: {
			terms: {
				ownerID: [1,2,3,4]
			}
		}
	}
}).then(function(resp){
	console.log(resp);
	console.log(resp);
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

client.index({
	index: "skits",
	type: "skit",
	body: {
		"skitID": 2,
		"ownerID": 2,
		"content": "Save your money!!",
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

client.index({
	index: "skits",
	type: "skit",
	body: {
		"skitID": 3,
		"ownerID": 3,
		"content": "Pittsbugh is the best",
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

client.index({
	index: "skits",
	type: "skit",
	body: {
		"skitID": 4,
		"ownerID": 4,
		"content": "My name is mark markimark",
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

client.index({
	index: "skits",
	type: "skit",
	body: {
		"skitID": 5,
		"ownerID": 3,
		"content": "Matt is a piece of shit",
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
	var ids = query.ids.split(',');

	client.search({
		index: 'skits',
		body: {
			sort: [{ "skitID": {"order": "desc"} }],
			query: {
				terms: {
					ownerID: ids
				}
			}
		},
	}).then(function(resp){
		if(resp.hits.total > 0){
			console.log(resp.hits);
			resp.hits.hits.forEach(function(hit){
				res.write(hit._source.ownerID + "," + hit._source.content + "," + hit._source.likes + "," + hit._source.skitID + "\n");
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
			res.end();
		} else {
			res.statusCode = 200;
			res.setHeader('Content-Type', 'text/plain');
			res.end();
		}
	});
})


/*
	Delete Skit API
*/
app.post('/deleteSkit', function(req, res){
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

	client.search({
		index: 'skits',
		body: {
			query: {
				match: {
					skitID: req.body.skitID,
					ownerID: req.body.owner_id
				}
			}
		}
	}).then(function(resp){
		if(resp.hits.total == 1){
			console.log(resp.hits.hits[0]._source);
			client.delete({
				index: 'skits',
				type: 'skit',
				id: resp.hits.hits[0]._id,
				refresh: true
			}, function(err, resp, status) {
				console.log(err);
			});
			res.end();
		} else {
			res.write("Error deleting Skit.");
			res.end();
		}
	});
});


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