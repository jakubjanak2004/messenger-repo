/**
 * Disbaling the PHP Default Paging
 * JS has its own paging
 */
if (!window.location.search.includes("user_p=none")) {
  window.location.replace("chats.php?user_p=none");
}

/**
 * Vars
 * Defined
 */
const messageArrow = document.querySelector(".chats_profile_bar p");

const searchPanel = document.querySelector("#search_panel");
const searchPanelLis = document.querySelectorAll("#search_panel li");

const chatsPanel = document.getElementById("chats_panel");
const chatsPanelLi = document.querySelectorAll("#chats_panel li");
const chatsPanelAnchor = document.querySelectorAll(
  "#chats_panel a:not(#higher):not(#lower)"
);

const chat = document.querySelector("#chat");
const chatUl = document.querySelector("#chat ul");

const messageForm = document.getElementById("message_form");
const messageFormInput = document.querySelector(
  "#message_form input[type=text]"
);

const ProfileInfoImg = document.querySelector("#profile_info img");
const ProfileInfoH1 = document.querySelector("#profile_info h1");

const searchUserForm = document.getElementById("search_user_form");
const searchUserInput = document.querySelector("#search_user_form input");

let moveDown = document.createElement("li");
let moveUp = document.createElement("li");

let anchor1 = document.createElement("a");
let anchor2 = document.createElement("a");

let page = 1;

const WINDOW_WIDTH_RESPONSIVE = 900;
const USERS_PER_PAGE = 10;

let fetchedData = {
  length: 0,
};

/**
 * Functions
 * Defined
 */
const moveMesssagesDown = () => {
  chatUl.scrollTop = chatUl.scrollHeight;
};

const searchPanelClicked = () => {
  if (window.innerWidth > WINDOW_WIDTH_RESPONSIVE) {
    return;
  }
  searchPanel.classList.toggle("active");
  chat.classList.toggle("not_active");
  messageArrow.classList.toggle("rotated");
};

const pageUsers = (page) => {
  moveUp.classList.remove("hidden");
  moveDown.classList.remove("hidden");

  count = 1;
  chatsPanelLi.forEach((user) => {
    user.classList.remove("hidden");
  });
  chatsPanelLi.forEach((user) => {
    if (
      count > USERS_PER_PAGE * page ||
      count < USERS_PER_PAGE * page - USERS_PER_PAGE + 1
    ) {
      user.classList.add("hidden");
    }

    count++;
  });

  if (page <= 1) {
    moveUp.classList.add("hidden");
  }

  if (USERS_PER_PAGE * page >= chatsPanelLi.length) {
    moveDown.classList.add("hidden");
  }
};

/**
 * Listeners
 * Defined
 */
window.addEventListener("resize", (event) => {
  if (window.innerWidth > WINDOW_WIDTH_RESPONSIVE) {
    searchPanel.classList.remove("active");
    chat.classList.remove("not_active");
  }
});

messageArrow.addEventListener("click", (e) => {
  searchPanelClicked();
});

/**
 * Ajax
 * Defined
 */
searchUserInput.addEventListener("keyup", (event) => {
  if (searchUserInput.value == "") {
    pageUsers(page);
    return;
  }

  const payload = new FormData(searchUserForm);

  fetch("api/searchUsersApi.php", {
    method: "POST",
    body: payload,
  })
    .then((red) => red.json())
    .then((data) => {
      searchPanel.querySelectorAll("li").forEach((listItem) => {
        let leave = false;

        data.forEach((element) => {
          if (
            listItem.querySelector("a").getAttribute("data-email") ==
            element["email"]
          ) {
            leave = true;
          }
        });

        if (!leave) {
          listItem.classList.add("hidden");
        } else {
          listItem.classList.remove("hidden");
        }
      });
    })
    .catch((err) => console.log(err));
});

searchUserForm.addEventListener("submit", (e) => {
  e.preventDefault();
});

