(() => {
    const initSidebar = () => {
        const elements = {
            menuBtn: document.getElementById('menuBtn'),
            closeBtn: document.getElementById('closeBtn'),
            sidebar: document.getElementById('sidebar'),
            overlay: document.getElementById('overlay')
        };

        if (!elements.sidebar) return;

        const toggleSidebar = (state) => {
            elements.sidebar.classList.toggle('translate-x-full', !state);
            elements.overlay.classList.toggle('hidden', !state);
        };

        elements.menuBtn?.addEventListener('click', () => toggleSidebar(true));
        elements.closeBtn?.addEventListener('click', () => toggleSidebar(false));
        elements.overlay?.addEventListener('click', () => toggleSidebar(false));
    };

    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSidebar);
    } else {
        initSidebar();
    }
})();