<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRGY 413 Registration & Login</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
            text-decoration: none;
            list-style: none;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(90deg, #e2e2e2, #c9d6ff);
        }

        .container {
            position: relative;
            width: 900px;
            height: 700px;
            background: #fff;
            margin: 20px;
            border-radius: 30px;
            box-shadow: 0 0 30px rgba(0, 0, 0, .2);
            overflow: hidden;
        }

        .container h1 {
            font-size: 36px;
            margin: -10px 0;
        }

        .container p {
            font-size: 14.5px;
            margin: 15px 0;
        }

        form { 
            width: 100%; 
        }

        .form-box {
            position: absolute;
            right: 0;
            width: 50%;
            height: 100%;
            background: #fff;
            display: flex;
            align-items: center;
            color: #333;
            text-align: center;
            padding: 40px;
            z-index: 1;
            transition: right 0.6s ease-in-out 1.2s, visibility 0s 1s;
        }

        .container.active .form-box { 
            right: 50%; 
        }

        .form-box.register { 
            visibility: hidden; 
        }

        .container.active .form-box.register { 
            visibility: visible; 
        }

        .input-box {
            position: relative;
            margin: 30px 0;
        }

        .input-box input,
        .input-box select {
            width: 100%;
            padding: 13px 50px 13px 20px;
            background: #eee;
            border-radius: 8px;
            border: none;
            outline: none;
            font-size: 16px;
            color: #333;
            font-weight: 500;
        }

        .input-box input::placeholder,
        .input-box select:disabled,
        .input-box select option[value=""] {
            color: #888;
            font-weight: 400;
        }

        .input-box select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        .input-box i {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
        }

        .forgot-link { 
            margin: -15px 0 15px; 
        }

        .forgot-link a {
            font-size: 14.5px;
            color: #333;
        }

        .btn {
            width: 100%;
            height: 48px;
            background: #182052;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, .1);
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: #fff;
            font-weight: 600;
        }

        .toggle-box {
            position: absolute;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .brgy-logo {
            display: block;
            margin-bottom: 25px;
            text-align: center;
        }

        .brgy-logo img {
            width: 100px;
            height: auto;
        }

        .toggle-box::before {
            content: '';
            position: absolute;
            left: -250%;
            width: 300%;
            height: 100%;
            background: #182052;
            border-radius: 150px;
            z-index: 2;
            transition: left 1.8s ease-in-out;
            will-change: left;
        }

        .container.active .toggle-box::before { 
            left: 50%; 
        }

        .toggle-panel {
            position: absolute;
            width: 50%;
            height: 100%;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 3;
            transition: all 0.6s ease-in-out;
            pointer-events: auto;
        }

        .toggle-panel.toggle-left { 
            left: 0;
            transition-delay: 1.2s; 
        }

        .container.active .toggle-panel.toggle-left {
            left: -50%;
            transition-delay: 0.6s;
        }

        .toggle-panel.toggle-right { 
            right: -50%;
            transition-delay: 0.6s;
        }

        .container.active .toggle-panel.toggle-right {
            right: 0;
            transition-delay: 1.2s;
        }

        .toggle-panel p { 
            margin-bottom: 20px; 
        }

        .toggle-panel .btn {
            width: 160px;
            height: 46px;
            background: #f2c516;
            border: 2px solid #fff;
            box-shadow: none;
            pointer-events: auto;
        }

        @media screen and (max-width: 650px) {
            .container { 
                height: calc(100vh - 40px); 
            }

            .form-box {
                bottom: 0;
                width: 100%;
                height: 70%;
            }

            .container.active .form-box {
                right: 0;
                bottom: 30%;
            }

            .toggle-box::before {
                left: 0;
                top: -270%;
                width: 100%;
                height: 300%;
                border-radius: 20vw;
                transition: top 1.8s ease-in-out;
            }

            .container.active .toggle-box::before {
                left: 0;
                top: 70%;
            }

            .container.active .toggle-panel.toggle-left {
                left: 0;
                top: -30%;
            }

            .toggle-panel { 
                width: 100%;
                height: 30%;
            }

            .toggle-panel.toggle-left { 
                top: 0; 
            }

            .toggle-panel.toggle-right {
                right: 0;
                bottom: -30%;
            }

            .container.active .toggle-panel.toggle-right { 
                bottom: 0; 
            }
        }

        @media screen and (max-width: 400px) {
            .form-box { 
                padding: 20px; 
            }

            .toggle-panel h1 {
                font-size: 30px; 
            }
        }

        #loading-overlay {
            position: fixed;
            top: 0; 
            left: 0;
            width: 100%; 
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .spinner {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #182052;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #28a745;
            color: white;
            padding: 16px 24px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease, transform 0.3s ease;
            transform: translateY(20px);
            z-index: 10000;
            max-width: 400px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .toast.show {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0);
        }

        .toast.error {
            background-color: #dc3545;
        }

        .toast.success {
            background-color: #28a745;
        }

        .toast i {
            font-size: 24px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Login Form -->
        <div class="form-box login">
            <form method="post" action="register.php">
                <h1>Login</h1>
                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <button type="submit" class="btn" name="signIn">Login</button>
            </form>
        </div>

        <!-- Registration Form -->
        <div class="form-box register">
            <form method="post" action="register.php">
                <h1>Registration</h1>
                <div class="input-box">
                    <input type="text" name="firstName" placeholder="First Name" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="text" name="lastName" placeholder="Last Name" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required>
                    <i class='bx bxs-envelope'></i>
                </div>
                <div class="input-box">
                    <input type="tel" name="contact" placeholder="Contact Number" required>
                    <i class='bx bxs-contact'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <p>By clicking "Register", you agree to our <a href="terms.php" style="color:#182052; text-decoration:underline;">Terms & Conditions</a> and confirm that you have read our <a href="privacy.php" style="color:#182052; text-decoration:underline;">Privacy Policy.</a></p>
                <button type="submit" class="btn" name="signUp">Register</button>
            </form>
        </div>

        <!-- Toggle Panels -->
        <div class="toggle-box">
            <div class="toggle-panel toggle-left">
                <span class="image brgy-logo">
                    <img src="images/brgyLogo.png" alt="Brgy Logo">
                </span>
                <h1>Mabuhay!</h1>
                <p>Maging bahagi ng ating online community.</p>
                <p>Don't have an account?</p>
                <button class="btn register-btn" type="button">Register</button>
            </div>

            <div class="toggle-panel toggle-right">
                <span class="image brgy-logo">
                    <img src="images/brgyLogo.png" alt="Brgy Logo">
                </span>
                <h1>Kumusta,</h1>
                <h1>kapitbahay!</h1>
                <p class="m">Mag-log in na para makibalita.</p>
                <p>Already have an account?</p>
                <button class="btn login-btn" type="button">Login</button>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay">
        <div class="spinner"></div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast"></div>

    <script>
        const container = document.querySelector('.container');
        const registerBtn = document.querySelector('.register-btn');
        const loginBtn = document.querySelector('.login-btn');

        // Toggle between login and register panels
        registerBtn.addEventListener('click', (e) => {
            e.preventDefault();
            container.classList.add('active');
        });

        loginBtn.addEventListener('click', (e) => {
            e.preventDefault();
            container.classList.remove('active');
        });

        // Show loading overlay on form submit and prevent double submission
        document.querySelectorAll("form").forEach(form => {
            form.addEventListener("submit", (e) => {
                if (form.dataset.submitted === "true") {
                    e.preventDefault();
                    return;
                }
                
                form.dataset.submitted = "true";
                
                const loadingOverlay = document.getElementById("loading-overlay");
                if (loadingOverlay) {
                    loadingOverlay.style.display = "flex";
                }
                
                setTimeout(() => {
                    form.dataset.submitted = "false";
                    if (loadingOverlay) {
                        loadingOverlay.style.display = "none";
                    }
                }, 10000);
            });
        });

        // Toast notification function
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const iconClass = type === 'error' ? 'bx-error-circle' : 'bx-check-circle';
            
            toast.innerHTML = `<i class='bx ${iconClass}'></i><span>${message}</span>`;
            toast.className = 'toast show ' + type;
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 4000);
        }

        // Check for PHP session messages and URL parameters on page load
        document.addEventListener("DOMContentLoaded", () => {
            // Check URL parameters first
            const urlParams = new URLSearchParams(window.location.search);
            const loginStatus = urlParams.get('login');
            const registerStatus = urlParams.get('register');
            
            if (loginStatus === 'success') {
                showToast('Login successful! Redirecting...', 'success');
                // Clean URL
                window.history.replaceState({}, document.title, window.location.pathname);
                
                // Redirect after showing toast
                setTimeout(() => {
                    <?php
                    if (isset($_SESSION['redirect_url'])) {
                        echo "window.location.href = '" . $_SESSION['redirect_url'] . "';";
                        unset($_SESSION['redirect_url']);
                    }
                    ?>
                }, 2000); // Wait 2 seconds to show the toast
            } else if (registerStatus === 'success') {
                showToast('Registration successful! Please login.', 'success');
                // Clean URL
                window.history.replaceState({}, document.title, window.location.pathname);
            }
            
            // Check session messages
            <?php
            if (isset($_SESSION['success'])) {
                echo "showToast('" . addslashes($_SESSION['success']) . "', 'success');";
                unset($_SESSION['success']);
            }
            if (isset($_SESSION['error'])) {
                echo "showToast('" . addslashes($_SESSION['error']) . "', 'error');";
                unset($_SESSION['error']);
            }
            ?>
        });
    </script>
</body>
</html>