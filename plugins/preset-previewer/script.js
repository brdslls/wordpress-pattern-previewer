document
  .querySelector(".pp-button--setting")
  .addEventListener("click", function () {
    // document.body.classList.add("overflow-hidden");
    document
      .querySelector(".pp-holder--trigger")
      .classList.remove("pp-holder--open");
    document.querySelector(".pp-holder--main").classList.add("pp-holder--open");
  });

let closeBtns = document.querySelectorAll(".pp-button--close");
for (const closeBtn of closeBtns) {
  closeBtn.addEventListener("click", function () {
    document
      .querySelector(".pp-holder--main")
      .classList.remove("pp-holder--open");
    document
      .querySelector(".pp-holder--trigger")
      .classList.add("pp-holder--open");
    // document.body.classList.remove("overflow-hidden");
  });
}
