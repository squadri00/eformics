/* ===========================================================================
   Shared light/dark theme controller — used by the public site AND /admin.
   The theme is applied before paint by a tiny inline script in each <head>;
   this file wires up the toggle button(s) and keeps every open tab in sync.
   Preference is stored per browser under localStorage "ef-theme"; with no
   stored choice the OS setting is followed live.
   =========================================================================== */
(function () {
  var root = document.documentElement;
  var KEY = 'ef-theme';
  var mq = window.matchMedia('(prefers-color-scheme: dark)');
  var meta = document.querySelector('meta[name="theme-color"]');
  var lightColor = meta ? meta.getAttribute('content') || '#393193' : '#393193';
  var DARK_COLOR = '#100e17';

  function stored() {
    try {
      var v = localStorage.getItem(KEY);
      return v === 'light' || v === 'dark' ? v : null;
    } catch (e) { return null; }
  }
  function current() {
    return root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
  }

  function apply(theme, persist) {
    root.setAttribute('data-theme', theme);
    if (persist) { try { localStorage.setItem(KEY, theme); } catch (e) {} }
    var dark = theme === 'dark';
    document.querySelectorAll('.theme-toggle').forEach(function (b) {
      b.setAttribute('aria-pressed', dark ? 'true' : 'false');
      b.setAttribute('title', dark ? 'Switch to light mode' : 'Switch to dark mode');
    });
    if (meta) meta.setAttribute('content', dark ? DARK_COLOR : lightColor);
  }

  // Reconcile button/meta state with whatever the pre-paint script set.
  apply(current(), false);

  document.querySelectorAll('.theme-toggle').forEach(function (b) {
    b.addEventListener('click', function () {
      apply(current() === 'dark' ? 'light' : 'dark', true);
    });
  });

  // Another tab changed the preference.
  window.addEventListener('storage', function (e) {
    if (e.key !== KEY) return;
    var next = e.newValue === 'dark' || e.newValue === 'light'
      ? e.newValue
      : (mq.matches ? 'dark' : 'light');
    apply(next, false);
  });

  // OS preference changed and the visitor has not overridden it.
  var onMq = function (e) { if (!stored()) apply(e.matches ? 'dark' : 'light', false); };
  if (mq.addEventListener) mq.addEventListener('change', onMq);
  else if (mq.addListener) mq.addListener(onMq);
})();
