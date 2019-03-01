$(".submit").click(function(){
	if (validateRegister()){
		return true;
	}
	return false;
})

$(".login").click(function(){
	if (validateLogin()){
		return true;
	}
	return false;
})

$(document).ready(function(){

	$('#itemslider').carousel({ interval: 3000 });
	
	// $('.carousel-showmanymoveone .item').each(function(){
	// 	var itemToClone = $(this);
		
	// 	for (var i=1;i<10;i++) {
	// 		itemToClone = itemToClone.next();
			
	// 		if (!itemToClone.length) {
	// 			itemToClone = $(this).siblings(':first');
	// 		}
		
	// 		itemToClone.children(':first-child').clone()
	// 		.addClass("cloneditem-"+(i))
	// 		.appendTo($(this));
	// 	}
	// });
});