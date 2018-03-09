$('[id=likeButton]').on({
	'click' : function(){
		if($(this).children("img").attr("src") == "img/like.svg"){
			$(this).children("img").attr("src", "img/hearts.svg");
		} else {
			$(this).children("img").attr("src", "img/like.svg");
		}
	}
});