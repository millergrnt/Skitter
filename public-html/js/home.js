$('[id=likeButton]').on({
	'click' : function(){
		if($(this).children("img").attr("src") == "img/like.svg"){
			$(this).children("img").attr("src", "img/hearts.svg");
		} else {
			$(this).children("img").attr("src", "img/like.svg");
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