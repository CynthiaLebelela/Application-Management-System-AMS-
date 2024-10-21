//JavaScript to show password
 function togglePassword() {
 var passwordField = document.getElementById("passwordField");
 if (passwordField.type === "password") {
 passwordField.type = "text";
 } else {
passwordField.type = "password";
}
 }

 //JavaScript to change background color and light is the classname of the body
 function changeTheme(){
    let body = document.querySelector("body");

    if(body.classList.contains("light")){
        body.classList.remove("light");
    }
    else{
        body.classList.add("light")
        }
 }
 let button=document.querySelector("lightMode"); //classname for the change background-color button
 button.addEventListener("click",changeTheme);
