// Email match
var email = document.getElementById("email");
var confirm_email = document.getElementById("confirmemail");

function validateEmail() {
  if(email.value !== confirm_email.value) {
    confirm_email.setCustomValidity("Emails don't match.");
    confirm_email.style.backgroundColor = "#fff6f6";
    confirm_email.style.color = "red";
  } 
  else {
    confirm_email.setCustomValidity('');
    confirm_email.style.backgroundColor = "white";
    confirm_email.style.color = "black";
  }
}
email.onchange = validateEmail;
confirm_email.onkeyup = validateEmail;