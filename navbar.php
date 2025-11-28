  <!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index.php" class="nav-link">Home</a>
      </li>
     
    </ul>

    

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      
      <li>
        <a class="nav-link">
          <span class="d-none d-md-inline"><?php echo $_SESSION["name"]; ?></span>
        </a>
      </li>
        
      <li class="nav-item">
        <a href="privacy_statement.php" class="nav-link">
          <span class="d-none d-md-inline">Privacy Statement</span>
        </a>
      </li>
        <li class="nav-item">
        <a href="refund_policy.php" class="nav-link">
          <span class="d-none d-md-inline">Refund Policy</span>
        </a>
      </li>
        <li class="nav-item">
        <a href="terms_of_service.php" class="nav-link">
          <span class="d-none d-md-inline">Terms Of Service</span>
        </a>
      </li>
        
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      
    </ul>
  </nav>
  
   <!-- /.navbar -->