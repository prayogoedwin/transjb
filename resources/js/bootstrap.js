import axios from "axios";
import Alpine from "alpinejs";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Add Authorization header with Sanctum token on every request
window.axios.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem("api_token");
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Handle 401 responses - token expired or invalid
window.axios.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            // Clear invalid token
            localStorage.removeItem("api_token");
            console.warn("Token expired or invalid. Redirecting to login.");
            window.location.href = "/login";
        }
        return Promise.reject(error);
    }
);

// Auto-generate token for web-authenticated users on page load
async function initializeApiToken() {
    const existingToken = localStorage.getItem("api_token");
    if (!existingToken) {
        try {
            const response = await window.axios.post("/api/auth/token");
            if (response.data?.token) {
                localStorage.setItem("api_token", response.data.token);
            }
        } catch (error) {
            // User not authenticated, no token available
            console.debug("No API token available", error.response?.status);
        }
    }
}

// Initialize token when DOM is ready
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initializeApiToken);
} else {
    initializeApiToken();
}

window.Alpine = Alpine;

Alpine.start();
