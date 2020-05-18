const inputs = document.querySelectorAll(".input");


function addcl(){
	let parent = this.parentNode.parentNode;
	parent.classList.add("focus");
}

function remcl(){
	let parent = this.parentNode.parentNode;
	if(this.value == ""){
		parent.classList.remove("focus");
	}
}


inputs.forEach(input => {
	input.addEventListener("focus", addcl);
	input.addEventListener("blur", remcl);
});

// Video tutorial/codealong here: https://youtu.be/fCpw5i_2IYU

$( '.friend-drawer--onhover' ).on( 'click',  function() {

  $( '.chat-bubble' ).hide('slow').show('slow');

});

// Video tutorial/codealong here: https://youtu.be/fCpw5i_2IYU
