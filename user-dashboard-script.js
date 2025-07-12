// Sample data for listings
const myListings = [
    {
        id: 1,
        title: "Vintage Denim Jacket",
        category: "Jackets",
        status: "available",
        icon: "🧥"
    },
    {
        id: 2,
        title: "Designer Handbag",
        category: "Accessories",
        status: "pending",
        icon: "👜"
    },
    {
        id: 3,
        title: "Summer Dress",
        category: "Dresses",
        status: "available",
        icon: "👗"
    },
    {
        id: 4,
        title: "Leather Boots",
        category: "Shoes",
        status: "swapped",
        icon: "👢"
    },
    {
        id: 5,
        title: "Wool Sweater",
        category: "Sweaters",
        status: "available",
        icon: "🧶"
    },
    {
        id: 6,
        title: "Silk Scarf",
        category: "Accessories",
        status: "pending",
        icon: "🧣"
    }
];

// Sample data for purchases
const myPurchases = [
    {
        id: 1,
        title: "Casual T-Shirt",
        category: "T-Shirts",
        status: "completed",
        icon: "👕"
    },
    {
        id: 2,
        title: "Running Shoes",
        category: "Shoes",
        status: "completed",
        icon: "👟"
    },
    {
        id: 3,
        title: "Winter Coat",
        category: "Coats",
        status: "pending",
        icon: "🧥"
    },
    {
        id: 4,
        title: "Formal Shirt",
        category: "Shirts",
        status: "completed",
        icon: "👔"
    },
    {
        id: 5,
        title: "Yoga Pants",
        category: "Activewear",
        status: "completed",
        icon: "👖"
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
                <span style="font-size: 3rem;">${item.icon}</span>
            </div>
            <div class="item-info">
                <h3 class="item-title">${item.title}</h3>
                <p class="item-category">${item.category}</p>
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