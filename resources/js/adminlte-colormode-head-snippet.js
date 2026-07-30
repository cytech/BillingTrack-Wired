// supposed to be inserted in <head> of master.blade.
//(() => {
'use strict';
const STORAGE_KEY = 'lte-theme';
let stored = 'light';
// override and force light on master
localStorage.setItem(STORAGE_KEY, stored)
try { stored = localStorage.getItem(STORAGE_KEY); } catch {}
const prefersDark = globalThis.matchMedia('(prefers-color-scheme: dark)').matches;
let resolved = 'light';
if (stored === 'dark' || stored === 'light') {
    resolved = stored;
} else if (prefersDark) {
    resolved = 'dark';
}
document.documentElement.setAttribute('data-bs-theme', resolved);
document.documentElement.style.colorScheme = resolved;
//})();
