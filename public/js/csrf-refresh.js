/**
 * CSRF Token Auto-Refresh
 * Prevents 419 Page Expired errors by refreshing CSRF token every 4 minutes
 * (before 5-minute session timeout)
 */

// Refresh CSRF token every 4 minutes (240000 ms)
const CSRF_REFRESH_INTERVAL = 240000; // 4 minutes
const SESSION_TIMEOUT = 300000; // 5 minutes

let sessionTimer;
let warningTimer;

// Function to refresh CSRF token
async function refreshCsrfToken() {
    try {
        const response = await fetch('/csrf-token', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        if (response.ok) {
            const data = await response.json();
            
            // Update all CSRF token inputs
            document.querySelectorAll('input[name="_token"]').forEach(input => {
                input.value = data.csrf_token;
            });
            
            // Update meta tag
            const metaTag = document.querySelector('meta[name="csrf-token"]');
            if (metaTag) {
                metaTag.setAttribute('content', data.csrf_token);
            }
            
            console.log('CSRF token refreshed successfully');
        }
    } catch (error) {
        console.error('Failed to refresh CSRF token:', error);
    }
}

// Function to show session timeout warning
function showSessionWarning() {
    const warningDiv = document.createElement('div');
    warningDiv.id = 'session-warning';
    warningDiv.className = 'fixed top-4 right-4 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg shadow-lg z-50 max-w-md';
    warningDiv.innerHTML = `
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700 font-medium">
                    Sesi Anda akan berakhir dalam 1 menit!
                </p>
                <p class="text-xs text-yellow-600 mt-1">
                    Klik tombol di bawah untuk memperpanjang sesi.
                </p>
                <button onclick="extendSession()" class="mt-2 bg-yellow-400 hover:bg-yellow-500 text-yellow-900 text-xs font-medium px-3 py-1 rounded transition-colors">
                    Perpanjang Sesi
                </button>
            </div>
            <button onclick="closeWarning()" class="ml-auto flex-shrink-0 text-yellow-400 hover:text-yellow-600">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    `;
    document.body.appendChild(warningDiv);
}

// Function to close warning
function closeWarning() {
    const warning = document.getElementById('session-warning');
    if (warning) {
        warning.remove();
    }
}

// Function to extend session
async function extendSession() {
    await refreshCsrfToken();
    closeWarning();
    resetSessionTimers();
    
    // Show success message
    const successDiv = document.createElement('div');
    successDiv.className = 'fixed top-4 right-4 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg shadow-lg z-50';
    successDiv.innerHTML = `
        <div class="flex items-center">
            <svg class="h-5 w-5 text-green-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm text-green-700 font-medium">Sesi berhasil diperpanjang!</p>
        </div>
    `;
    document.body.appendChild(successDiv);
    
    setTimeout(() => successDiv.remove(), 3000);
}

// Function to reset session timers
function resetSessionTimers() {
    // Clear existing timers
    if (sessionTimer) clearTimeout(sessionTimer);
    if (warningTimer) clearTimeout(warningTimer);
    
    // Show warning 1 minute before timeout (4 minutes)
    warningTimer = setTimeout(showSessionWarning, SESSION_TIMEOUT - 60000);
    
    // Auto logout after 5 minutes
    sessionTimer = setTimeout(() => {
        window.location.href = '/login?session_expired=1';
    }, SESSION_TIMEOUT);
}

// Function to handle user activity
function handleUserActivity() {
    resetSessionTimers();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Only run on authenticated pages
    if (document.querySelector('meta[name="csrf-token"]')) {
        // Refresh CSRF token every 4 minutes
        setInterval(refreshCsrfToken, CSRF_REFRESH_INTERVAL);
        
        // Initialize session timers
        resetSessionTimers();
        
        // Reset timers on user activity (for authenticated users)
        const activityEvents = ['mousedown', 'keydown', 'scroll', 'touchstart'];
        let activityTimeout;
        
        activityEvents.forEach(event => {
            document.addEventListener(event, () => {
                clearTimeout(activityTimeout);
                activityTimeout = setTimeout(handleUserActivity, 1000); // Debounce 1 second
            }, { passive: true });
        });
    }
});

// Handle form submissions - refresh token before submit
document.addEventListener('submit', async function(e) {
    const form = e.target;
    
    // Skip if form has data-no-csrf attribute
    if (form.hasAttribute('data-no-csrf')) {
        return;
    }
    
    // Check if form has CSRF token
    const csrfInput = form.querySelector('input[name="_token"]');
    if (csrfInput) {
        e.preventDefault();
        
        // Refresh token before submission
        await refreshCsrfToken();
        
        // Submit form after token refresh
        setTimeout(() => form.submit(), 100);
    }
});

// Expose functions globally
window.extendSession = extendSession;
window.closeWarning = closeWarning;
