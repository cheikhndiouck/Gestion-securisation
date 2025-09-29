<!doctype html>
<html lang="fr">

<head>
	<title>Login</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


	<!-- <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet"> -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
	<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> -->
	<link rel="stylesheet" href="../css/index.css">
	<link rel="stylesheet" href="../css/global.css?v=1.2">

</head>

<body>
	<section class="vh-100 d-flex justify-content-center align-items-center bg-login">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-12 col-lg-10">
					<div class="wrap d-md-flex login-box">
						<div class="login-logo">
							<img src="../image/banniere.png" alt="Senelec Logo">
						</div>
						<div class="login-wrap p-4 p-md-5">
							<div class="d-flex">
								<div class="w-100">
									<h3 class="mb-4 text-light">Sign In</h3>
								</div>
							</div>
							<form action="../php/traitement.php" method="POST">
								<div class="form-group mb-3">
									<label class="label text-light" for="gmail">Username</label>
									<input type="text" id="gmail" name="gmail" class="form-control" placeholder="Username" required>
								</div>

								<div class="form-group mb-3 position-relative">
									<label class="label text-light" for="password">Password</label>
									<input type="password" id="password" name="password" class="form-control" placeholder="Password" required>

									<!-- petit œil -->
									<span class="toggle-password" style="position:absolute; top:38px; right:15px; cursor:pointer;">
										<i class="fa fa-eye"></i>
									</span>
								</div>

								<div class="form-group">
									<button type="submit" class="btn btn-theme1 text-info bg-dark">Login</button>
								</div>
								<div class="form-group d-md-flex">
									<div class="w-50 text-left">
										<label class="checkbox-wrap checkbox-primary mb-0 text-light">Remember Me
											<input type="checkbox" checked>
											<span class="checkmark"></span>
										</label>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<footer class="text-center py-3">
		<p class="text-muted mb-0">© 2025 Senelec - Gestion Sécurisation</p>
	</footer>
	<script src="../js/index.js"></script>
</body>

</html>