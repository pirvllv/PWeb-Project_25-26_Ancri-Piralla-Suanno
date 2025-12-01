document.addEventListener("contextmenu", function (event) {
    event.preventDefault(); // stops the default browser menu
    alert("Right-click detected!");
  });