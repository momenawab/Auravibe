// ===================================
// Aura Vibe - Main JavaScript
// ===================================

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all functions
    initNavbar();
    initHero3D();
    initScrollAnimations();
});

// ===================================
// Navbar Functionality
// ===================================
function initNavbar() {
    const navbar = document.querySelector('.navbar');
    const mobileToggle = document.getElementById('mobileToggle');
    const navMenu = document.getElementById('navMenu');

    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        if (window.scrollY > 100) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Mobile menu toggle
    if (mobileToggle) {
        mobileToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            this.classList.toggle('active');
        });
    }

    // Close mobile menu when clicking on a link
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            navMenu.classList.remove('active');
            if (mobileToggle) {
                mobileToggle.classList.remove('active');
            }
        });
    });
}

// ===================================
// Premium Watch-Themed 2D Animation
// ===================================
function initHero3D() {
    const heroCanvas = document.getElementById('hero-canvas');
    if (!heroCanvas) return;

    // Create canvas
    const canvas = document.createElement('canvas');
    heroCanvas.appendChild(canvas);
    const ctx = canvas.getContext('2d');

    // Responsive sizing
    function resizeCanvas() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);

    // Mouse tracking
    let mouse = {
        x: canvas.width / 2,
        y: canvas.height / 2
    };

    document.addEventListener('mousemove', (e) => {
        mouse.x = e.clientX;
        mouse.y = e.clientY;
    });

    // Center position
    const centerX = () => canvas.width / 2;
    const centerY = () => canvas.height / 2;

    // Watch Gear class (2D gears)
    class WatchGear {
        constructor(x, y, radius, teeth, speed) {
            this.x = x;
            this.y = y;
            this.radius = radius;
            this.teeth = teeth;
            this.speed = speed;
            this.rotation = Math.random() * Math.PI * 2;
            this.opacity = Math.random() * 0.15 + 0.1;
        }

        update() {
            this.rotation += this.speed;
        }

        draw() {
            ctx.save();
            ctx.translate(this.x, this.y);
            ctx.rotate(this.rotation);

            // Outer circle
            ctx.strokeStyle = `rgba(212, 175, 55, ${this.opacity})`;
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.arc(0, 0, this.radius, 0, Math.PI * 2);
            ctx.stroke();

            // Inner circle
            ctx.beginPath();
            ctx.arc(0, 0, this.radius * 0.4, 0, Math.PI * 2);
            ctx.stroke();

            // Gear teeth
            for (let i = 0; i < this.teeth; i++) {
                const angle = (i / this.teeth) * Math.PI * 2;
                const x1 = Math.cos(angle) * this.radius;
                const y1 = Math.sin(angle) * this.radius;
                const x2 = Math.cos(angle) * (this.radius + 8);
                const y2 = Math.sin(angle) * (this.radius + 8);

                ctx.beginPath();
                ctx.moveTo(x1, y1);
                ctx.lineTo(x2, y2);
                ctx.stroke();
            }

            // Center bolt
            ctx.fillStyle = `rgba(201, 166, 92, ${this.opacity * 2})`;
            ctx.beginPath();
            ctx.arc(0, 0, this.radius * 0.15, 0, Math.PI * 2);
            ctx.fill();

            ctx.restore();
        }
    }

    // Watch Face class
    class WatchFace {
        constructor(x, y, radius, opacity) {
            this.x = x;
            this.y = y;
            this.radius = radius;
            this.opacity = opacity;
            this.rotation = Math.random() * Math.PI * 2;
            this.rotationSpeed = 0.0001;
        }

        update() {
            this.rotation += this.rotationSpeed;
        }

        draw() {
            ctx.save();
            ctx.translate(this.x, this.y);
            ctx.rotate(this.rotation);

            // Watch bezel
            ctx.strokeStyle = `rgba(212, 175, 55, ${this.opacity})`;
            ctx.lineWidth = 3;
            ctx.beginPath();
            ctx.arc(0, 0, this.radius, 0, Math.PI * 2);
            ctx.stroke();

            // Hour markers
            for (let i = 0; i < 12; i++) {
                const angle = (i / 12) * Math.PI * 2 - Math.PI / 2;
                const isMainMarker = i % 3 === 0;
                const markerLength = isMainMarker ? 15 : 8;
                const markerWidth = isMainMarker ? 3 : 1.5;

                const x1 = Math.cos(angle) * (this.radius - markerLength);
                const y1 = Math.sin(angle) * (this.radius - markerLength);
                const x2 = Math.cos(angle) * this.radius;
                const y2 = Math.sin(angle) * this.radius;

                ctx.strokeStyle = `rgba(212, 175, 55, ${this.opacity})`;
                ctx.lineWidth = markerWidth;
                ctx.beginPath();
                ctx.moveTo(x1, y1);
                ctx.lineTo(x2, y2);
                ctx.stroke();
            }

            ctx.restore();
        }
    }

    // Roman Numeral class
    class RomanNumeral {
        constructor() {
            this.reset();
        }

        reset() {
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height;
            this.text = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'][Math.floor(Math.random() * 12)];
            this.size = Math.random() * 20 + 15;
            this.speedX = (Math.random() - 0.5) * 0.3;
            this.speedY = (Math.random() - 0.5) * 0.3;
            this.opacity = Math.random() * 0.2 + 0.1;
            this.rotation = Math.random() * 0.5 - 0.25;
        }

        update() {
            this.x += this.speedX;
            this.y += this.speedY;

            if (this.x < -50 || this.x > canvas.width + 50 || this.y < -50 || this.y > canvas.height + 50) {
                this.reset();
            }
        }

        draw() {
            ctx.save();
            ctx.translate(this.x, this.y);
            ctx.rotate(this.rotation);
            ctx.font = `${this.size}px 'Playfair Display', serif`;
            ctx.fillStyle = `rgba(212, 175, 55, ${this.opacity})`;
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText(this.text, 0, 0);
            ctx.restore();
        }
    }

    // Time particle (like dust particles)
    class TimeParticle {
        constructor() {
            this.reset();
        }

        reset() {
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height;
            this.size = Math.random() * 2 + 0.5;
            this.speedX = Math.random() * 0.3 - 0.15;
            this.speedY = Math.random() * 0.3 - 0.15;
            this.opacity = Math.random() * 0.4 + 0.1;
        }

        update() {
            this.x += this.speedX;
            this.y += this.speedY;

            // Mouse attraction
            const dx = mouse.x - this.x;
            const dy = mouse.y - this.y;
            const distance = Math.sqrt(dx * dx + dy * dy);

            if (distance < 120) {
                const force = (120 - distance) / 120;
                this.x += (dx / distance) * force * 0.3;
                this.y += (dy / distance) * force * 0.3;
            }

            if (this.x < 0 || this.x > canvas.width || this.y < 0 || this.y > canvas.height) {
                this.reset();
            }
        }

        draw() {
            ctx.fillStyle = `rgba(212, 175, 55, ${this.opacity})`;
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fill();
        }
    }

    // Create elements
    const gears = [
        new WatchGear(centerX() - 250, centerY() - 150, 60, 12, 0.005),
        new WatchGear(centerX() + 280, centerY() - 100, 50, 10, -0.007),
        new WatchGear(centerX() - 300, centerY() + 180, 45, 8, 0.006),
        new WatchGear(centerX() + 250, centerY() + 150, 55, 11, -0.004),
        new WatchGear(centerX() - 150, centerY() - 220, 35, 8, 0.008),
        new WatchGear(centerX() + 180, centerY() + 220, 40, 9, -0.006)
    ];

    const watchFaces = [
        new WatchFace(centerX() - 350, centerY(), 80, 0.15),
        new WatchFace(centerX() + 350, centerY(), 70, 0.12),
        new WatchFace(centerX(), centerY() - 250, 60, 0.1),
        new WatchFace(centerX(), centerY() + 250, 65, 0.13)
    ];

    const romanNumerals = [];
    for (let i = 0; i < 20; i++) {
        romanNumerals.push(new RomanNumeral());
    }

    const timeParticles = [];
    for (let i = 0; i < 100; i++) {
        timeParticles.push(new TimeParticle());
    }

    // Main watch hands (center)
    let hourAngle = 0;
    let minuteAngle = 0;
    let secondAngle = 0;

    // Animation loop
    function animate() {
        requestAnimationFrame(animate);

        // Dark gradient background
        const gradient = ctx.createRadialGradient(centerX(), centerY(), 0, centerX(), centerY(), canvas.width);
        gradient.addColorStop(0, 'rgba(10, 10, 10, 0.05)');
        gradient.addColorStop(1, 'rgba(5, 5, 5, 0.05)');
        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        // Update and draw watch faces
        watchFaces.forEach(face => {
            face.update();
            face.draw();
        });

        // Update and draw gears
        gears.forEach(gear => {
            gear.update();
            gear.draw();
        });

        // Update and draw roman numerals
        romanNumerals.forEach(numeral => {
            numeral.update();
            numeral.draw();
        });

        // Update and draw time particles
        timeParticles.forEach(particle => {
            particle.update();
            particle.draw();
        });

        // Draw central luxury watch
        const centerWatchRadius = Math.min(canvas.width, canvas.height) * 0.15;

        // Outer bezel with luxury details
        ctx.strokeStyle = 'rgba(212, 175, 55, 0.3)';
        ctx.lineWidth = 4;
        ctx.beginPath();
        ctx.arc(centerX(), centerY(), centerWatchRadius, 0, Math.PI * 2);
        ctx.stroke();

        // Inner dial
        ctx.strokeStyle = 'rgba(212, 175, 55, 0.2)';
        ctx.lineWidth = 2;
        ctx.beginPath();
        ctx.arc(centerX(), centerY(), centerWatchRadius * 0.9, 0, Math.PI * 2);
        ctx.stroke();

        // Hour markers on central watch
        for (let i = 0; i < 12; i++) {
            const angle = (i / 12) * Math.PI * 2 - Math.PI / 2;
            const isMainMarker = i % 3 === 0;
            const markerLength = isMainMarker ? centerWatchRadius * 0.15 : centerWatchRadius * 0.08;

            const x1 = centerX() + Math.cos(angle) * (centerWatchRadius * 0.85);
            const y1 = centerY() + Math.sin(angle) * (centerWatchRadius * 0.85);
            const x2 = centerX() + Math.cos(angle) * (centerWatchRadius * 0.85 - markerLength);
            const y2 = centerY() + Math.sin(angle) * (centerWatchRadius * 0.85 - markerLength);

            ctx.strokeStyle = `rgba(212, 175, 55, ${isMainMarker ? 0.4 : 0.3})`;
            ctx.lineWidth = isMainMarker ? 3 : 1.5;
            ctx.beginPath();
            ctx.moveTo(x1, y1);
            ctx.lineTo(x2, y2);
            ctx.stroke();
        }

        // Animate watch hands
        secondAngle += 0.02;
        minuteAngle += 0.002;
        hourAngle += 0.0001;

        // Second hand
        ctx.save();
        ctx.translate(centerX(), centerY());
        ctx.rotate(secondAngle);
        ctx.strokeStyle = 'rgba(212, 175, 55, 0.5)';
        ctx.lineWidth = 1.5;
        ctx.beginPath();
        ctx.moveTo(0, 0);
        ctx.lineTo(0, -centerWatchRadius * 0.7);
        ctx.stroke();
        ctx.restore();

        // Minute hand
        ctx.save();
        ctx.translate(centerX(), centerY());
        ctx.rotate(minuteAngle);
        ctx.strokeStyle = 'rgba(212, 175, 55, 0.6)';
        ctx.lineWidth = 3;
        ctx.beginPath();
        ctx.moveTo(0, 0);
        ctx.lineTo(0, -centerWatchRadius * 0.6);
        ctx.stroke();
        ctx.restore();

        // Hour hand
        ctx.save();
        ctx.translate(centerX(), centerY());
        ctx.rotate(hourAngle);
        ctx.strokeStyle = 'rgba(212, 175, 55, 0.7)';
        ctx.lineWidth = 4;
        ctx.beginPath();
        ctx.moveTo(0, 0);
        ctx.lineTo(0, -centerWatchRadius * 0.45);
        ctx.stroke();
        ctx.restore();

        // Center bolt
        ctx.fillStyle = 'rgba(212, 175, 55, 0.8)';
        ctx.beginPath();
        ctx.arc(centerX(), centerY(), centerWatchRadius * 0.05, 0, Math.PI * 2);
        ctx.fill();
    }

    animate();
    console.log('✓ Luxury Watch Animation Loaded');
}

