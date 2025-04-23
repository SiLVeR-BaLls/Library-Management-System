// Form validation on submit
document
  .getElementById("registration-form")
  .addEventListener("submit", function (event) {
    const password = document.getElementById("password").value;
    const passwordRepeat = document.getElementById("password-repeat").value;
    const errorMessage = document.getElementById("error-message");

    if (password !== passwordRepeat) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Passwords do not match.",
      });
      event.preventDefault(); // Prevent form submission
    } else {
      errorMessage.textContent = ""; // Clear error message
    }
  });

//password visible
function togglePasswordVisibility(passwordId, iconId) {
  const passwordField = document.getElementById(passwordId);
  const icon = document.getElementById(iconId);

  if (passwordField.type === "password") {
    passwordField.type = "text";
    icon.textContent = "📖"; // Open book (showing password)
  } else {
    passwordField.type = "password";
    icon.textContent = "📚"; // Closed book (hiding password)
  }
}

//login
// Function to get the query string parameters
function getQueryParam(param) {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(param);
}

// Function to display error messages
function displayErrorMessage() {
  const error = getQueryParam("error");
  let message = "";

  switch (error) {
    case "wrongpassword":
      message = "Incorrect password. Please try again.";
      break;
    case "wrongusername":
      message = "Username does not exist. Please try again.";
      break;
    default:
      message = "";
  }

  if (message) {
    const overlay = document.createElement("div");
    overlay.className = "overlay";
    document.body.appendChild(overlay);

    const popup = document.createElement("div");
    popup.className = "popup-message";
    popup.innerHTML = `<p>${message}</p>`;
    document.body.appendChild(popup);

    // Display the overlay and pop-up
    overlay.style.display = "block";
    popup.style.display = "block";

    // Close the pop-up when clicking outside of it
    overlay.addEventListener("click", function (event) {
      if (event.target === overlay) {
        overlay.style.display = "none";
        popup.style.display = "none";
      }
    });

    // Hide the pop-up after 5 seconds
    setTimeout(() => {
      overlay.style.display = "none";
      popup.style.display = "none";
    }, 5000);
  }
}

// Call the function to display error message when the page loads
window.onload = displayErrorMessage;

//idno problem


const nextBtn = document.getElementById('nextBtn');
const prevBtn = document.getElementById('prevBtn');
const submitBtn = document.getElementById('submitBtn');
const formSteps = document.querySelectorAll('.form-step');
let currentStep = 0;

nextBtn.addEventListener('click', () => {
    const currentStepFields = formSteps[currentStep].querySelectorAll('[required]');
    let allFieldsValid = true;

    currentStepFields.forEach(field => {
        if (!field.value.trim()) {
            allFieldsValid = false;
            Swal.fire({
                icon: 'error',
                title: 'Field Missing',
                text: `Please fill out the required field: ${field.previousElementSibling.textContent || field.name}`,
                confirmButtonText: 'OK'
            });
        }
    });

    if (allFieldsValid) {
        if (currentStep < formSteps.length - 1) {
            formSteps[currentStep].classList.remove('form-step-active');
            currentStep++;
            formSteps[currentStep].classList.add('form-step-active');
        }
        updateButtons();
    }
});

prevBtn.addEventListener('click', () => {
    if (currentStep > 0) {
        formSteps[currentStep].classList.remove('form-step-active');
        currentStep--;
        formSteps[currentStep].classList.add('form-step-active');
    }
    updateButtons();
});

document.getElementById('resetBtn').addEventListener('click', () => {
    currentStep = 0;
    formSteps.forEach(step => step.classList.remove('form-step-active'));
    formSteps[0].classList.add('form-step-active');
    updateButtons();
});

function updateButtons() {
    nextBtn.style.display = currentStep === formSteps.length - 1 ? 'none' : 'inline';
    submitBtn.style.display = currentStep === formSteps.length - 1 ? 'inline' : 'none';
    prevBtn.disabled = currentStep === 0;
}

updateButtons();
