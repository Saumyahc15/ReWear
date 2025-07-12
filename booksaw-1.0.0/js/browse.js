document.addEventListener('DOMContentLoaded', function() {
  // Carousel functionality
  const carouselImages = document.querySelectorAll('.carousel-img');
  let currentCarousel = 0;
  setInterval(() => {
    carouselImages.forEach(img => img.style.display = 'none');
    currentCarousel = (currentCarousel + 1) % carouselImages.length;
    carouselImages[currentCarousel].style.display = 'block';
  }, 3000);

  // Search and Category functionality
  const searchInput = document.getElementById('searchInput');
  const searchBtn = document.getElementById('searchBtn');
  const productsGrid = document.getElementById('productsGrid');
  const searchResultMsg = document.getElementById('searchResultMsg');
  const categoryCards = document.querySelectorAll('.category-card');
  let selectedCategory = '';

  function filterProducts() {
    const query = searchInput.value.toLowerCase();
    const cards = productsGrid.querySelectorAll('.product-card');
    let visibleCount = 0;
    cards.forEach(card => {
      const title = card.querySelector('.product-title').textContent.toLowerCase();
      // Category filter
      const matchesCategory = !selectedCategory || title.includes(selectedCategory);
      // Search filter
      const matchesSearch = !query || title.includes(query);
      if (matchesCategory && matchesSearch) {
        card.style.display = '';
        visibleCount++;
      } else {
        card.style.display = 'none';
      }
    });
    if (!query && !selectedCategory) {
      searchResultMsg.textContent = "";
    } else if (visibleCount > 0) {
      searchResultMsg.textContent = `${visibleCount} item${visibleCount > 1 ? 's' : ''} available`;
    } else {
      searchResultMsg.textContent = "No item is available";
    }
  }

  searchBtn.addEventListener('click', filterProducts);
  searchInput.addEventListener('keyup', (e) => {
    if (e.key === 'Enter') filterProducts();
  });

  categoryCards.forEach(card => {
    card.addEventListener('click', () => {
      categoryCards.forEach(c => c.classList.remove('active'));
      card.classList.add('active');
      selectedCategory = card.textContent.toLowerCase();
      filterProducts();
    });
  });
}); 