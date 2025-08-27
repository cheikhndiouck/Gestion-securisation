<?php
echo '
    <header>
      <div class="d-flex position-relative p-2">
        <img
          src="image/banniere.png"
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
            class="collapse navbar-collapse justify-content-between"
            id="navbarNav"
          >
            <ul class="nav">
              <li class="nav-item dropdown">
                <a
                  class="nav-link dropdown-toggle"
                  href="#"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  BONS DE SORTIES
                </a>

                <ul class="dropdown-menu">
                  <li>
                    <a class="dropdown-item" href="Materiel.php">Matériels</a>
                  </li>
                  <li><a class="dropdown-item" href="#">Responsable</a></li>
                </ul>
              </li>
            </ul>

            <ul class="nav">
              <li class="nav-item dropdown">
                <a
                  class="nav-link dropdown-toggle"
                  href="Reception.html"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  RECEPTION
                </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">GIE</a></li>
                  <li><a class="dropdown-item" href="#">FACTURE</a></li>
                </ul>
              </li>
            </ul>

            <ul class="nav">
              <li class="nav-item dropdown">
                <a
                  class="nav-link dropdown-toggle"
                  href="#"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  FACTURATION
                </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Compteur</a></li>
                </ul>
              </li>
            </ul>
          </div>

          <!-- Barre de recherche à droite -->
          <form class="d-flex ms-auto" role="search">
            <input
              class="form-control me-2"
              type="search"
              placeholder="Rechercher..."
              aria-label="Search"
            />
            <button class="btn btn-outline-primary" type="submit">
              Rechercher
            </button>
          </form>
        </div>
      </nav>
    </header>
';