<!-- Preloader Component -->
<style>
    .preloader-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        backdrop-filter: blur(4px);
    }

    .preloader {
        width: 50px;
        height: 50px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #6e8efb;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .preloader-text {
        position: absolute;
        top: calc(50% + 40px);
        color: #2c3e50;
        font-size: 0.9rem;
        font-weight: 500;
    }
</style>

<div id="preloaderContainer" class="preloader-container">
    <div class="preloader"></div>
    <div class="preloader-text">Loading...</div>
</div>

<script>
    // Preloader functions
    const preloader = {
        show: function(message = 'Loading...') {
            const container = document.getElementById('preloaderContainer');
            const text = container.querySelector('.preloader-text');
            text.textContent = message;
            container.style.display = 'flex';
        },
        hide: function() {
            const container = document.getElementById('preloaderContainer');
            container.style.display = 'none';
        }
    };

    // Axios interceptor to automatically show/hide preloader
    axios.interceptors.request.use(function (config) {
        preloader.show();
        return config;
    }, function (error) {
        preloader.hide();
        return Promise.reject(error);
    });

    axios.interceptors.response.use(function (response) {
        preloader.hide();
        return response;
    }, function (error) {
        preloader.hide();
        return Promise.reject(error);
    });
</script> 
