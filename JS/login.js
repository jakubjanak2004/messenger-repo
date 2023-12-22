/**
 * Variables
 * Defined
 */
let form = document.getElementById("loggin_form");
let errorParagraph = document.querySelector(".error");

/**
 * Listeners
 * Defined
 */
form.addEventListener("submit", (event) => {
  event.preventDefault();

  const payload = new FormData(form);

  fetch("api/loginApi.php", {
    method: "POST",
    body: payload,
  })
    .then((red) => red.json())
    .then((data) => {
      if (data["logged"]) {
        localStorage.removeItem("user_messaged");
        window.location.replace("index.php");
      } else {
        errorParagraph.innerHTML = data["error"];
      }
    })
    .catch((err) => console.log(err));
});
