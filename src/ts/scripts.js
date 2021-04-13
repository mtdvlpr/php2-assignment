// Responsive menu
function showMenu() {
  var nav = document.getElementById("myTopnav");
  nav.className === "topnav" ? nav.className += " responsive" : nav.className = "topnav";
}

/* Scroll back button */
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.body.scrollTop > 350 || document.documentElement.scrollTop > 350) {
        document.getElementById("scrollbtn").style.display = "block";
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 110) {
            document.getElementById("scrollbtn").style.position = "absolute";
            document.getElementById("scrollbtn").style.bottom = "160px";
        } else {
            document.getElementById("scrollbtn").style.position = "fixed";
            document.getElementById("scrollbtn").style.bottom = "40px";
        }
    } else {
        document.getElementById("scrollbtn").style.display = "none";
    }
}

function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

// File upload
var inputs = document.querySelectorAll('.inputpic');
Array.prototype.forEach.call(inputs, function(input) {
  var label	 = input.nextElementSibling, labelVal = label.innerHTML;
  input.addEventListener('change', function(e) {
    var fileName = '';
    fileName = e.target.value.split( '\\' ).pop();
    if(fileName) label.querySelector( 'span' ).innerHTML = fileName;
    else label.innerHTML = labelVal;
  });
});

/* Prevent asking for form resubmission */
if (window.history.replaceState) {
  window.history.replaceState(null, null, window.location.href);
}