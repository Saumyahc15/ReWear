document.addEventListener('DOMContentLoaded', function() {
  // Hero background carousel functionality
  const heroSection = document.querySelector('.hero-landing');
  const heroImages = [
    'images/product-item5.jpg',
    'images/product-item6.jpg',
    'images/product-item7.jpg'
  ];
  let currentHero = 0;
  const heroDots = document.querySelectorAll('.hero-dot');
  const heroArrow = document.querySelector('.hero-arrow');
  let heroInterval;

  function setHeroImage(idx) {
    currentHero = idx;
    heroSection.style.backgroundImage = `url('${heroImages[currentHero]}')`;
    heroDots.forEach((dot, i) => {
      dot.classList.toggle('active', i === currentHero);
    });
  }

  function nextHeroImage() {
    setHeroImage((currentHero + 1) % heroImages.length);
  }

  function startHeroInterval() {
    if (heroInterval) clearInterval(heroInterval);
    heroInterval = setInterval(() => {
      nextHeroImage();
    }, 3000);
  }

  heroDots.forEach((dot, i) => {
    dot.addEventListener('click', () => {
      setHeroImage(i);
      startHeroInterval();
    });
  });
  if (heroArrow) {
    heroArrow.addEventListener('click', () => {
      nextHeroImage();
      startHeroInterval();
    });
  }

  // Initialize
  setHeroImage(0);
  startHeroInterval();

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