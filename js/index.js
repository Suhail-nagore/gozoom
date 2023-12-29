
  const wrapper = document.querySelector(".wrapper"),
    selectBtn = wrapper.querySelector(".select-btn"),
    searchInp = wrapper.querySelector("input"),
    myoption = wrapper.querySelector(".options");

  let countries = ["Mobile Development", "Web Development", "Retail", "Integration", "Others"];

  function addCountry(selectedCountry) {
    myoption.innerHTML = "";
    countries.forEach(country => {
      let isSelected = country == selectedCountry ? "selected" : "";
      let li = `<li onclick="updateName(this)" class="${isSelected}">${country}</li>`;
      myoption.insertAdjacentHTML("beforeend", li);
    });
  }
  addCountry();

  function updateName(selectedLi) {
    searchInp.value = "";
    addCountry(selectedLi.innerText);
    wrapper.classList.remove("active");
    var hello = selectBtn.firstElementChild.innerText = selectedLi.innerText;
    var inputEle = document.getElementById("selecti");
    inputEle.value = selectBtn.firstElementChild.innerText ;
    console.log(hello)
  
  }

  searchInp.addEventListener("keyup", () => {
    let arr = [];
    let searchWord = searchInp.value.toLowerCase();
    arr = countries.filter(data => {
      return data.toLowerCase().startsWith(searchWord);
    }).map(data => {
      let isSelected = data == selectBtn.firstElementChild.innerText ? "selected" : "";
      return `<li onclick="updateName(this)" class="${isSelected}">${data}</li>`;
    }).join("");
    myoption.innerHTML = arr ? arr : `<p style="color: #333; font-size:13px;">Oops! Service not found</p>`;
  });

  selectBtn.addEventListener("click", () => wrapper.classList.toggle("active"));

  const emailInput = document.getElementById("email");
  const emailInputs = document.getElementById("email_ef6491f");
  const phoneInput = document.getElementById("contact-number");
  const phoneInputs = document.getElementById("contact-numbers");
  const errorMessage = document.getElementById("errorMessage");
  const errorMessages = document.getElementById("errorMessages");

  emailInput.addEventListener("input", validateEmail);
  emailInputs.addEventListener("input", validateEmail);
  phoneInput.addEventListener("input", validatePhone);
  phoneInputs.addEventListener("input", validatePhone);
  const phonePattern = /^\d{10}$/;
  const phoneValue = phoneInput.value.trim();
  const phoneValues = phoneInputs.value.trim();

  function validateEmail() {
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

    if (!emailPattern.test(emailInput.value)) {
      errorMessage.style.visibility = "visible";
      emailInput.style.borderColor = "red";
    } else {
      errorMessage.style.visibility = "hidden";
      emailInput.style.borderColor = "initial";
    }
    if (!emailPattern.test(emailInputs.value)) {
      errorMessages.style.visibility = "visible";
      emailInputs.style.borderColor = "red";
    } else {
      errorMessages.style.visibility = "hidden";
      emailInputs.style.borderColor = "initial";
    }
  }
  function validatePhone() {
    const phonePattern = /^\+?\d{10,}$/;
    let phoneValue = phoneInput.value.trim();
    let phoneValues = phoneInputs.value.trim();

    // Remove non-numeric characters
    phoneValue = phoneValue.replace(/[^\d+]/g, '');
    phoneValues = phoneValues.replace(/[^\d+]/g, '');

    //   if (phoneValue.length > 10) {
    //     phoneValue = phoneValue.substr(0, 10);
    //   }

    phoneInput.value = phoneValue;
    phoneInputs.value = phoneValues;


  }
