const http = require('http');
const elasticsearch = require('elasticsearch');
const url = require('url');
const express = require('express');
const bodyParser = require('body-parser');

const hostname = 'serversetup_node_1';
const port = 61234;

var app = express();
app.use( bodyParser.urlencoded({
	extended: true
}));

var skitID = 7;

var client = new elasticsearch.Client({
	host: 'elasticsearch:9200',
	log: 'trace',
	maxRetries: 5,
	requestTimeout: 2000
}, function(err, resp){
	console.log(resp);
	console.log("Error");
	console.log(err);
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
				ownerID: [0,1,2,3,4]
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
		"replyTo": [-1],
		"replies": [1, 2]
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
		"replyTo": [0],
		"replies": [-1]
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
		"replyTo": [0],
		"replies": [-1]
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
		"replyTo": [-1],
		"replies": [-1]
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
		"replyTo": [-1],
		"replies": [-1]
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
		"replyTo": [-1],
		"replies": [6]
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
		"skitID": 6,
		"ownerID": 1,
		"content": "So is nate",
		"replyTo": [5],
		"replies": [-1]
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
var ip = req.connection.remoteAddress;

//Check to make sure we are only allowing docker containers to access node.
if(!ip.startsWith("172.18.0.")){
	res.write("You are not allowed to access this item.");
	res.end();
} else {
	var hostPortion = ip.lastIndexOf(".");
	var hostPortion = ip.slice(hostPortion + 1);
	if(!(hostPortion < 11)){
		res.write("You are not allowed to access this item.");
		res.end();
	}
}

var url_parse = url.parse(req.url, true);
var query = url_parse.query;
var hits = "";
var ids = query.ids.split(',');

//We will search for all the skits that belong to the IDs listed
//this will find not only the owner of that page but also their
//friends if it is the current user's home page. If it is some
//user other than the one currently logged in it will only gather
//their skits
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
		resp.hits.hits.forEach(function(hit){
			res.write(hit._source.ownerID + "," + hit._source.content + "," + hit._source.skitID + "," + hit._source.replyTo + "|" + hit._source.replies + "\n");
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
});


/**
	Gets a reply's Content, and ownerID
*/
app.get('/getReply', function (req, res){
	var ip = req.connection.remoteAddress;

	//Check to make sure we are only allowing docker containers to access node.
	if(!ip.startsWith("172.18.0.")){
		res.write("You are not allowed to access this item.");
		res.end();
	} else {
		var hostPortion = ip.lastIndexOf(".");
		var hostPortion = ip.slice(hostPortion + 1);
		if(!(hostPortion < 11)){
			res.write("You are not allowed to access this item.");
			res.end();
		}
	}

	var url_parse = url.parse(req.url, true);
	var query = url_parse.query;
	var hits = "";

	//Look up the skit by ID
	client.search({
		index: 'skits',
		body: {
			query: {
				match: {
					skitID: query.id
				}
			}
		},
	}).then(function(resp){
		if(resp.hits.total > 0){

			//If we have a hit, and it should only be one then we will return data about that skit for PHP
			resp.hits.hits.forEach(function(hit){
				res.write(hit._source.ownerID + "," + hit._source.content + "," + hit._source.skitID + "\n");
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
});


/*
	Delete Skit API
*/
app.post('/deleteSkit', function(req, res){
	var ip = req.connection.remoteAddress;

	//Check to make sure we are only allowing docker containers to access node.
	if(!ip.startsWith("172.18.0.")){
		res.write("You are not allowed to access this item.");
		res.end();
	} else {
		var hostPortion = ip.lastIndexOf(".");
		var hostPortion = ip.slice(hostPortion + 1);
		if(!(hostPortion < 11)){
			res.write("You are not allowed to access this item.");
			res.end();
		}
	}

	if(req.method != 'POST'){
		res.write('Illegal format encountered.');
		res.end();
	}

	// Make sure the data is not too big and going to ruin server's RAM and DOS us
	var body = '';
	req.on('data', function(data){
		if(data.length > 1e6){
			req.connection.destroy();
		} else {
			body += data;
		}
	});

	//Search the Skit we want to delete
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

			//We have a hit and so now we will check if this skit is a reply
			var originalSkitToUpdate = resp.hits.hits[0]._source.replyTo;

			//Delete the Skit
			client.delete({
				index: 'skits',
				type: 'skit',
				id: resp.hits.hits[0]._id,
				refresh: true
			}, function(err, resp, status) {
				console.log(err);
			});

			//If it is a reply we need to update the one being replied to
			if(originalSkitToUpdate[0] != -1){

				client.search({
					index: 'skits',
					type: 'skit',
					body: {
						query: {
							match: {
								skitID: originalSkitToUpdate[0]
							}
						}
					}
				}).then(function(resp){

					//Make sure we actually have a hit
					if(resp.hits.total != 0){

						//Get its current replies array and splice out the skit we just deleted
						var repliesArr = resp.hits.hits[0]._source.replies;

						if(repliesArr.length == 1){
							repliesArr = [-1];
						} else {
							var index = repliesArr.indexOf(req.body.skitID);
							if(index > -1){
								repliesArr.splice(req.body.skitID, 1);
							} else {
								res.end();
							}
						}

						//Update that original Skit
						client.update({
							index: 'skits',
							type: 'skit',
							id: resp.hits.hits[0]._id,
							body: {
								doc: {
									replies: repliesArr
								}
							},
							refresh: true
						}).then(function(resp){
							res.end();
						});
					} else {
						res.write("Error updating replied to Skit!");
						res.end();
					}
				});


			} else {
				res.write("Hi");
				res.end();
			}
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
	var ip = req.connection.remoteAddress;

	//Check to make sure we are only allowing docker containers to access node.
	if(!ip.startsWith("172.18.0.")){
		res.write("You are not allowed to access this item.");
		res.end();
	} else {
		var hostPortion = ip.lastIndexOf(".");
		var hostPortion = ip.slice(hostPortion + 1);
		if(!(hostPortion < 11)){
			res.write("You are not allowed to access this item.");
			res.end();
		}
	}

	if(req.method != 'POST'){
		res.write('Illegal format encountered.');
		res.end();
	}

	//Make sure the data is not too big to hopefully prevent DOS
	var body = '';
	req.on('data', function(data){
		if(data.length > 1e6){
			req.connection.destroy();
		} else {
			body += data;
		}
	});

	//Skit is too large, deny it
	if(req.body.content.length > 140){
		res.write("Error, creating Skit.");
		res.write("Skit too long");
		res.end();
	}

	//Otherwise, we will need to get the current skitID so that we can add another skit
	//without messing up the ordering. The skitID can be incremented by the Rails server
	//when creating a reply Skit
	client.search({
		index: 'skits',
		body:{
			sort: [{ "skitID": {"order": "desc"} }],
			size: 1,
			query: {"match_all": {}}
		}
	}, function(err, resp, status){
		if(err){
			res.write("Error creating Skit.");
			res.end();
		} else {
			skitID = resp.hits.hits[0]._source.skitID;
		}
	});

	//Create the new skit
	client.index({
		index: "skits",
		type: "skit",
		body: {
			"skitID": skitID + 1,
			"ownerID": req.body.user_id,
			"content": req.body.content,
			"replyTo": -1,
			"replies": [-1]
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

var listener = app.listen(61234, '0.0.0.0', function(){
	console.log("Server started on port: %d", listener.address().port);
	console.log("Address: " + listener.address().address);
});