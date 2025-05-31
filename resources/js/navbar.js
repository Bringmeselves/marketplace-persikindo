document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('dropdownToggle');
    const dropdown = document.getElementById('userDropdown');

    if (toggle && dropdown) {
        toggle.addEventListener('click', () => {
            dropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!dropdown.contains(e.target) && !toggle.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    }
});
