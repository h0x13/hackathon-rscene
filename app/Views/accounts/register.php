<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VenueConnect - Register</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: #2c3e50;
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255,255,255,0.1), transparent 70%);
            z-index: 0;
        }
        .form-container {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2), 0 0 0 4px rgba(110, 142, 251, 0.1);
            max-width: 600px;
            width: 100%;
            animation: fadeInUp 0.8s ease-in-out;
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            position: relative;
            z-index: 1;
            overflow: hidden;
        }
        .form-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at center, rgba(110, 142, 251, 0.05), transparent 70%);
            z-index: -1;
        }
        .form-container:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 50px rgba(0, 0, 0, 0.25), 0 0 0 6px rgba(110, 142, 251, 0.15);
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 2rem;
            color: #2c3e50;
            font-weight: 700;
            font-size: 1.8rem;
            letter-spacing: 1px;
            position: relative;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .form-container h2::after {
            content: '';
            display: block;
            width: 60px;
            height: 3px;
            background: linear-gradient(to right, #6e8efb, #a777e3);
            margin: 10px auto;
            border-radius: 2px;
        }
        .form-label {
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            position: relative;
            transition: color 0.3s ease;
        }
        .form-control {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 0.7rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.95);
            position: relative;
        }
        .form-control:focus {
            border-color: transparent;
            box-shadow: 0 0 10px rgba(110, 142, 251, 0.4), inset 0 0 0 2px #6e8efb;
            outline: none;
            background: white;
            transform: translateY(-2px);
        }
        .btn-primary {
            width: 100%;
            padding: 0.8rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 10px;
            background: linear-gradient(to right, #6e8efb, #a777e3);
            border: none;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-primary:hover {
            background: linear-gradient(to right, #5a75e8, #8e5ed0);
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(110, 142, 251, 0.6);
        }
        .btn-primary::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }
        .btn-primary:hover::after {
            left: 100%;
        }
        .form-text {
            color: #6c757d;
            font-size: 0.8rem;
            margin-top: 0.4rem;
            font-style: italic;
        }
        .mb-3,
        .row.mb-3 {
            margin-bottom: 1.5rem !important;
        }
        .text-center a {
            font-size: 0.9rem;
            color: #6e8efb;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }
        .text-center a:hover {
            color: #a777e3;
            text-decoration: none;
        }
        .text-center a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background: #a777e3;
            transition: width 0.3s ease;
        }
        .text-center a:hover::after {
            width: 100%;
        }
        .alert {
            margin-bottom: 1.5rem;
            border-radius: 10px;
            font-size: 0.95rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease-in-out;
        }
    </style>
</head>
<body>
    <!-- Include Preloader Component -->
    <?= view('components/preloader') ?>

    <div class="form-container">
        <h2>Register Account</h2>
        <div id="alertContainer"></div>
        <form id="registerForm" onsubmit="handleSubmit(event)">
            <?= csrf_field() ?>
            <div class="row mb-3">
                <div class="col">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First name" required>
                </div>
                <div class="col">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last name" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required
                       minlength="8" pattern=".{8,}" title="Password must be at least 8 characters long">
                <div class="form-text">Password must be at least 8 characters long</div>
            </div>
            <div class="mb-3">
                <label for="birthdate" class="form-label">Birthday</label>
                <input type="date" class="form-control" id="birthdate" name="birthdate" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
            <div class="mt-4 text-center">
                <a href="<?= base_url('login') ?>">Already have an account? Sign In</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function showAlert(message, type) {
            const alertContainer = document.getElementById('alertContainer');
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            alertContainer.appendChild(alertDiv);

            // Auto dismiss after 5 seconds
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }

        async function handleSubmit(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);

            // Validate password length
            const password = formData.get('password');
            if (password.length < 8) {
                showAlert('Password must be at least 8 characters long', 'danger');
                return;
            }

            const data = {
                first_name: formData.get('first_name'),
                last_name: formData.get('last_name'),
                email: formData.get('email'),
                password: formData.get('password'),
                birthdate: formData.get('birthdate')
            };

            try {
                const response = await axios.post('<?= base_url('register') ?>', data, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('[name=<?= csrf_token() ?>]').value,
                        'Content-Type': 'application/json'
                    }
                });

                if (response.data.success) {
                    showAlert(response.data.message, 'success');
                    // Redirect to verification page after 1 second
                    setTimeout(() => {
                        window.location.href = "<?= base_url('verify-code') ?>";
                    }, 1000);
                } else {
                    showAlert(response.data.message || 'An error occurred', 'danger');
                }
            } catch (error) {
                showAlert(error.response?.data?.message || 'An error occurred', 'danger');
            }
        }

        preloader.show('Custom message'); // Show with custom message
        preloader.hide(); // Hide the preloader
    </script>
</body>
</html>
