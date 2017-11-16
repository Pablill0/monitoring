//variables
var sideMenuVisible = false;

//initialize page
function init() {
	console.log('Initializing page...');
	//check authentication
	if (sessionStorage.authenticated) {
		//user info
		document.getElementById('username').innerHTML = sessionStorage.userName;
	}
	else{	
		//redirect to login
		window.location = 'login.html';
	}
}

//show menu
function showMenu() {
	//menu closed
	if (!sideMenuVisible) {
		sideMenuVisible = true;
		document.getElementById('sidemenu').style.display = 'inline';
		document.getElementById('content').style.width = '80%';
	}
	else {
		sideMenuVisible = false;
		document.getElementById('sidemenu').style.display = 'none';
		document.getElementById('content').style.width = '100%';
	}
}