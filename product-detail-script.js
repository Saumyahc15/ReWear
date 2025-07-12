// Sample data for previous listings


// Function to create listing card
// Sample data for previous listings matching database schema
const previousListings = [
    {
        id: 6,
        user_id: 1,
        title: 'Denim Jacket',
        price: 40.00,
        image: 'jack.jpg',
        created_at: '2025-07-12 11:33:12'
    },
    {
        id: 7,
        user_id: 2,
        title: 'Leather Boots',
        price: 38.00,
        image: 'boots.jpg',
        created_at: '2025-07-12 11:33:12'
    },
    {
        id: 8,
        user_id: 3,
        title: 'Silk Saree',
        price: 45.00,
        image: 'sari.jpg',
        created_at: '2025-07-12 11:33:12'
    },
    {
        id: 9,
        user_id: 1,
        title: 'Vintage Shirt',
        price: 35.00,
        image: 'shirt.jpg',
        created_at: '2025-07-12 11:33:12'
    },
    {
        id: 10,
        user_id: 2,
        title: 'Cotton Kurti',
        price: 40.00,
        image: 'kurti.jpg',
        created_at: '2025-07-12 11:33:12'
    }
];

// Update the createListingCard function to use the new data structure
function createListingCard(item) {
    return `
        <div class="listing-card product-style" onclick="viewListing(${item.id})">
            <div class="listing-image">
                <img src="${item.image}" alt="${item.title}" onerror="this.onerror=null; this.src='default-product.jpg';">
            </div>
            <div class="listing-info">
                <div class="listing-title">${item.title}</div>
                <div class="listing-price">$${item.price.toFixed(2)}</div>
                <div class="listing-date">${new Date(item.created_at).toLocaleDateString()}</div>
            </div>
        </div>
    `;
}

// Function to populate previous listings
function populatePreviousListings() {
    const container = document.getElementById('previousListings');
    container.innerHTML = previousListings.map(createListingCard).join('');
}

// Function to change main image
function changeImage(emoji) {
    const mainImage = document.getElementById('mainImage');
    mainImage.innerHTML = `<span>${emoji}</span>`;
    
    // Update active thumbnail
    document.querySelectorAll('.thumbnail').forEach(thumb => {
        thumb.classList.remove('active');
        if (thumb.textContent === emoji) {
            thumb.classList.add('active');
        }
    });
}

// Function to handle search
function performSearch() {
    const searchInput = document.getElementById('searchInput');
    console.log('Searching for:', searchInput.value);
    // Implement search functionality
}

// Function to toggle user menu
function toggleUserMenu() {
    console.log('User menu toggled');
    // Implement user menu toggle
}

// Function to handle image upload
function uploadImage() {
    console.log('Upload image clicked');
    // Implement image upload functionality
}

// Function to handle swap request
function requestSwap() {
    console.log('Swap requested');
    // Implement swap request functionality
}

// Function to handle points redemption
function redeemWithPoints() {
    console.log('Points redemption requested');
    // Implement points redemption functionality
}

// Function to handle seller contact
function contactSeller() {
    console.log('Contact seller clicked');
    // Implement seller contact functionality
}

// Function to view listing details
function viewListing(itemId) {
    console.log('Viewing listing:', itemId);
    // Implement listing view functionality
}

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    populatePreviousListings();

    // Initialize AOS if available
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 1200,
            once: true,
        });
    }
});