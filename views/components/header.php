<?php
echo '
<header>
  <div class="d-flex position-relative p-2">
    <img
      src="../image/banniere.png"
      class="img-fluid"
      alt="logo"
      style="max-width: 70px"
    />
  </div>
  <nav
    class="navbar navbar-expand-lg navbar-light bg-white px-3"
    style="border-radius: 50px 150px"
  >
    <div class="container">
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav"
        aria-controls="navbarNav"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div
        class="collapse navbar-collapse"
        id="navbarNav"
      >
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="./Bons.php">BONS DE SORTIES</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./Reception.php">RECEPTION</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./Facturation.php">FACTURATION</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./Setting.php">PARAMETRAGE</a>
          </li>
        </ul>

        <!-- Menu profil à l\'extrême droite -->
        <div class="dropdown ms-auto">
          <a class="btn btn-light dropdown-toggle d-flex align-items-center" 
             href="#" role="button" 
             id="dropdownMenuLink" 
             data-bs-toggle="dropdown" 
             aria-expanded="false">
            <i class="bi bi-person-circle fs-3"></i>
          </a>

          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink">
            <li><a class="dropdown-item" href="./profil.php">👤 Gérer le compte</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="../php/logout.php">🚪 Déconnexion</a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</header>
'
?>
