// Password match
var password = document.getElementById("pass");
var confirm_password = document.getElementById("confirmpass");

function validatePassword() {
  if(password.value !== confirm_password.value) {
    confirm_password.setCustomValidity("Passwords don't match.");
    confirm_password.style.backgroundColor = "#fff6f6";
    confirm_password.style.color = "red";
  } 
  else {
    confirm_password.setCustomValidity('');
    confirm_password.style.backgroundColor = "white";
    confirm_password.style.color = "black";
  }
}
password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;