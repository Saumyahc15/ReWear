// Demo data
const demoUser = {
  id: 1,
  name: "You",
  points: 0,
  items: [
    { id: 101, title: "Blue Denim Jacket", size: "M", category: "Men", type: "Outerwear", condition: "Good", tags: ["denim", "jacket"], img: "", available: true },
    { id: 102, title: "Red Dress", size: "S", category: "Women", type: "Dress", condition: "Like New", tags: ["dress", "red"], img: "", available: true },
    { id: 103, title: "Kids T-shirt", size: "L", category: "Kids", type: "Top", condition: "New", tags: ["kids", "tshirt"], img: "", available: true },
  ]
};

const demoItems = [
  { id: 201, title: "Green Hoodie", size: "L", category: "Men", type: "Top", condition: "Good", tags: ["hoodie", "green"], img: "", available: true, owner: 2 },
  { id: 202, title: "Classic Shirt", size: "M", category: "Men", type: "Top", condition: "Like New", tags: ["shirt", "classic"], img: "", available: true, owner: 3 },
  { id: 203, title: "Leather Bag", size: "-", category: "Women", type: "Accessories", condition: "Good", tags: ["bag", "leather"], img: "", available: true, owner: 4 },
  { id: 204, title: "Stylish Sweater", size: "S", category: "Women", type: "Top", condition: "Fair", tags: ["sweater", "stylish"], img: "", available: true, owner: 5 },
];

let swapRequests = [];
let notifications = [];
let currentChatSwapId = null;

// --- Utility Functions ---
function $(sel) { return document.querySelector(sel); }
function $all(sel) { return document.querySelectorAll(sel); }

// --- Render Swap Items Grid ---
function renderSwapItems(items) {
  const grid = $("#swapItemsGrid");
  grid.innerHTML = "";
  items.forEach(item => {
    const card = document.createElement("div");
    card.className = "product-card";
    card.innerHTML = `
      <img src="${item.img ? item.img : ''}" alt="${item.title}">
      <div class="product-title">${item.title}</div>
      <div class="product-meta">Size: ${item.size} | ${item.category}</div>
      <div class="product-meta">Available for: <span style="color:#bfa58a;">Swap</span></div>
      <button class="cta-btn hero-btn-main offer-swap-btn" data-id="${item.id}">Offer a Swap</button>
      <button class="cta-btn hero-btn-outline view-details-btn" data-id="${item.id}">View Details</button>
    `;
    grid.appendChild(card);
  });
  // Attach event listeners
  $all('.offer-swap-btn').forEach(btn => btn.onclick = openOfferSwapModal);
}

// --- Filtering ---
function filterItems() {
  let items = demoItems.filter(i => i.available);
  const cat = $("#filterCategory").value;
  const type = $("#filterType").value;
  const size = $("#filterSize").value;
  const cond = $("#filterCondition").value;
  const tags = $("#filterTags").value.toLowerCase();
  if (cat) items = items.filter(i => i.category === cat);
  if (type) items = items.filter(i => i.type === type);
  if (size) items = items.filter(i => i.size === size);
  if (cond) items = items.filter(i => i.condition === cond);
  if (tags) items = items.filter(i => i.tags.join(' ').toLowerCase().includes(tags));
  renderSwapItems(items);
}

$("#filterBtn").onclick = filterItems;
window.addEventListener('DOMContentLoaded', () => {
  renderSwapItems(demoItems);
});

// --- Offer Swap Modal ---
function openOfferSwapModal(e) {
  const itemId = e.target.getAttribute('data-id');
  $("#offerSwapModal").style.display = 'block';
  $("#offerSwapModal").setAttribute('data-item-id', itemId);
  // Populate user's items
  const select = $("#myItemsSelect");
  select.innerHTML = '';
  demoUser.items.filter(i => i.available).forEach(i => {
    const opt = document.createElement('option');
    opt.value = i.id;
    opt.textContent = `${i.title} (${i.size}, ${i.category})`;
    select.appendChild(opt);
  });
  $("#swapMessage").value = '';
  // Add points note if not already present
  let ptsNote = document.getElementById('swapPointsNote');
  if (!ptsNote) {
    ptsNote = document.createElement('div');
    ptsNote.id = 'swapPointsNote';
    ptsNote.style = 'color:#bfa58a;font-size:0.98rem;margin-bottom:8px;';
    ptsNote.innerHTML = "<span style='font-size:1.1em;'>&#11088;</span> You'll earn <b>20 points</b> when this swap is completed!";
    $("#swapMessage").parentNode.insertBefore(ptsNote, $("#swapMessage"));
  }
}
$("#closeOfferModal").onclick = () => { $("#offerSwapModal").style.display = 'none'; };

