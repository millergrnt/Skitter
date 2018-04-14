$('[id=likeButton]').on({
	'click' : function(){
		if($(this).children("img").attr("src") == "img/like.svg"){
			$(this).children("img").attr("src", "img/hearts.svg");
			var id = $(this).attr("skitID");
			$.post("http://localhost:61234/likeSkit", {"skitID": id, "value": 1}).done(function(){
				location.reload(true);
			});
		} else {
			$(this).children("img").attr("src", "img/like.svg");
			var id = $(this).attr("skitID");
			$.post("http://localhost:61234/likeSkit", {"skitID": id, "value": -1}).done(function(){
				location.reload(true);
			});
		}
	}
});

$("#exitButton").on({
	'click' : function(){
		$("#blur").hide();
	}
});

$("#getSettings").on({
	'click' : function(){
		$("#blur").show();
	}
});

$(window).on( "load", function(){
	$("#blur").hide();
});