$('#likeButton').on({
	'click' : function(){
		if($(this).attr("src") == "img/like.svg"){
			$(this).attr("src", "img/hearts.svg");
		} else {
			$(this).attr("src", "img/like.svg");
		}
	}
});

$('#likeButton').hover(function(){
	$(this).attr("src", "img/hearts.svg");
}, function(){
	$(this).attr("src", "img/like.svg");
});