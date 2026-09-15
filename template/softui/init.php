<script>
(function () {
  var defAcc  = <?php echo json_encode(theme_config('default_accent', 'red')); ?>;
  var defMode = <?php echo json_encode(theme_config('default_mode', 'auto')); ?>;
  var a = null, m = null;
  try { a = localStorage.getItem('sf_accent'); } catch (e) {}
  try { m = localStorage.getItem('sf_mode'); } catch (e) {}
  var acc  = a || defAcc || 'red';
  var mode = m || defMode || 'auto';
  if (mode === 'auto') {
    mode = (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';
  }
  document.documentElement.setAttribute('data-accent', acc);
  document.documentElement.setAttribute('data-mode', mode);
})();
</script>