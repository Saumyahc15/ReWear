// Sample data for previous listings
const previousListings = [
    {
        id: 1,
        title: "Casual Denim Jacket",
        category: "Jackets",
        price: "40 Points",
        icon: "🧥"
    },
    {
        id: 2,
        title: "Designer Handbag",
        category: "Accessories",
        price: "60 Points",
        icon: "👜"
    },
    {
        id: 3,
        title: "Leather Boots",
        category: "Shoes",
        price: "45 Points",
        icon: "👢"
    },
    {
        id: 4,
        title: "Wool Sweater",
        category: "Sweaters",
        price: "35 Points",
        icon: "🧶"
    },
    {
        id: 5,
        title: "Silk Scarf",
        category: "Accessories",
        price: "25 Points",
        icon: "🧣"
    },
    {
        id: 6,
        title: "Summer Blouse",
        category: "Tops",
        price: "30 Points",
        icon: "👚"
    },
    {
        id: 7,
        title: "Formal Pants",
        category: "Bottoms",
        price: "40 Points",
        icon: "👖"
    },
    {
        id: 8,
        title: "Winter Coat",
        category: "Outerwear",
        price: "70 Points",
        icon: "🧥"
    }
];

// Function to create listing card
function createListingCard(item) {
    return `
        <div class="listing-card product-style" onclick="viewListing(${item.id})">
            <div class="listing-image">
                <span>${item.icon}</span>
            </div>
            <div class="listing-info">
                <div class="listing-title">${item.title}</div>
                <div class="listing-category">${item.category}</div>
                <div class="listing-price">${item.price}</div>
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