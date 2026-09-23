<?php
session_start();

if (isset($_GET['logout'])) {
	$_SESSION = [];
	session_destroy();
	header('Location: LOGIN.PHP');
	exit;
}

$loginError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$email = trim($_POST['email'] ?? '');
	$password = $_POST['password'] ?? '';

	if ($email !== '' && $password !== '') {
		$_SESSION['authenticated'] = true;
		$_SESSION['user_email'] = $email;
		$_SESSION['user_role'] = 'admin';
		header('Location: Temple.php?page=dashboard');
		exit;
	}

	$loginError = 'Enter your email address and password to continue.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>FarmHouse | Sign in</title>
	<style>
		@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap');

		:root { --brown: #713b1c; --brown-dark: #4f2815; --cream: #f8f3e9; --paper: #fffdf8; --ink: #2e271f; --muted: #82766a; --line: #ded5c8; }
		* { box-sizing: border-box; }
		body { margin: 0; min-height: 100vh; background: var(--cream); color: var(--ink); font-family: 'DM Sans', sans-serif; -webkit-font-smoothing: antialiased; }
		.page { min-height: 100vh; display: grid; grid-template-columns: minmax(420px, 1.08fr) minmax(430px, .92fr); }
		.visual { position: relative; min-height: 720px; overflow: hidden; color: white; background: linear-gradient(145deg, rgba(54, 31, 17, .28), rgba(39, 27, 17, .7)), url('https://images.unsplash.com/photo-1560493676-04071c5f467b?auto=format&fit=crop&w=1500&q=85') center / cover; }
		.visual::after { content: ''; position: absolute; inset: 0; background: linear-gradient(180deg, rgba(32, 21, 13, .36), transparent 38%, rgba(32, 21, 13, .8)); }
		.visual-header, .visual-copy { position: relative; z-index: 1; }
		.visual-header { display: flex; align-items: center; justify-content: space-between; padding: 28px 8%; }
		.brand { display: flex; align-items: center; gap: 10px; color: white; font-size: 17px; font-weight: 700; letter-spacing: .02em; }
		.brand-mark { display: grid; width: 35px; height: 35px; place-items: center; border: 1px solid rgba(255,255,255,.7); border-radius: 50%; font-size: 18px; }
		.back-link { color: rgba(255,255,255,.88); font-size: 12px; text-decoration: none; }
		.back-link:hover { color: white; text-decoration: underline; }
		.visual-copy { position: absolute; right: 10%; bottom: 9%; left: 10%; max-width: 560px; }
		.eyebrow { display: flex; align-items: center; gap: 10px; margin-bottom: 17px; color: #e6c99f; font-size: 11px; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; }
		.eyebrow::before { width: 30px; height: 1px; background: #e6c99f; content: ''; }
		h1, h2 { font-family: 'Playfair Display', Georgia, serif; font-weight: 600; letter-spacing: -.035em; }
		h1 { max-width: 570px; margin: 0 0 16px; font-size: clamp(38px, 4.2vw, 62px); line-height: 1.04; }
		.visual-copy p { max-width: 430px; margin: 0; color: rgba(255,255,255,.82); font-size: 14px; line-height: 1.65; }
		.form-panel { display: flex; flex-direction: column; justify-content: center; min-height: 720px; padding: 56px clamp(40px, 8vw, 115px); background: var(--paper); }
		.form-wrap { width: 100%; max-width: 405px; margin: 0 auto; }
		.panel-label { margin: 0 0 11px; color: var(--brown); font-size: 12px; font-weight: 700; letter-spacing: .13em; text-transform: uppercase; }
		h2 { margin: 0; color: var(--ink); font-size: clamp(32px, 3vw, 44px); line-height: 1.12; }
		.intro { margin: 14px 0 35px; color: var(--muted); font-size: 14px; }
		.login-error { margin: -16px 0 20px; padding: 11px 13px; border-left: 3px solid #b54b32; background: #fff0eb; color: #8b3525; font-size: 12px; line-height: 1.45; }
		form { display: grid; gap: 20px; }
		.field { display: grid; gap: 8px; }
		label { color: #50483f; font-size: 12px; font-weight: 700; }
		input { width: 100%; height: 49px; padding: 0 15px; border: 1px solid var(--line); border-radius: 3px; outline: none; background: white; color: var(--ink); font: inherit; transition: border-color .2s, box-shadow .2s; }
		input:focus { border-color: var(--brown); box-shadow: 0 0 0 3px rgba(113, 59, 28, .1); }
		.password-field { position: relative; }
		.password-field input { padding-right: 50px; }
		.toggle-password { position: absolute; right: 0; bottom: 0; width: 48px; height: 49px; border: 0; background: transparent; color: var(--muted); font-size: 17px; cursor: pointer; }
		.toggle-password:hover { color: var(--brown); }
		.form-options { display: flex; align-items: center; justify-content: space-between; margin-top: -4px; }
		.remember { display: flex; align-items: center; gap: 8px; color: var(--muted); font-size: 12px; font-weight: 500; }
		.remember input { width: 15px; height: 15px; accent-color: var(--brown); }
		.forgot, .register a { color: var(--brown); font-weight: 700; text-decoration: none; }
		.forgot { font-size: 12px; }
		.forgot:hover, .register a:hover { text-decoration: underline; }
		.submit-button { width: 100%; height: 51px; margin-top: 5px; border: 0; border-radius: 3px; background: var(--brown); color: white; font: inherit; font-size: 13px; font-weight: 700; letter-spacing: .02em; cursor: pointer; transition: background .2s, transform .2s; }
		.submit-button:hover { background: var(--brown-dark); transform: translateY(-1px); }
		.register { margin: 30px 0 0; color: var(--muted); font-size: 13px; text-align: center; }
		.register a { font-size: inherit; }
		.fine-print { margin: 50px 0 0; color: #a1978b; font-size: 11px; line-height: 1.6; text-align: center; }
		@media (max-width: 800px) {
			.page { display: block; }
			.visual { min-height: 410px; }
			.visual-header { padding: 22px 7%; }
			.visual-copy { bottom: 11%; left: 7%; }
			h1 { max-width: 500px; font-size: clamp(36px, 10vw, 52px); }
			.visual-copy p { display: none; }
			.form-panel { min-height: auto; padding: 52px 8% 45px; }
			.fine-print { margin-top: 36px; }
		}
		@media (max-width: 440px) {
			.visual { min-height: 340px; }
			.visual-header { padding-top: 18px; }
			.back-link { display: none; }
			.form-panel { padding-right: 24px; padding-left: 24px; }
			.form-options { align-items: flex-start; gap: 15px; }
		}
	</style>
</head>
<body>
	<main class="page">
		<section class="visual" aria-label="FarmHouse welcome">
			<header class="visual-header">
				<div class="brand"><span class="brand-mark" aria-hidden="true">⌂</span> FarmHouse</div>
				<a class="back-link" href="../HTML/Index.php">Back to website &rarr;</a>
			</header>
			<div class="visual-copy">
				<div class="eyebrow">Good things grow here</div>
				<h1>Closer to the land.<br>Closer to your farm.</h1>
				<p>Manage your livestock, keep your records in order, and make every day on the farm count.</p>
			</div>
		</section>
		<section class="form-panel">
			<div class="form-wrap">
				<p class="panel-label">FarmHouse portal</p>
				<h2>Welcome back</h2>
				<p class="intro">Sign in to continue managing your farm.</p>
				<?php if ($loginError !== ''): ?>
					<p class="login-error" role="alert"><?php echo htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8'); ?></p>
				<?php endif; ?>
				<form action="" method="post">
					<div class="field">
						<label for="email">Email address</label>
						<input id="email" name="email" type="email" autocomplete="email" placeholder="you@example.com" required>
					</div>
					<div class="field password-field">
						<label for="password">Password</label>
						<input id="password" name="password" type="password" autocomplete="current-password" placeholder="Enter your password" required>
						<button class="toggle-password" type="button" aria-label="Show password" aria-pressed="false">&#9673;</button>
					</div>
					<div class="form-options">
						<label class="remember"><input type="checkbox" name="remember"> Remember me</label>
						<a class="forgot" href="#forgot-password">Forgot password?</a>
					</div>
					<button class="submit-button" type="submit">Sign in to your farm &rarr;</button>
				</form>
				<p class="register">New to FarmHouse? <a href="#create-account">Create an account</a></p>
				<p class="fine-print">By continuing, you agree to our terms of service and privacy policy.</p>
			</div>
		</section>
	</main>
	<script>
		const toggle = document.querySelector('.toggle-password');
		const password = document.querySelector('#password');
		toggle.addEventListener('click', () => {
			const isVisible = password.type === 'text';
			password.type = isVisible ? 'password' : 'text';
			toggle.setAttribute('aria-pressed', String(!isVisible));
			toggle.setAttribute('aria-label', isVisible ? 'Show password' : 'Hide password');
		});
	</script>
</body>
</html>
