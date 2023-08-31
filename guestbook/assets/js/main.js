  $(document).ready(function(){
	  $(".iframe").fancybox();
	  $(".picimg").fancybox();
      $("#textmsg").fancybox({ padding: 0, border: 1, margin: 10 });
	  $("#textmsg").trigger('click');
  });

  function confirmSubmit(imsg,ihref) {
	  var smsg = confirm(imsg);
	  if (smsg == true) {
		  window.location=ihref;
	  } else {
	    return false;	
	  }
  }

  function validatePwd(form) {
    with (window.document.password) {
      if (cname.value == "") {
        alert('Please enter a name!');
        cname.focus();
        return false;
      }
      if (cpwd.value == "") {
        alert('Please enter a password!');
        cpwd.focus();
        return false;
      }
      if (cpwd2.value == "") {
        alert('Please enter the password again!');
        cpwd2.focus();
        return false;
      }
      if (cpwd.value != cpwd2.value) {
        alert('Passwords did not match\, please try again!');
        cpwd.focus();
        return false;
      }
      return true;
    }
  }

  function textCounter(field, countfield, maxlimit) {
    if (field.value.length > maxlimit) // if too long...trim it!
      field.value = field.value.substring(0, maxlimit);
    else // otherwise, update 'characters left' counter
      countfield.value = maxlimit - field.value.length;
}

function togglePass(arg1, arg2) {
    var x = document.getElementById(arg1);
    var y = document.getElementById(arg2);
    if (x.type === "password") {
        x.type = "text";
        y.className = "fa fa-eye shpwd";
    } else {
        x.type = "password";
        y.className = "fa fa-eye-slash shpwd";
    }
}
