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