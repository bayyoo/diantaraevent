// Session timeout warning and auto-logout functionality
class SessionTimeout {
    constructor(timeoutMinutes = 5) {
        this.timeoutMinutes = timeoutMinutes;
        this.warningMinutes = 1; // Show warning 1 minute before timeout
        this.timeoutMs = timeoutMinutes * 60 * 1000;
        this.warningMs = (timeoutMinutes - this.warningMinutes) * 60 * 1000;
        
        this.warningTimer = null;
        this.logoutTimer = null;
        this.warningModal = null;
        
        this.init();
    }
    
    init() {
        this.createWarningModal();
        this.resetTimers();
        this.bindEvents();
    }
    
    createWarningModal() {
        // Create modal HTML
        const modalHTML = `
            <div id="session-timeout-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" style="display: none;">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                            <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Sesi Akan Berakhir</h3>
                        <div class="mt-2 px-7 py-3">
                            <p class="text-sm text-gray-500">
                                Sesi Anda akan berakhir dalam <span id="countdown-timer" class="font-bold text-red-600">60</span> detik karena tidak aktif.
                            </p>
                            <p class="text-sm text-gray-500 mt-2">
                                Klik "Perpanjang Sesi" untuk melanjutkan atau "Logout" untuk keluar sekarang.
                            </p>
                        </div>
                        <div class="items-center px-4 py-3">
                            <button id="extend-session-btn" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                Perpanjang
                            </button>
                            <button id="logout-now-btn" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                                Logout
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Add modal to body
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        this.warningModal = document.getElementById('session-timeout-modal');
        
        // Bind modal events
        document.getElementById('extend-session-btn').addEventListener('click', () => {
            this.extendSession();
        });
        
        document.getElementById('logout-now-btn').addEventListener('click', () => {
            this.logout();
        });
    }
    
    bindEvents() {
        // Reset timers on user activity
        const events = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
        
        events.forEach(event => {
            document.addEventListener(event, () => {
                this.resetTimers();
            }, true);
        });
    }
    
    resetTimers() {
        // Clear existing timers
        if (this.warningTimer) clearTimeout(this.warningTimer);
        if (this.logoutTimer) clearTimeout(this.logoutTimer);
        if (this.countdownInterval) clearInterval(this.countdownInterval);
        
        // Hide warning modal if shown
        if (this.warningModal) {
            this.warningModal.style.display = 'none';
        }
        
        // Set new timers
        this.warningTimer = setTimeout(() => {
            this.showWarning();
        }, this.warningMs);
        
        this.logoutTimer = setTimeout(() => {
            this.logout();
        }, this.timeoutMs);
    }
    
    showWarning() {
        if (this.warningModal) {
            this.warningModal.style.display = 'block';
            this.startCountdown();
        }
    }
    
    startCountdown() {
        let seconds = this.warningMinutes * 60;
        const countdownElement = document.getElementById('countdown-timer');
        
        this.countdownInterval = setInterval(() => {
            seconds--;
            if (countdownElement) {
                countdownElement.textContent = seconds;
            }
            
            if (seconds <= 0) {
                clearInterval(this.countdownInterval);
                this.logout();
            }
        }, 1000);
    }
    
    extendSession() {
        // Make AJAX request to extend session
        fetch('/extend-session', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(response => {
            if (response.ok) {
                this.resetTimers();
            }
        }).catch(error => {
            console.error('Error extending session:', error);
        });
    }
    
    logout() {
        // Submit logout form
        const logoutForm = document.createElement('form');
        logoutForm.method = 'POST';
        logoutForm.action = '/logout';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        logoutForm.appendChild(csrfToken);
        document.body.appendChild(logoutForm);
        logoutForm.submit();
    }
}

// Initialize session timeout when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize for authenticated users
    if (document.querySelector('meta[name="csrf-token"]')) {
        new SessionTimeout(5); // 5 minutes timeout
    }
});
