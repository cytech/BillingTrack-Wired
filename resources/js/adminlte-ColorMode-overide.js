const DATA_KEY = 'lte.color-mode';
const EVENT_KEY = `.${DATA_KEY}`;
const EVENT_CHANGED = `changed${EVENT_KEY}`;
const STORAGE_KEY = 'lte-theme';
const SELECTOR_TOGGLE = '[data-bs-theme-value]';
const SELECTOR_ICON = '[data-lte-theme-icon]';
class ColorMode {
    getStoredTheme() {
        try {
            const stored = localStorage.getItem(STORAGE_KEY);
            return stored && ['light', 'dark', 'auto'].includes(stored) ? stored : null;
        }
        catch {
            return null;
        }
    }
    getPreferredTheme() {
        const stored = this.getStoredTheme();
        if (stored) {
            return stored;
        }
        return this._prefersDark() ? 'dark' : 'light';
    }
    resolveTheme(theme) {
        if (theme === 'auto') {
            return this._prefersDark() ? 'dark' : 'light';
        }
        return theme;
    }
    setTheme(theme) {
        try {
            localStorage.setItem(STORAGE_KEY, theme);
        }
        catch {
        }
        this._applyTheme(theme);
        this._showActiveTheme(theme);
        document.dispatchEvent(new CustomEvent(EVENT_CHANGED, {
            detail: { theme, resolved: this.resolveTheme(theme) }
        }));
    }
    _applyTheme(theme) {
        const resolved = this.resolveTheme(theme);
        //document.documentElement.setAttribute('data-bs-theme', resolved);
        document.documentElement.style.colorScheme = resolved;
    }
    _prefersDark() {
        return globalThis.matchMedia('(prefers-color-scheme: dark)').matches;
    }
    _showActiveTheme(theme) {
        document.querySelectorAll(SELECTOR_TOGGLE).forEach(toggle => {
            const isActive = toggle.getAttribute('data-bs-theme-value') === theme;
            toggle.classList.toggle('active', isActive);
            toggle.setAttribute('aria-pressed', String(isActive));
            toggle.querySelector('.bi-check-lg')?.classList.toggle('d-none', !isActive);
        });
        document.querySelectorAll(SELECTOR_ICON).forEach(icon => {
            icon.classList.toggle('d-none', icon.dataset.lteThemeIcon !== theme);
        });
    }
    init() {
        const theme = this.getPreferredTheme();
        //this._applyTheme(theme);
        this._showActiveTheme(theme);
    }
}

export {ColorMode};


////

// onDOMContentLoaded(() => {
//     const colorMode = new ColorMode();
//     colorMode.init();
//     globalThis.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
//         const stored = colorMode.getStoredTheme();
//         if (!stored || stored === 'auto') {
//             colorMode._applyTheme('auto');
//             colorMode._showActiveTheme(stored ?? 'auto');
//         }
//     }, { signal: getLifecycleSignal() });
// });
