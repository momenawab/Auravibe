// ===================================
// Premium 2D Luxury Hero Animation
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

    // Gold color palette
    const colors = {
        gold1: '#D4AF37',
        gold2: '#C9A65C',
        gold3: '#F4E4B8',
        gold4: '#B8941F'
    };

    // Particle system
    class Particle {
        constructor() {
            this.reset();
        }

        reset() {
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height;
            this.size = Math.random() * 3 + 1;
            this.speedX = Math.random() * 0.5 - 0.25;
            this.speedY = Math.random() * 0.5 - 0.25;
            this.opacity = Math.random() * 0.5 + 0.2;
            this.life = Math.random() * 200 + 100;
            this.age = 0;
        }

        update() {
            // Move particle
            this.x += this.speedX;
            this.y += this.speedY;

            // Mouse attraction
            const dx = mouse.x - this.x;
            const dy = mouse.y - this.y;
            const distance = Math.sqrt(dx * dx + dy * dy);

            if (distance < 150) {
                const force = (150 - distance) / 150;
                this.x += (dx / distance) * force * 0.5;
                this.y += (dy / distance) * force * 0.5;
            }

            this.age++;
            if (this.age > this.life || this.x < 0 || this.x > canvas.width || this.y < 0 || this.y > canvas.height) {
                this.reset();
            }
        }

        draw() {
            const fadeIn = Math.min(this.age / 30, 1);
            const fadeOut = Math.max((this.life - this.age) / 30, 0);
            const alpha = this.opacity * fadeIn * fadeOut;

            ctx.fillStyle = `rgba(212, 175, 55, ${alpha})`;
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fill();

            // Glow effect
            const gradient = ctx.createRadialGradient(this.x, this.y, 0, this.x, this.y, this.size * 3);
            gradient.addColorStop(0, `rgba(244, 228, 184, ${alpha * 0.3})`);
            gradient.addColorStop(1, 'rgba(244, 228, 184, 0)');
            ctx.fillStyle = gradient;
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size * 3, 0, Math.PI * 2);
            ctx.fill();
        }
    }

    // Floating geometric shapes
    class GeometricShape {
        constructor() {
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height;
            this.size = Math.random() * 80 + 40;
            this.rotation = Math.random() * Math.PI * 2;
            this.rotationSpeed = (Math.random() - 0.5) * 0.002;
            this.opacity = Math.random() * 0.15 + 0.05;
            this.type = Math.floor(Math.random() * 3); // 0: circle, 1: square, 2: hexagon
            this.speedX = (Math.random() - 0.5) * 0.1;
            this.speedY = (Math.random() - 0.5) * 0.1;
        }

        update() {
            this.rotation += this.rotationSpeed;
            this.x += this.speedX;
            this.y += this.speedY;

            // Wrap around
            if (this.x < -this.size) this.x = canvas.width + this.size;
            if (this.x > canvas.width + this.size) this.x = -this.size;
            if (this.y < -this.size) this.y = canvas.height + this.size;
            if (this.y > canvas.height + this.size) this.y = -this.size;
        }

        draw() {
            ctx.save();
            ctx.translate(this.x, this.y);
            ctx.rotate(this.rotation);
            ctx.strokeStyle = `rgba(212, 175, 55, ${this.opacity})`;
            ctx.lineWidth = 2;

            if (this.type === 0) {
                // Circle
                ctx.beginPath();
                ctx.arc(0, 0, this.size, 0, Math.PI * 2);
                ctx.stroke();
            } else if (this.type === 1) {
                // Square
                ctx.strokeRect(-this.size / 2, -this.size / 2, this.size, this.size);
            } else {
                // Hexagon
                ctx.beginPath();
                for (let i = 0; i < 6; i++) {
                    const angle = (Math.PI / 3) * i;
                    const x = Math.cos(angle) * this.size;
                    const y = Math.sin(angle) * this.size;
                    if (i === 0) ctx.moveTo(x, y);
                    else ctx.lineTo(x, y);
                }
                ctx.closePath();
                ctx.stroke();
            }

            ctx.restore();
        }
    }

    // Create particles and shapes
    const particles = [];
    for (let i = 0; i < 150; i++) {
        particles.push(new Particle());
    }

    const shapes = [];
    for (let i = 0; i < 8; i++) {
        shapes.push(new GeometricShape());
    }

    // Animated gradient background
    let gradientOffset = 0;

    // Animation loop
    function animate() {
        requestAnimationFrame(animate);

        // Animated gradient background
        gradientOffset += 0.0005;
        const gradient = ctx.createLinearGradient(
            0, 0,
            canvas.width,
            canvas.height
        );

        const hue1 = 45 + Math.sin(gradientOffset) * 5;
        const hue2 = 45 + Math.cos(gradientOffset * 0.7) * 5;

        gradient.addColorStop(0, `hsla(${hue1}, 40%, 5%, 0.05)`);
        gradient.addColorStop(1, `hsla(${hue2}, 40%, 8%, 0.05)`);

        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        // Update and draw geometric shapes
        shapes.forEach(shape => {
            shape.update();
            shape.draw();
        });

        // Update and draw particles
        particles.forEach(particle => {
            particle.update();
            particle.draw();
        });

        // Draw connections between nearby particles
        for (let i = 0; i < particles.length; i++) {
            for (let j = i + 1; j < particles.length; j++) {
                const dx = particles[i].x - particles[j].x;
                const dy = particles[i].y - particles[j].y;
                const distance = Math.sqrt(dx * dx + dy * dy);

                if (distance < 120) {
                    const opacity = (1 - distance / 120) * 0.15;
                    ctx.strokeStyle = `rgba(212, 175, 55, ${opacity})`;
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.stroke();
                }
            }
        }

        // Pulsing center accent
        const time = Date.now() * 0.001;
        const pulse = (Math.sin(time) + 1) / 2;
        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;
        const radius = 100 + pulse * 50;

        const centerGradient = ctx.createRadialGradient(centerX, centerY, 0, centerX, centerY, radius);
        centerGradient.addColorStop(0, `rgba(212, 175, 55, ${0.05 + pulse * 0.05})`);
        centerGradient.addColorStop(1, 'rgba(212, 175, 55, 0)');

        ctx.fillStyle = centerGradient;
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, 0, Math.PI * 2);
        ctx.fill();
    }

    animate();
    console.log('✓ Premium 2D Animation Loaded');
}
