<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-container {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.15);
            max-width: 500px;
            width: 100%;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 1.2rem;
            color: #2c3e50;
            font-weight: 600;
            font-size: 1.4rem;
            letter-spacing: 0.8px;
        }

        .form-label {
            font-weight: 600;
            color: #34495e;
            font-size: 0.8rem;
            margin-bottom: 0.3rem;
        }

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            padding: 0.5rem;
            font-size: 0.85rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            border-color: #6e8efb;
            box-shadow: 0 0 6px rgba(110, 142, 251, 0.4);
            outline: none;
        }

        .btn-primary {
            width: 100%;
            padding: 0.6rem;
            font-size: 0.95rem;
            font-weight: 500;
            border-radius: 6px;
            background: linear-gradient(to right, #6e8efb, #a777e3);
            border: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 12px rgba(110, 142, 251, 0.5);
        }

        .form-text {
            color: #7f8c8d;
            font-size: 0.7rem;
            margin-top: 0.2rem;
        }

        .mb-3 {
            margin-bottom: 1rem !important;
        }

        .text-center a {
            font-size: 0.8rem;
            color: #6e8efb;
        }

        .text-center a:hover {
            color: #a777e3;
        }

        .alert {
            margin-bottom: 1rem;
            border-radius: 6px;
            font-size: 0.85rem;
        }
    </style>
</head>

<body>
    <!-- Include Preloader Component -->
    <?= view('components/preloader') ?>

    <div class="form-container">
        <h2>Email Verification</h2>
        <div id="alertContainer"></div>
        <form id="verificationForm" onsubmit="handleSubmit(event)">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label for="verification_code" class="form-label">Verification Code</label>
                <input type="text" class="form-control" id="verification_code" name="verification_code" required>
                <div class="form-text">Please enter the verification code sent to your email</div>
            </div>
            <button type="submit" class="btn btn-primary">Verify Email</button>
            <div class="mt-3 text-center">
                <a href="<?= base_url('login') ?>" class="text-decoration-none">Back to Login</a>
            </div>
        </form>
    </div>

    <!-- Latest Bootstrap JS Bundle with Popper -->
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
            
            const data = {
                verification_code: formData.get('verification_code')
            };
            
            try {
                const response = await axios.post('<?= base_url('verify-email') ?>', data, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('[name=<?= csrf_token() ?>]').value,
                        'Content-Type': 'application/json'
                    }
                });
                
                if (response.data.success) {
                    showAlert(response.data.message, 'success');
                    // Redirect to login page after 1 second
                    setTimeout(() => {
                        window.location.href = "<?= base_url('login') ?>";
                    }, 1000);
                } else {
                    showAlert(response.data.message || 'An error occurred', 'danger');
                }
            } catch (error) {
                showAlert(error.response?.data?.message || 'An error occurred', 'danger');
            }
        }
    </script>
</body>
</html>