$("#confirmOfferBtn").onclick = function() {
  const requestedId = $("#offerSwapModal").getAttribute('data-item-id');
  const offeredId = $("#myItemsSelect").value;
  const message = $("#swapMessage").value;
  if (!offeredId) return alert('Select your item to offer!');
  // Add swap request
  const swapId = Date.now();
  swapRequests.push({
    id: swapId,
    offered: demoUser.items.find(i => i.id == offeredId),
    requested: demoItems.find(i => i.id == requestedId),
    status: 'Pending',
    sent: true,
    messages: message ? [{from: 'You', text: message, time: new Date().toLocaleTimeString()}] : [],
    timeline: ['Request Sent'],
  });
  notifications.push({type: 'swap', text: 'Swap request sent!', time: new Date()});
  renderNotifications();
  renderMySwaps();
  $("#offerSwapModal").style.display = 'none';
};

// --- My Swaps Tabs ---
$all('.swap-tab').forEach(tab => {
  tab.onclick = function() {
    $all('.swap-tab').forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    renderMySwaps();
  };
});

function renderMySwaps() {
  const activeTab = document.querySelector('.swap-tab.active').dataset.tab;
  const container = $("#swapTabContent");
  let swaps = swapRequests;
  if (activeTab === 'sent') swaps = swapRequests.filter(s => s.sent && s.status !== 'Completed' && s.status !== 'Cancelled');
  if (activeTab === 'received') swaps = swapRequests.filter(s => !s.sent && s.status !== 'Completed' && s.status !== 'Cancelled');
  if (activeTab === 'completed') swaps = swapRequests.filter(s => s.status === 'Completed');
  if (activeTab === 'cancelled') swaps = swapRequests.filter(s => s.status === 'Cancelled' || s.status === 'Rejected');
  container.innerHTML = '';
  if (!swaps.length) { container.innerHTML = '<div style="padding:24px;color:#bfa58a;">No swaps in this category.</div>'; return; }
  swaps.forEach((swap, idx) => {
    const card = document.createElement('div');
    card.className = 'swap-card';
    card.innerHTML = `
      <div class="swap-items-row">
        <div class="swap-item-card">
          <img src="${swap.offered.img ? swap.offered.img : ''}" alt="${swap.offered.title}">
          <div class="swap-item-title">You offered:<br><b>${swap.offered.title}</b></div>
        </div>
        <span class="swap-arrow">&#8594;</span>
        <div class="swap-item-card">
          <img src="${swap.requested.img ? swap.requested.img : ''}" alt="${swap.requested.title}">
          <div class="swap-item-title">For:<br><b>${swap.requested.title}</b></div>
        </div>
      </div>
      <div class="swap-status-row">
        <div class="swap-timeline">${renderTimeline(swap.timeline, swap.status)}</div>
        <div class="swap-status">Status: <b>${swap.status}</b></div>
      </div>
      <div class="swap-actions">
        ${swap.status === 'Pending' && swap.sent ? `<button class="swap-btn cancel-btn" data-idx="${idx}">Cancel</button>` : ''}
        ${swap.status === 'Pending' && !swap.sent ? `<button class="swap-btn accept-btn" data-idx="${idx}">Accept</button><button class="swap-btn reject-btn" data-idx="${idx}">Decline</button>` : ''}
        ${swap.status === 'Accepted' && swap.sent ? `<button class="swap-btn complete-btn" data-idx="${idx}">Mark as Completed <span style='color:#bfa58a;font-weight:600;'>+20 pts</span></button>` : ''}
        <button class="swap-btn chat-btn" data-idx="${idx}">Chat</button>
      </div>
    `;
    container.appendChild(card);
  });
  // Attach action listeners
  $all('.cancel-btn').forEach(btn => btn.onclick = cancelSwap);
  $all('.accept-btn').forEach(btn => btn.onclick = acceptSwap);
  $all('.reject-btn').forEach(btn => btn.onclick = rejectSwap);
  $all('.complete-btn').forEach(btn => btn.onclick = completeSwap);
  $all('.chat-btn').forEach(btn => btn.onclick = openChatModal);
}

