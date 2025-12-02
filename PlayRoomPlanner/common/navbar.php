<header class="header-area header-sticky">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <nav class="main-nav">

          <a href="/PlayRoomPlanner/index.php" class="logo">
            <h1 style="color: white; margin: 0; line-height: inherit;">PlayRoomPlanner</h1>
          </a>
          <ul class="nav">
            <li class="scroll-to-section"><a href="/PlayRoomPlanner/index.php" class="active">Home</a></li>
            <li class="has-sub">
              <a href="javascript:void(0)">Pagine</a>
              <ul class="sub-menu">
                <li><a href="#">Iscriviti</a></li>
                <li><a href="/PlayRoomPlanner/frontend/chi_siamo.php">Chi siamo</a></li>
                <li><a href="#">Contattaci</a></li>
              </ul>
            </li>
            <?php if (isset($_SESSION['user_id'])): ?>
              <li><a href="/frontend/area_personale.php">Area Personale</a></li>
            <?php else: ?>
              <li><a href="/frontend/login.php">Login</a></li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</header>