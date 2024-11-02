function togglePasswordVisibility() {
   var showHidePasswordButton = document.getElementById("show_hide_password_image");
   var passwordField = document.getElementById("password_field");
   var currentButtonSrc = showHidePasswordButton.src;
   var pathArray = currentButtonSrc.split("/");
   var currentButtonSrcFilename = pathArray[pathArray.length - 1];
   if (currentButtonSrcFilename == "password_hidden.png") {
      showHidePasswordButton.src = "import/img/password_visible.png";
      passwordField.type = "text";
   }
   else if (currentButtonSrcFilename == "password_visible.png") {
      showHidePasswordButton.src = "import/img/password_hidden.png";
      passwordField.type = "password";
   }
}