chatsPanelAnchor.forEach((element) => {
  element.addEventListener("click", (event) => {
    event.preventDefault();

    if (element.classList.contains("active")) {
      searchPanelClicked();
      return;
    }

    element.classList.add("active");

    chatsPanelAnchor.forEach((elem) => {
      if (elem != element) {
        elem.classList.remove("active");
      }
    });

    let formData = new FormData();
    formData.append("user_messaged", element.getAttribute("data-email"));

    localStorage.setItem("user_messaged", element.getAttribute("data-email"));

    fetch("api/getChatApi.php", {
      method: "POST",
      body: formData,
    })
      .then((red) => red.json())
      .then((data) => {
        chatUl.innerHTML = "";

        Object.keys(data).forEach((key) => {
          chatUl.innerHTML += data[key];
        });

        searchPanelClicked();
        moveMesssagesDown();
      })
      .catch((err) => console.log(err));

    fetch("api/getUserApi.php", {
      method: "POST",
      body: formData,
    })
      .then((red) => red.json())
      .then((data) => {
        let username = data["username"];
        let userPic = data["userpic"];

        ProfileInfoImg.src = userPic;
        ProfileInfoH1.textContent = username;
      })
      .catch((err) => console.log(err));
  });
});

messageForm.addEventListener("submit", (e) => {
  e.preventDefault();

  if (!messageFormInput.value || messageFormInput.value == " ") {
    return;
  }

  const payload = new FormData(messageForm);
  payload.append("user_messaged", localStorage.getItem("user_messaged"));

  fetch("api/sendMessageApi.php", {
    method: "POST",
    body: payload,
  })
    .then((red) => red.json())
    .then((data) => {
      chatUl.innerHTML = "";

      Object.keys(data).forEach((key) => {
        chatUl.innerHTML += data[key];
      });

      moveMesssagesDown();
    })
    .catch((err) => console.log(err));

  messageForm.querySelector("input").value = "";
});

/**
 * Managing the user_messaged
 *
 */
if (localStorage.getItem("user_messaged")) {
  searchPanelLis.forEach((element) => {
    let anchor = element.querySelector("a");

    if (
      anchor.getAttribute("data-email") == localStorage.getItem("user_messaged")
    ) {
      anchor.classList.add("active");

      let formData = new FormData();
      formData.append("user_messaged", anchor.getAttribute("data-email"));

      localStorage.setItem("user_messaged", anchor.getAttribute("data-email"));

      fetch("api/getChatApi.php", {
        method: "POST",
        body: formData,
      })
        .then((red) => red.json())
        .then((data) => {
          chatUl.innerHTML = "";

          Object.keys(data).forEach((key) => {
            chatUl.innerHTML += data[key];
          });

          moveMesssagesDown();
        })
        .catch((err) => console.log(err));

      fetch("api/getUserApi.php", {
        method: "POST",
        body: formData,
      })
        .then((red) => red.json())
        .then((data) => {
          let email = data["email"];
          let username = data["username"];
          let userPic = data["userpic"];

          ProfileInfoImg.src = userPic;
          ProfileInfoH1.textContent = username;
        })
        .catch((err) => console.log(err));
    }
  });
}

/**
 * Setting Interval for message update
 * Update after 1000ms
 */
setInterval(() => {
  if (!localStorage.getItem("user_messaged")) {
    return;
  }

  let formData = new FormData();
  formData.append("user_messaged", localStorage.getItem("user_messaged"));

  fetch("api/getChatApi.php", {
    method: "POST",
    body: formData,
  })
    .then((red) => red.json())
    .then((data) => {
      if (fetchedData.length == Object.keys(data).length) {
        return;
      }

      fetchedData.length = Object.keys(data).length;

      fetch("api/getChatApi.php", {
        method: "POST",
        body: formData,
      })
        .then((red) => red.json())
        .then((data) => {
          chatUl.innerHTML = "";

          Object.keys(data).forEach((key) => {
            chatUl.innerHTML += data[key];
          });
        })
        .catch((err) => console.log(err));
    })
    .catch((err) => console.log(err));
}, 1000);

/**
 * Paging in JS
 *
 */
anchor1.href = "chats.php";
anchor1.innerHTML = "&darr;";
anchor1.id = "lower";

anchor2.href = "chats.php";
anchor2.innerHTML = "&uarr;";
anchor2.id = "higher";

moveDown.appendChild(anchor1);
moveUp.appendChild(anchor2);

chatsPanel.appendChild(moveDown);
chatsPanel.prepend(moveUp);

anchor1.addEventListener("click", (e) => {
  e.preventDefault();

  page++;
  pageUsers(page);
});

anchor2.addEventListener("click", (e) => {
  e.preventDefault();

  page--;
  pageUsers(page);
});

//call pageUsers on load to manage the paging
pageUsers(page);
