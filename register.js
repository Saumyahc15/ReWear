function togglePassword(fieldId, eyeId) {
  const field = document.getElementById(fieldId);
  const icon = document.getElementById(eyeId);
  const isVisible = field.type === "text";
  field.type = isVisible ? "password" : "text";
  icon.classList.toggle("fa-eye", isVisible);
  icon.classList.toggle("fa-eye-slash", !isVisible);
}

// Password validation
const passwordInput = document.getElementById("reg-password");
const confirmInput = document.getElementById("reg-confirmPassword");

if (passwordInput && confirmInput) {
  passwordInput.addEventListener("input", validatePassword);
  confirmInput.addEventListener("input", validateConfirmPassword);
}

function validatePassword() {
  const password = passwordInput.value;
  const errors = [];
  if (password.length < 8) errors.push("At least 8 characters");
  if (!/[A-Z]/.test(password)) errors.push("1 uppercase");
  if (!/[a-z]/.test(password)) errors.push("1 lowercase");
  if (!/[0-9]/.test(password)) errors.push("1 number");
  if (!/[^A-Za-z0-9]/.test(password)) errors.push("1 special character");
  const errorBox = document.getElementById("reg-passwordErrors");
  errorBox.innerHTML = errors.length ? "Password: " + errors.join(", ") : "";
}

function validateConfirmPassword() {
  const confirmPassword = confirmInput.value;
  const errorBox = document.getElementById("reg-confirmPasswordError");
  if (confirmPassword !== passwordInput.value) {
    errorBox.textContent = "Passwords do not match.";
  } else {
    errorBox.textContent = "";
  }
}

document.getElementById("registerForm").addEventListener("submit", function (e) {
  validatePassword();
  validateConfirmPassword();
  const passwordError = document.getElementById("reg-passwordErrors").textContent;
  const confirmError = document.getElementById("reg-confirmPasswordError").textContent;
  if (passwordError || confirmError) {
    e.preventDefault();
  }
}); 