function renderTimeline(timeline, status) {
  const steps = ['Request Sent', 'Accepted', 'Shipping', 'Received', 'Completed'];
  let html = '<div class="timeline-bar">';
  steps.forEach((step, i) => {
    const done = timeline.includes(step) || (status === step);
    html += `<span class="timeline-step${done ? ' done' : ''}">${step}</span>`;
    if (i < steps.length - 1) html += '<span class="timeline-sep"></span>';
  });
  html += '</div>';
  return html;
}

function cancelSwap(e) {
  const idx = e.target.getAttribute('data-idx');
  swapRequests[idx].status = 'Cancelled';
  swapRequests[idx].timeline.push('Cancelled');
  notifications.push({type: 'swap', text: 'Swap request cancelled.', time: new Date()});
  renderNotifications();
  renderMySwaps();
}
function acceptSwap(e) {
  const idx = e.target.getAttribute('data-idx');
  swapRequests[idx].status = 'Accepted';
  swapRequests[idx].timeline.push('Accepted');
  notifications.push({type: 'swap', text: 'Swap request accepted!', time: new Date()});
  renderNotifications();
  renderMySwaps();
}
function rejectSwap(e) {
  const idx = e.target.getAttribute('data-idx');
  swapRequests[idx].status = 'Rejected';
  swapRequests[idx].timeline.push('Rejected');
  notifications.push({type: 'swap', text: 'Swap request declined.', time: new Date()});
  renderNotifications();
  renderMySwaps();
}
function completeSwap(e) {
  const idx = e.target.getAttribute('data-idx');
  swapRequests[idx].status = 'Completed';
  swapRequests[idx].timeline.push('Completed');
  demoUser.points += 20;
  updatePointsDisplay();
  notifications.push({type: 'swap', text: 'Swap marked as completed! (+20 points)', time: new Date()});
  renderNotifications();
  renderMySwaps();
}

// --- Chat Modal ---
function openChatModal(e) {
  const idx = e.target.getAttribute('data-idx');
  currentChatSwapId = idx;
  $("#chatModal").style.display = 'block';
  renderChatMessages();
}
$("#closeChatModal").onclick = () => { $("#chatModal").style.display = 'none'; };

function renderChatMessages() {
  const swap = swapRequests[currentChatSwapId];
  const chatBox = $("#chatMessages");
  chatBox.innerHTML = '';
  if (!swap.messages.length) {
    chatBox.innerHTML = '<div style="color:#bfa58a;padding:12px;">No messages yet.</div>';
    return;
  }
  swap.messages.forEach(msg => {
    const div = document.createElement('div');
    div.className = 'chat-msg';
    div.innerHTML = `<b>${msg.from}:</b> ${msg.text} <span class="chat-time">${msg.time}</span>`;
    chatBox.appendChild(div);
  });
  chatBox.scrollTop = chatBox.scrollHeight;
}

$("#sendChatBtn").onclick = function() {
  const input = $("#chatInput");
  const text = input.value.trim();
  if (!text) return;
  const swap = swapRequests[currentChatSwapId];
  swap.messages.push({from: 'You', text, time: new Date().toLocaleTimeString()});
  notifications.push({type: 'chat', text: 'New message sent.', time: new Date()});
  renderNotifications();
  renderChatMessages();
  input.value = '';
};

// --- Notifications ---
function renderNotifications() {
  const area = $("#notificationArea");
  if (!notifications.length) { area.innerHTML = ''; return; }
  area.innerHTML = notifications.slice(-3).map(n => `<div class="notif-card">${n.text} <span class="notif-time">${n.time.toLocaleTimeString ? n.time.toLocaleTimeString() : n.time}</span></div>`).join('');
  setTimeout(() => { area.innerHTML = ''; }, 4000);
}

// --- Initial Render ---
function updatePointsDisplay() {
  const el = document.getElementById('userPoints');
  if (el) el.textContent = demoUser.points;
}
window.addEventListener('DOMContentLoaded', () => {
  renderSwapItems(demoItems);
  renderMySwaps();
  updatePointsDisplay();
}); 