// ===================================
// Scroll Animations
// ===================================
function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe elements
    const animateElements = document.querySelectorAll(
        '.product-card, .category-card, .feature-card, .testimonial-card'
    );

    animateElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
}

// ===================================
// Add to Cart Functionality
// ===================================
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('add-to-cart') ||
        e.target.closest('.add-to-cart') ||
        e.target.classList.contains('btn-add-cart') ||
        e.target.closest('.btn-add-cart')) {

        e.preventDefault();

        const btn = e.target.classList.contains('add-to-cart') || e.target.classList.contains('btn-add-cart') ?
                    e.target : e.target.closest('.add-to-cart, .btn-add-cart');

        // Get product ID from button data attribute or parent product-card
        const productId = btn.getAttribute('data-product-id') || 
                         btn.getAttribute('data-id') || 
                         btn.closest('.product-card')?.getAttribute('data-product-id');

        if (productId) {
            // Send AJAX request to add item to cart
            fetch('api/cart-add.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message || 'تم إضافة المنتج إلى السلة', 'success');

                    // Update cart count in navbar if exists
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount && data.cart_count) {
                        cartCount.textContent = data.cart_count;
                        cartCount.style.display = 'flex';
                    }
                } else {
                    showNotification(data.message || 'حدث خطأ أثناء إضافة المنتج', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('تم إضافة المنتج إلى السلة', 'success');
            });
        } else {
            showNotification('تم إضافة المنتج إلى السلة', 'success');
        }
    }
});

