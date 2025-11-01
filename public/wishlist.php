<?php require_once __DIR__ . '/config-paths.php';
$page_title = 'My Wishlist';
include_view('header.php');
include_view('navbar.php');
?>

<!-- Wishlist Section -->
<section class="wishlist-section">
    <div class="container">
        <div class="wishlist-header">
            <h1><?php echo __('wishlist'); ?></h1>
            <p id="wishlist-count-text">0 items saved</p>
        </div>

        <div class="wishlist-actions-bar" id="wishlist-actions" style="display:none;">
            <button class="btn btn-outline" onclick="clearAllWishlist()">
                <i class="fas fa-trash"></i> <?php echo __('clear_all'); ?>
            </button>
            <button class="btn btn-primary" onclick="addAllWishlistToCart()">
                <i class="fas fa-shopping-cart"></i> <?php echo __('add_all_to_cart'); ?>
            </button>
        </div>

        <!-- Wishlist Grid (populated by JavaScript) -->
        <div class="wishlist-grid" id="wishlist-grid"></div>

        <!-- Empty Wishlist Message -->
        <div class="empty-wishlist" id="empty-wishlist" style="display:none;">
            <div class="empty-icon">
                <i class="far fa-heart"></i>
            </div>
            <h2><?php echo __('wishlist_empty'); ?></h2>
            <p><?php echo __('wishlist_empty_desc'); ?></p>
            <a href="shop.php" class="btn btn-primary btn-large">
                <i class="fas fa-shopping-bag"></i> <?php echo __('start_shopping'); ?>
            </a>
        </div>
    </div>
</section>

<script>
// Load and display wishlist from localStorage
function loadWishlist() {
    const wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
    const grid = document.getElementById('wishlist-grid');
    const emptyMessage = document.getElementById('empty-wishlist');
    const actionsBar = document.getElementById('wishlist-actions');
    const countText = document.getElementById('wishlist-count-text');

    if (wishlist.length === 0) {
        grid.style.display = 'none';
        emptyMessage.style.display = 'block';
        actionsBar.style.display = 'none';
        countText.textContent = '0 items saved';
        return;
    }

    grid.style.display = 'grid';
    emptyMessage.style.display = 'none';
    actionsBar.style.display = 'flex';
    countText.textContent = `${wishlist.length} item${wishlist.length > 1 ? 's' : ''} saved`;

    grid.innerHTML = wishlist.map(item => `
        <div class="wishlist-card" data-product-id="${item.id}">
            <button class="wishlist-remove" onclick="removeFromWishlistPage('${item.id}')">
                <i class="fas fa-times"></i>
            </button>

            <div class="wishlist-image">
                <a href="product.php?id=${item.id}">
                    <img src="${item.image}" alt="${item.name}">
                </a>
            </div>

            <div class="wishlist-info">
                <h3 class="wishlist-name">
                    <a href="product.php?id=${item.id}">${item.name}</a>
                </h3>
                <div class="wishlist-brand">by ${item.brand || 'Unknown'}</div>
                <div class="wishlist-price number"><?php echo __('egp'); ?> ${parseFloat(item.price).toFixed(2)}</div>
            </div>

            <div class="wishlist-actions">
                <button class="btn btn-primary btn-block add-to-cart" 
                        data-product-id="${item.id}"
                        data-product-name="${item.name}"
                        data-product-price="${item.price}"
                        data-product-image="${item.image}"
                        data-product-brand="${item.brand || ''}">
                    <i class="fas fa-shopping-cart"></i> <?php echo __('add_to_cart'); ?>
                </button>
                <a href="product.php?id=${item.id}" class="btn btn-outline btn-block">
                    <i class="fas fa-eye"></i> <?php echo __('view_details'); ?>
                </a>
            </div>
        </div>
    `).join('');
}

function removeFromWishlistPage(productId) {
    if (confirm('<?php echo __('confirm_remove_wishlist'); ?>')) {
        let wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
        wishlist = wishlist.filter(item => item.id != productId);
        localStorage.setItem('wishlist', JSON.stringify(wishlist));
        
        showNotification('<?php echo __('removed_from_wishlist'); ?>');
        updateWishlistCount();
        loadWishlist();
    }
}

function clearAllWishlist() {
    if (confirm('<?php echo __('confirm_clear_wishlist'); ?>')) {
        localStorage.setItem('wishlist', JSON.stringify([]));
        showNotification('<?php echo __('wishlist_cleared'); ?>');
        updateWishlistCount();
        loadWishlist();
    }
}

function addAllWishlistToCart() {
    const wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
    let addedCount = 0;
    
    wishlist.forEach(item => {
        // Trigger add to cart for each item
        const btn = document.querySelector(`[data-product-id="${item.id}"].add-to-cart`);
        if (btn) {
            btn.click();
            addedCount++;
        }
    });
    
    if (addedCount > 0) {
        showNotification(`${addedCount} items added to cart!`);
    }
}

// Load wishlist on page load
document.addEventListener('DOMContentLoaded', function() {
    loadWishlist();
    updateWishlistCount();
});
</script>

<?php include_view('footer.php'); ?>
