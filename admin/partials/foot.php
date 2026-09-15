  </main>
</div>
<script>
(function () {
  var t = document.getElementById('admSideToggle');
  var s = document.getElementById('admSide');
  if (t && s) {
    t.addEventListener('click', function () { s.classList.toggle('open'); });
  }
})();
</script>
<script src="<?= asset('/assets/js/theme.js') ?>"></script>
<script src="<?= asset('/admin/assets/admin.js') ?>"></script>
</body>
</html>