// ===================================
// Wishlist Functionality (Works without login - uses localStorage)
// ===================================

// Initialize wishlist from localStorage
function getWishlist() {
    const wishlist = localStorage.getItem('wishlist');
    return wishlist ? JSON.parse(wishlist) : [];
}

function saveWishlist(wishlist) {
    localStorage.setItem('wishlist', JSON.stringify(wishlist));
}

function isInWishlist(productId) {
    const wishlist = getWishlist();
    return wishlist.some(item => item.id == productId);
}

// Update wishlist button state
function updateWishlistButton(btn, productId) {
    const icon = btn.querySelector('i');
    if (isInWishlist(productId)) {
        icon.classList.remove('far');
        icon.classList.add('fas');
        btn.style.color = '#D4AF37';
        btn.setAttribute('title', 'Remove from wishlist');
    } else {
        icon.classList.remove('fas');
        icon.classList.add('far');
        btn.style.color = '';
        btn.setAttribute('title', 'Add to wishlist');
    }
}

// Initialize wishlist buttons on page load
document.addEventListener('DOMContentLoaded', function() {
    const wishlistButtons = document.querySelectorAll('.wishlist-btn');
    wishlistButtons.forEach(btn => {
        const productId = btn.dataset.productId;
        if (productId) {
            updateWishlistButton(btn, productId);
        }
    });
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('wishlist-btn') ||
        e.target.closest('.wishlist-btn')) {

        const btn = e.target.classList.contains('wishlist-btn') ?
                    e.target : e.target.closest('.wishlist-btn');

        const productId = btn.dataset.productId;
        const productName = btn.dataset.productName;
        const productPrice = btn.dataset.productPrice;
        const productImage = btn.dataset.productImage;
        const productBrand = btn.dataset.productBrand;

        if (!productId) {
            showNotification('Product information missing', 'error');
            return;
        }

        const wishlist = getWishlist();
        const existingIndex = wishlist.findIndex(item => item.id == productId);

        if (existingIndex > -1) {
            // Remove from wishlist
            wishlist.splice(existingIndex, 1);
            saveWishlist(wishlist);
            updateWishlistButton(btn, productId);
            showNotification('Removed from wishlist ❤️');
        } else {
            // Add to wishlist
            wishlist.push({
                id: productId,
                name: productName,
                price: productPrice,
                image: productImage,
                brand: productBrand
            });
            saveWishlist(wishlist);
            updateWishlistButton(btn, productId);
            showNotification('Added to wishlist ❤️');
        }

        // Update wishlist count in navbar
        updateWishlistCount();
    }
});

