// Mobile nav toggle
const menuToggle = document.querySelector('.menu-toggle');
const navLinks = document.querySelector('.nav-links');
if (menuToggle) {
  menuToggle.addEventListener('click', () => navLinks.classList.toggle('open'));
}

// Dropdown (tap to open on touch/mobile, hover on desktop via CSS)
document.querySelectorAll('.has-dropdown > button').forEach(btn => {
  btn.addEventListener('click', () => {
    const parent = btn.parentElement;
    document.querySelectorAll('.has-dropdown').forEach(li => { if (li !== parent) li.classList.remove('open'); });
    parent.classList.toggle('open');
  });
});
document.addEventListener('click', (e) => {
  if (!e.target.closest('.has-dropdown')) {
    document.querySelectorAll('.has-dropdown').forEach(li => li.classList.remove('open'));
  }
});

// FAQ accordion
document.querySelectorAll('.faq-item button').forEach(btn => {
  btn.addEventListener('click', () => {
    const item = btn.closest('.faq-item');
    const wasOpen = item.classList.contains('open');
    item.parentElement.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
    if (!wasOpen) item.classList.add('open');
  });
});

// Back to top
const toTop = document.querySelector('.to-top');
if (toTop) {
  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const toggleToTop = () => toTop.classList.toggle('show', window.scrollY > 500);
  toggleToTop();
  window.addEventListener('scroll', toggleToTop, { passive: true });
  toTop.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' });
  });
}

// Contact form — AJAX submit with a no-JS fallback to contact.php
const contactForm = document.getElementById('contactForm');
if (contactForm) {
  const statusEl = document.getElementById('formStatus');
  const OK_MSG = 'Thanks — your message has been sent. We’ll get back to you shortly.';
  const ERR_MSG = 'Sorry, something went wrong. Please email hello@eformics.com directly.';

  const showStatus = (msg, ok) => {
    if (!statusEl) return;
    statusEl.textContent = msg;
    statusEl.className = 'form-status ' + (ok ? 'is-ok' : 'is-err');
    statusEl.hidden = false;
  };

  // Show a message if the no-JS fallback bounced the visitor back here.
  const params = new URLSearchParams(window.location.search);
  if (params.get('sent') === '1') showStatus(OK_MSG, true);
  else if (params.get('error') === '1') showStatus(ERR_MSG, false);

  contactForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const btn = contactForm.querySelector('button[type="submit"]');
    const label = btn ? btn.textContent : '';
    if (btn) { btn.disabled = true; btn.textContent = 'Sending…'; }

    fetch(contactForm.action, {
      method: 'POST',
      body: new FormData(contactForm),
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
      .then(res => res.json().catch(() => ({})).then(data => ({ ok: res.ok, data })))
      .then(({ ok, data }) => {
        if (ok && data.ok) {
          contactForm.reset();
          showStatus(OK_MSG, true);
        } else {
          showStatus((data && data.error) || ERR_MSG, false);
        }
      })
      .catch(() => showStatus('Network error — please email hello@eformics.com directly.', false))
      .finally(() => {
        if (btn) { btn.disabled = false; btn.textContent = label; }
      });
  });
}

// Theme toggle + cross-tab sync now lives in the shared assets/js/theme.js
// (loaded on both the public site and /admin so the two stay in step).

// Scroll reveal
const revealEls = document.querySelectorAll('.reveal');
if ('IntersectionObserver' in window && revealEls.length) {
  const io = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('in');
        io.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12 });
  revealEls.forEach(el => io.observe(el));
} else {
  revealEls.forEach(el => el.classList.add('in'));
}
