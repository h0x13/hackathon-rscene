<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>
Talents - Profile Setting
<?= $this->endSection() ?>


<?= $this->section('local_css') ?>
<style>
    .profile-container {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.15);
        max-width: 800px;
        margin: 80px auto;
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

    .profile-header {
        text-align: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .profile-header h1 {
        color: #2c3e50;
        font-weight: 600;
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .profile-header p {
        color: #7f8c8d;
        margin: 0;
    }

    .form-label {
        font-weight: 600;
        color: #34495e;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 0.7rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #6e8efb;
        box-shadow: 0 0 8px rgba(110, 142, 251, 0.4);
        outline: none;
    }

    .form-control:disabled {
        background-color: #f8f9fa;
        cursor: not-allowed;
    }

    .btn-primary {
        padding: 0.7rem 1.5rem;
        font-size: 1rem;
        font-weight: 500;
        border-radius: 8px;
        background: linear-gradient(to right, #6e8efb, #a777e3);
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(110, 142, 251, 0.5);
    }

    .btn-outline-primary {
        padding: 0.7rem 1.5rem;
        font-size: 1rem;
        font-weight: 500;
        border-radius: 8px;
        border: 2px solid #6e8efb;
        color: #6e8efb;
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover {
        background: linear-gradient(to right, #6e8efb, #a777e3);
        border-color: transparent;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(110, 142, 251, 0.3);
    }

    .alert {
        border-radius: 8px;
        font-size: 0.95rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border: none;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }

    .form-section {
        background-color: #f8f9fa;
        padding: 1.5rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
    }

    .form-section h2 {
        color: #2c3e50;
        font-size: 1.3rem;
        margin-bottom: 1.2rem;
        font-weight: 600;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
    }

    .profile-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6e8efb, #a777e3);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        font-weight: 600;
    }

    .profile-details {
        flex: 1;
    }

    .profile-name {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
    }

    .profile-email {
        color: #7f8c8d;
        margin: 0.3rem 0 0 0;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="profile-container">
    <div class="profile-header">
        <h1>Profile Settings</h1>
        <p>Manage your account information and preferences</p>
    </div>

    <div id="alertContainer"></div>

    <div class="profile-info">
        <div class="profile-avatar">
           JD
        </div>
        <div class="profile-details">
            <h2 class="profile-name">John Doe</h2>
            <p class="profile-email">john.doe@gmail.com</p>
        </div>
    </div>

    <form id="profileForm" onsubmit="handleSubmit(event)">
        <div class="form-section">
            <h2>Personal Information</h2>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ user_credential.user_profile.first_name }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="middle_name" class="form-label">Middle Name</label>
                    <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{ user_credential.user_profile.middle_name|default:'' }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ user_credential.user_profile.last_name }}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" value="{{ user_credential.email }}" disabled>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="birthdate" class="form-label">Birthdate</label>
                    <input type="date" class="form-control" id="birthdate" name="birthdate" value="{{ user_credential.user_profile.birthdate|date:'Y-m-d' }}" required>
                </div>
            </div>
        </div>

        <div class="action-buttons">
            <a href="{% url 'change_password' %}" class="btn btn-outline-primary">Change Password</a>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>


<?= $this->section('local_javascript') ?>
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
            first_name: formData.get('first_name'),
            middle_name: formData.get('middle_name'),
            last_name: formData.get('last_name'),
            birthdate: formData.get('birthdate')
        };
        
        try {
            const response = await axios.post('{% url "update_profile" %}', data, {
                headers: {
                    'X-CSRFToken': document.querySelector('[name=csrfmiddlewaretoken]').value,
                    'Content-Type': 'application/json'
                }
            });
            
            if (response.data.success) {
                showAlert(response.data.message, 'success');
                // Update profile display
                document.querySelector('.profile-name').textContent = `${data.first_name} ${data.last_name}`;
                document.querySelector('.profile-avatar').textContent = `${data.first_name[0]}${data.last_name[0]}`;
            } else {
                showAlert(response.data.message || 'An error occurred', 'danger');
            }
        } catch (error) {
            showAlert(error.response?.data?.message || 'An error occurred', 'danger');
        }
    }
</script>
<?= $this->endSection() ?>