/* Skill Academy v2 — app.js */

// ── CANVAS PARTICLES ──────────────────────────────────────────
(function () {
  const canvas = document.getElementById('heroCanvas');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');
  let W, H, particles = [];

  function resize() {
    W = canvas.width  = canvas.offsetWidth;
    H = canvas.height = canvas.offsetHeight;
  }
  window.addEventListener('resize', resize);
  resize();

  class P {
    constructor() { this.init(true); }
    init(scatter) {
      this.x  = Math.random() * W;
      this.y  = scatter ? Math.random() * H : H + 8;
      this.r  = 1 + Math.random() * 1.8;
      this.vy = -(0.35 + Math.random() * 0.65);
      this.vx = (Math.random() - 0.5) * 0.3;
      this.life = 0;
      this.maxLife = 180 + Math.random() * 180;
      this.color = Math.random() > 0.5 ? '16,185,129' : '6,182,212';
    }
    draw() {
      const alpha = Math.sin((this.life / this.maxLife) * Math.PI) * 0.55;
      ctx.beginPath();
      ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2);
      ctx.fillStyle = `rgba(${this.color},${alpha})`;
      ctx.fill();
      this.x += this.vx;
      this.y += this.vy;
      this.life++;
      if (this.life >= this.maxLife) this.init(false);
    }
  }

  for (let i = 0; i < 70; i++) particles.push(new P());

  (function loop() {
    ctx.clearRect(0, 0, W, H);
    particles.forEach(p => p.draw());
    requestAnimationFrame(loop);
  })();
})();

// ── SCROLL REVEAL ─────────────────────────────────────────────
(function () {
  const els = document.querySelectorAll('.course-card, .feat-card, .stat-card');
  if (!els.length) return;
  const io = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.style.opacity = '1';
        e.target.style.transform = 'translateY(0)';
        io.unobserve(e.target);
      }
    });
  }, { threshold: 0.1 });
  els.forEach((el, i) => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(22px)';
    el.style.transition = `opacity 0.45s ${i * 0.07}s ease, transform 0.45s ${i * 0.07}s ease`;
    io.observe(el);
  });
})();

// ── PASSWORD TOGGLE ───────────────────────────────────────────
function togglePw(id, btn) {
  const el = document.getElementById(id);
  if (!el) return;
  const show = el.type === 'password';
  el.type = show ? 'text' : 'password';
  btn.textContent = show ? '🙈' : '👁';
}

// ── NAV SHADOW ON SCROLL ──────────────────────────────────────
(function () {
  const nav = document.querySelector('nav');
  if (!nav) return;
  window.addEventListener('scroll', () => {
    nav.style.borderBottomColor = window.scrollY > 30
      ? 'rgba(46,74,114,0.7)'
      : 'var(--border)';
  }, { passive: true });
})();