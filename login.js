function togglePassword(fieldId, eyeId) {
  const field = document.getElementById(fieldId);
  const icon = document.getElementById(eyeId);
  const isVisible = field.type === "text";
  field.type = isVisible ? "password" : "text";
  icon.classList.toggle("fa-eye", isVisible);
  icon.classList.toggle("fa-eye-slash", !isVisible);
}

document.getElementById("loginForm").addEventListener("submit", function (e) {
  // Example: Prevent default for demo, add your own login logic here
  e.preventDefault();
  alert("Login submitted!");
}); 