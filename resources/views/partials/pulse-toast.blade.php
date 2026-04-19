<!-- Toast Container -->
<div id="pulse-toast-container" style="position: fixed; top: 24px; right: 24px; z-index: 10000; pointer-events: none; display: flex; flex-direction: column; gap: 12px;"></div>

<script>
    if (typeof window.showPulseToast === 'undefined') {
        window.showPulseToast = function(message, type = 'success') {
            const container = document.getElementById('pulse-toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = `pulse-toast ${type}`;
            toast.style.cssText = `
                pointer-events: auto;
                background: var(--bg-glass, rgba(255, 255, 255, 0.8));
                backdrop-filter: blur(12px);
                border: 1px solid var(--border-glass, rgba(0,0,0,0.1));
                padding: 14px 24px;
                border-radius: 16px;
                box-shadow: 0 8px 32px rgba(0,0,0,0.12);
                color: var(--text-primary, #0f172a);
                font-size: 14px;
                font-weight: 600;
                display: flex;
                align-items: center;
                gap: 12px;
                transform: translateX(120%);
                transition: all 0.5s cubic-bezier(0.18, 0.89, 0.32, 1.28);
                border-left: 5px solid ${type === 'success' ? '#22c55e' : '#ef4444'};
            `;

            const icon = type === 'success' 
                ? '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>'
                : '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="3"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>';

            toast.innerHTML = `${icon} <span style="line-height:1.4;">${message}</span>`;
            container.appendChild(toast);

            // Animate in
            requestAnimationFrame(() => {
                toast.style.transform = 'translateX(0)';
            });

            // Remove
            setTimeout(() => {
                toast.style.transform = 'translateX(120%)';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            }, 4000);
        };
    }

    // Check for Laravel Session messages and display them
    document.addEventListener("DOMContentLoaded", () => {
        let hasDisplayed = false; // Prevent multiple toasts overlapping if both exist
        
        @if(session('success'))
            if(window.showPulseToast && !hasDisplayed) { 
                window.showPulseToast("{{ addslashes(session('success')) }}", "success");
                hasDisplayed = true;
            }
        @endif
        
        @if(session('error') || session('status'))
            if(window.showPulseToast && !hasDisplayed) { 
                window.showPulseToast("{{ addslashes(session('error') ?? session('status')) }}", "{{ session('error') ? 'error' : 'success' }}");
                hasDisplayed = true;
            }
        @endif

        @if($errors->any())
            if(window.showPulseToast && !hasDisplayed) { 
                window.showPulseToast("{{ addslashes($errors->first()) }}", "error");
                hasDisplayed = true;
            }
        @endif
    });
</script>
