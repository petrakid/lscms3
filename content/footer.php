  <footer class="page-footer teal">
    <div class="container">
      <div class="row">
     
      <?php
     $foot = $p->getContent($_GET['p'], 2);
     $f = $foot->fetch(PDO::FETCH_ASSOC);
     echo $f['section_content'];
     ?>
     
      </div>
    </div>
    <div class="footer-copyright">
      <div class="container">
      Copyright &copy; <?php echo date('Y') ?> <?php echo $g['site_name'] ?> | 
      Made by <a class="brown-text text-lighten-3" href="https://www.luthersites.net">Luthersites</a>
      </div>
    </div>
  </footer>
  
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script src="<?php echo $g['site_url'] ?>/js/app.js"></script>

<?php
if(isset($_SESSION['isLoggedIn'])) {
     ?>
     <script src="<?php echo $g['site_url'] ?>/admin/js/a-app.js"></script>
     <script>
     UPLOADCARE_PUBLIC_KEY = '2d4d1228ab27d535e8a2';
     </script>  
          
     <?php
}
?>
</body>
</html>