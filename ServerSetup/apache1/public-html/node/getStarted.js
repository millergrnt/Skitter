const http = require('http');
const elasticsearch = require('elasticsearch');
const url = require('url');

const hostname = '127.0.0.1';
const port = 61234;

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


client.index({
	index: "skits",
	type: "skit",
	body: {
		"skitID": 1,
		"ownerID": 1,
		"content": "Hello World",
		"likes": 17,
		"isReply": "N",
		"replyTo": 0
	}
}, function(err, resp, status) {
	if(err){
		console.log(err);
	}
	//console.log(resp);
})


/*
	This gets run everytime Node receives a request
*/
const server = http.createServer((req, res) => {
	var url_parse = url.parse(req.url, true);
	var query = url_parse.query;
	var hits = "";

	client.search({
		index: 'skits',
		body: {
			query: {
				match: {
					ownerID: parseInt(query.id)
				}
			}
		}
	}).then(function(resp){
		if(resp.hits.total > 0){
			resp.hits.hits.forEach(function(hit){
				res.write("[" + hit._source.ownerID + "," + hit._source.content + "," + hit._source.likes + "]\n");
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
}).listen(port);

