// Image preview and upload area logic
const itemImage = document.getElementById('item-image');
const imagePreview = document.getElementById('imagePreview');
const imageUploadArea = document.getElementById('imageUploadArea');

itemImage.addEventListener('change', function() {
  const file = this.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      imagePreview.innerHTML = `<img src="${e.target.result}" alt="Item Image" />`;
    };
    reader.readAsDataURL(file);
  } else {
    imagePreview.innerHTML = '<i class="fas fa-plus"></i><span>Add a photo</span>';
  }
});

imagePreview.addEventListener('click', function() {
  itemImage.click();
});

// Optional: AJAX submit (commented, for PHP/SQL backend)
/*
document.getElementById('itemForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  fetch('list-item.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      document.getElementById('successMessage').textContent = 'Item listed successfully!';
      this.reset();
      imagePreview.innerHTML = '<i class="fas fa-plus"></i><span>Add a photo</span>';
    } else {
      document.getElementById('successMessage').textContent = 'Error: ' + data.error;
    }
  })
  .catch(() => {
    document.getElementById('successMessage').textContent = 'An error occurred.';
  });
});
*/ 