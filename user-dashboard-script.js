// Sample data for listings
const myListings = [
    {
        id: 6,
        user_id: 1,
        title: 'Denim Jacket',
        price: 40.00,
        image: 'jack.jpg',
        created_at: '2025-07-12 11:33:12',
        status: 'available' // Added for UI state
    },
    {
        id: 7,
        user_id: 2,
        title: 'Leather Boots',
        price: 38.00,
        image: 'boots.jpg',
        created_at: '2025-07-12 11:33:12',
        status: 'pending'
    },
    {
        id: 8,
        user_id: 3,
        title: 'Silk Saree',
        price: 45.00,
        image: 'sari.jpg',
        created_at: '2025-07-12 11:33:12',
        status: 'available'
    },
    {
        id: 9,
        user_id: 1,
        title: 'Vintage Shirt',
        price: 35.00,
        image: 'shirt.jpg',
        created_at: '2025-07-12 11:33:12',
        status: 'swapped'
    },
    {
        id: 10,
        user_id: 2,
        title: 'Cotton Kurti',
        price: 40.00,
        image: 'kurti.jpg',
        created_at: '2025-07-12 11:33:12',
        status: 'available'
    }
];

// Sample data for purchases (following similar structure)
const myPurchases = [
    {
        id: 1,
        user_id: 1,
        title: 'Casual T-Shirt',
        price: 35.00,
        image: 'tshirt.jpg',
        created_at: '2025-07-12 11:33:12',
        status: 'completed'
    },
    {
        id: 2,
        user_id: 2,
        title: 'Running Shoes',
        price: 42.00,
        image: 'shoes.jpg',
        created_at: '2025-07-12 11:33:12',
        status: 'completed'
    }
];



// Function to get status class
function getStatusClass(status) {
    switch(status) {
        case 'available': return 'status-available';
        case 'pending': return 'status-pending';
        case 'swapped': return 'status-swapped';
        case 'completed': return 'status-completed';
        default: return 'status-available';
    }
}

// Function to get status text
function getStatusText(status) {
    switch(status) {
        case 'available': return 'Available';
        case 'pending': return 'Pending';
        case 'swapped': return 'Swapped';
        case 'completed': return 'Completed';
        default: return 'Available';
    }
}

// Function to create item card
function createItemCard(item) {
    return `
        <div class="item-card product-style" onclick="viewItem(${item.id})">
            <div class="item-image">
                <img src="${item.image}" alt="${item.title}" onerror="this.onerror=null; this.src='default-product.jpg';">
            </div>
            <div class="item-info">
                <h3 class="item-title">${item.title}</h3>
                <p class="item-price">$${item.price.toFixed(2)}</p>
                <div class="item-status ${getStatusClass(item.status)}">
                    ${getStatusText(item.status)}
                </div>
            </div>
        </div>
    `;
}

// Function to populate listings
function populateListings() {
    const container = document.getElementById('myListings');
    if (myListings.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">📦</div>
                <p>No items listed yet</p>
            </div>
        `;
    } else {
        container.innerHTML = myListings.map(createItemCard).join('');
    }
}

// Function to populate purchases
function populatePurchases() {
    const container = document.getElementById('myPurchases');
    if (myPurchases.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">🛍️</div>
                <p>No purchases yet</p>
            </div>
        `;
    } else {
        container.innerHTML = myPurchases.map(createItemCard).join('');
    }
}

// Function to handle item click
function viewItem(itemId) {
    // Navigate to product detail page
    window.location.href = 'product-detail.html';
}

// Function to toggle user menu
function toggleUserMenu() {
    console.log('User menu toggled');
    // Here you would typically show/hide user menu
    alert('User menu would appear here');
}

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    populateListings();
    populatePurchases();

    // Initialize AOS if available
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 1200,
            once: true,
        });
    }
});

// Add smooth scrolling for horizontal scroll containers
document.querySelectorAll('.scroll-container').forEach(container => {
    let isDown = false;
    let startX;
    let scrollLeft;

    container.addEventListener('mousedown', (e) => {
        isDown = true;
        startX = e.pageX - container.offsetLeft;
        scrollLeft = container.scrollLeft;
    });

    container.addEventListener('mouseleave', () => {
        isDown = false;
    });

    container.addEventListener('mouseup', () => {
        isDown = false;
    });

    container.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - container.offsetLeft;
        const walk = (x - startX) * 2;
        container.scrollLeft = scrollLeft - walk;
    });
});