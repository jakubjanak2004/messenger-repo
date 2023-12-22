/**
 * Vars
 * Defined
 */
const form = document.getElementById("signup_form");
const errorParagraph = document.querySelector(".error");
const usernameForm = document.getElementById("username");
const passwordInput = document.getElementById("password");
const passwordInputRepeat = document.getElementById("password_input_repeat");

/**
 * Functions
 * Defined
 */
function passwordEqual() {
  if (!passwordInput.value || !passwordInputRepeat.value) {
    return;
  }

  if (passwordInput.value != passwordInputRepeat.value) {
    passwordInput.classList.add("wrong");
    passwordInputRepeat.classList.add("wrong");
  } else {
    passwordInput.classList.remove("wrong");
    passwordInputRepeat.classList.remove("wrong");
  }
}

/**
 * Listeners
 * Defined
 */
form.addEventListener("submit", (event) => {
  event.preventDefault();

  const payload = new FormData(form);

  fetch("api/signupApi.php", {
    method: "POST",
    body: payload,
  })
    .then((red) => red.json())
    .then((data) => {
      if (data["signed"]) {
        window.location.replace("index.php");
      } else {
        errorParagraph.innerHTML = data["error"];
      }
    })
    .catch((err) => console.log(err));
});

passwordInput.addEventListener("keyup", (e) => {
  passwordEqual();
});

passwordInputRepeat.addEventListener("keyup", (e) => {
  passwordEqual();
});