function redborder(id){
	id.style.borderColor="red";
}

function greenborder(id){
	id.style.borderColor="green";
}

function blurFunction(id){
	var obj = document.getElementById(id);
	if (!checkTextBlankById(id)){
		redborder(obj);
	}
	else{
		if (id=="email"){
			if (!checkEmailFormat()){
				redborder(obj);
			}
			else{
				greenborder(obj);
			}
		}
		if (id=="username"){
			if (!checkUsernameFormat()){
				redborder(obj);
			}
			else{
				greenborder(obj);
			}
		}
	}
}

function checkTextBlankById(obj_id ,msg){
	var obj = document.getElementById(obj_id);
	if(obj.value.length ==0){
		redborder(obj);


		return false;
	}
	greenborder(obj);
	return true;
}

function checkPasswordEqual(){
	var strpass1=document.getElementById('password').value;
	var strpass2=document.getElementById('cpassword').value;
	var obj=document.getElementById('cpassword');
	if (strpass1 == strpass2){
		greenborder(obj);
		return true;
	}
	redborder(obj);
	return false;
}

function checkEmailFormat(){
	var obj = document.getElementById('email');
	var str = obj.value;
	var pattern = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/; 
	if (!pattern.test(str)){
		redborder(obj);
		return false;
	}
	greenborder(obj);
	return true;
}

function checkUsernameFormat(){
	var obj=document.getElementById('username');
	var str=obj.value;
	var pattern=/^[a-z]+$/;

	if (!pattern.test(str)){
		redborder(obj);
		return false;
	}
	greenborder(obj);
	return true;
}

function validateRegister(){
	var emailblank, usernameblank, passwordblank, cpasswordblank;
	emailformat=checkEmailFormat();
	emailblank=checkTextBlankById('email');
	usernameblank=checkTextBlankById('username');
	passwordblank=checkTextBlankById('password');
	cpasswordblank=checkTextBlankById('cpassword');

	if (!emailblank){
		document.getElementById('email').placeholder="Email cannot be blank.";
	}
	else{
		if (!emailformat){
			var obj = document.getElementById('email');
			redborder(obj);
			// obj.value='';
			// document.getElementById('email').placeholder="Wrong email format.";
		}
	}
	if (!usernameblank){
		document.getElementById('username').placeholder="Username cannot be blank.";
	}
	if (!passwordblank){
		var obj = document.getElementById('cpassword');
		// redborder(obj);
		obj.style.borderColor="red";
		// ^^^^^ LI PAS P VIN ROUZ
		// document.getElementById('cpassword').placeholder="Password cannot be blank.";
		// document.getElementById('cpassword').style.borderColor="red";
		document.getElementById('cpassword').placeholder="Password cannot be blank.";		
		document.getElementById('password').placeholder="Password cannot be blank.";

	}
	if (!cpasswordblank){
		if (!passwordblank){
			var obj = document.getElementById('cpassword');
			redborder(obj);
		}
		else{
			document.getElementById('cpassword').placeholder="Please confirm password.";
		}

	}
	if (!checkPasswordEqual()){
		var obj = document.getElementById('cpassword');
		redborder(obj);
		if (!cpasswordblank){
			obj.placeholder="Please confirm password.";
		}
		else{
			obj.value='';
			obj.placeholder="Passwords do not match. Try again.";
		}
	}
	if (emailblank && usernameblank && passwordblank && cpasswordblank && checkPasswordEqual() && checkEmailFormat()){
		return true;
	}
	return false;
}

function validateLogin(){
	var usernameblank,passwordblank;
	usernameblank=checkTextBlankById('username');
	passwordblank=checkTextBlankById('password');
	if (!usernameblank){
		document.getElementById('username').placeholder="Please enter username.";
	}
	if (!passwordblank){
		document.getElementById('password').placeholder="Please enter password.";
	}
	if (usernameblank && passwordblank){
		return true;
	}
	return false;
}

function incorrectUsername(){
	var obj=document.getElementById('username');
	redborder(obj);
	obj.value='';
	obj.placeholder="Incorrect username"
}