<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
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
                <li><a href="/PlayRoomPlanner/frontend/chi_siamo.php">Chi siamo</a></li>
                <li><a href="/PlayRoomPlanner/frontend/contattaci.php">Contattaci</a></li>
              </ul>
            </li>

            <?php
            if (isset($_SESSION["user"])) {
              echo '<li><a href="/PlayRoomPlanner/backend/logout.php"><img src="/PlayRoomPlanner/immagini/logout_icon.png" alt="Logout" style="width: 30px; height: 30px; vertical-align: middle;"></a></li>';
            }
            ?>

            <?php if (isset($_SESSION['user'])): ?>
              <li>
                <a href="/PlayRoomPlanner/frontend/area_personale.php">
                  <?php
                  $filename = $_SESSION['nome'] . "_" . $_SESSION['cognome'] . ".jpg";

                  if (!file_exists(__DIR__ . "/../immagini/foto_profilo/" . $filename)) {
                    echo '<img src="/PlayRoomPlanner/immagini/default_profile.webp" alt="Default Profile" 
                          style="width: 30px; height: 30px; border-radius: 50%; margin-right: 8px; vertical-align: middle;"> ' . $_SESSION['nome'];
                  } else {
                    echo '<img src="/PlayRoomPlanner/immagini/foto_profilo/' . $filename . '" alt="Profile"
                          style="width: 30px; height: 30px; border-radius: 50%; margin-right: 8px; vertical-align: middle;">
                    ' . $_SESSION['nome'] . ' ';
                  }
                  ?>
                </a>
              </li>
            <?php else: ?>
              <li><a href="/PlayRoomPlanner/frontend/login.php">Login</a></li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</header>