// Update wishlist count badge
function updateWishlistCount() {
    const wishlist = getWishlist();
    const countElement = document.querySelector('.wishlist-count');
    if (countElement) {
        countElement.textContent = wishlist.length;
        if (wishlist.length > 0) {
            countElement.style.display = 'inline-block';
        } else {
            countElement.style.display = 'none';
        }
    }
}

// ===================================
// Quick View Modal (placeholder)
// ===================================
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('quick-view-btn') ||
        e.target.closest('.quick-view-btn')) {

        showNotification('Quick view feature coming soon!');
    }
});

// ===================================
// Notification System
// ===================================
function showNotification(message, type = 'success') {
    // Remove existing notification
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    // Create notification
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;

    // Styles
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: ${type === 'success' ? '#D4AF37' : '#e74c3c'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 0;
        font-weight: 500;
        z-index: 10000;
        animation: slideInRight 0.4s ease;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    `;

    document.body.appendChild(notification);

    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.4s ease';
        setTimeout(() => notification.remove(), 400);
    }, 3000);
}

// Add animation styles
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// ===================================
// Smooth Scroll
// ===================================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href !== '#') {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
    });
});

// ===================================
// Form Validation (Newsletter)
// ===================================
const newsletterForm = document.querySelector('.newsletter-form');
if (newsletterForm) {
    newsletterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const email = this.querySelector('input[type="email"]').value;

        if (email) {
            showNotification('Thank you for subscribing!', 'success');
            this.reset();
        }
    });
}

// ===================================
// Lazy Loading Images
// ===================================
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            }
        });
    });

    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}

// ===================================
// Back to Top Button
// ===================================
function createBackToTopButton() {
    const button = document.createElement('button');
    button.className = 'back-to-top';
    button.innerHTML = '<i class="fas fa-arrow-up"></i>';
    button.style.cssText = `
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #D4AF37, #B8941F);
        color: white;
        border: none;
        cursor: pointer;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 1000;
        font-size: 1.2rem;
    `;

    document.body.appendChild(button);

    window.addEventListener('scroll', () => {
        if (window.scrollY > 500) {
            button.style.opacity = '1';
            button.style.visibility = 'visible';
        } else {
            button.style.opacity = '0';
            button.style.visibility = 'hidden';
        }
    });

    button.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    button.addEventListener('mouseenter', () => {
        button.style.transform = 'translateY(-5px) scale(1.1)';
    });

    button.addEventListener('mouseleave', () => {
        button.style.transform = 'translateY(0) scale(1)';
    });
}

createBackToTopButton();

// ===================================
// Page Load Animation
// ===================================
window.addEventListener('load', function() {
    document.body.style.opacity = '0';
    document.body.style.transition = 'opacity 0.5s ease';

    setTimeout(() => {
        document.body.style.opacity = '1';
    }, 100);
});

console.log('Aura Vibe - Luxury Watch Store Loaded Successfully');
