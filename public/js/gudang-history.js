/**
 * JavaScript untuk History Gudang
 * Dipisahkan dari view untuk mengurangi kode di blade template
 */

// Handle bulan select change
document.addEventListener('DOMContentLoaded', function() {
    const bulanSelect = document.getElementById('bulanSelect');

    if (bulanSelect) {
        bulanSelect.addEventListener('change', function() {
            filterByCategory('bulan');
        });
    }

    // Default load
    updateStatistics('all');
    updateDivisiStatistics();
});

// Update statistics (atas)
function updateStatistics(category) {
    let rows;
    let visibleRows = [];

    if (category === 'semua') {
        rows = document.querySelectorAll('.semua-bon-row');
        visibleRows = Array.from(rows); // Show all rows for 'semua' category
    } else {
        rows = document.querySelectorAll('.history-row');
        rows.forEach(row => {
            let show = false;

            switch(category) {
                case 'all':
                    show = true;
                    break;
                case 'disetujui':
                    show = row.dataset.status === 'disetujui';
                    break;
                case 'bulan':
                    const selectedBulan = document.getElementById('bulanSelect').value;
                    show = selectedBulan === '' || row.dataset.bulan === selectedBulan;
                    break;
            }

            if (show) {
                visibleRows.push(row);
            }
        });
    }

    const totalDisetujui = visibleRows.filter(row => row.dataset.status === 'disetujui').length;
    const totalBon = visibleRows.length;

    document.querySelector('.stat-disetujui').textContent = totalDisetujui;
    document.querySelector('.stat-total').textContent = totalBon;
}

// Update statistik per divisi
function updateDivisiStatistics() {
    const divisiGroups = document.querySelectorAll('.divisi-group');

    divisiGroups.forEach(group => {
        const rows = group.querySelectorAll('.history-row');

        let disetujui = 0;

        rows.forEach(row => {
            // Check if row is visible (same logic as filterByCategory)
            let isVisible = false;
            const activeTab = document.querySelector('.category-tab.border-blue-500');
            const category = activeTab ? activeTab.dataset.category : 'all';

            switch(category) {
                case 'all':
                    isVisible = true;
                    break;
                case 'disetujui':
                    isVisible = row.dataset.status === 'disetujui';
                    break;
                case 'bulan':
                    const selectedBulan = document.getElementById('bulanSelect').value;
                    isVisible = selectedBulan === '' || row.dataset.bulan === selectedBulan;
                    break;
            }

            if (isVisible) {
                if (row.dataset.status === 'disetujui') disetujui++;
            }
        });

        group.querySelector('.stat-divisi-disetujui').textContent = `Disetujui: ${disetujui}`;
    });
}

// SINGLE function filter (tidak dobel)
function filterByCategory(category) {
    // Update tab styles
    document.querySelectorAll('.category-tab').forEach(tab => {
        if (tab.dataset.category === category) {
            tab.classList.remove('border-transparent', 'text-gray-500');
            tab.classList.add('border-blue-500', 'text-blue-600');
        } else {
            tab.classList.remove('border-blue-500', 'text-blue-600');
            tab.classList.add('border-transparent', 'text-gray-500');
        }
    });

    // Show/hide bulan filter
    const bulanFilter = document.getElementById('bulanFilter');
    if (category === 'bulan') {
        bulanFilter.classList.remove('hidden');
    } else {
        bulanFilter.classList.add('hidden');
    }

    // Show/hide appropriate sections
    const historyList = document.querySelector('.bg-white.shadow.rounded-lg.overflow-hidden:not(#semuaBonList)');
    const semuaBonList = document.getElementById('semuaBonList');

    if (category === 'semua') {
        // Hide regular history list, show semua bon list
        if (historyList) historyList.classList.add('hidden');
        semuaBonList.classList.remove('hidden');
    } else {
        // Show regular history list, hide semua bon list
        if (historyList) historyList.classList.remove('hidden');
        semuaBonList.classList.add('hidden');

        // Filter rows for regular categories
        const rows = document.querySelectorAll('.history-row');

        rows.forEach(row => {
            let show = false;

            switch(category) {
                case 'all':
                    show = true;
                    break;
                case 'disetujui':
                    show = row.dataset.status === 'disetujui';
                    break;
                case 'bulan':
                    const selectedBulan = document.getElementById('bulanSelect').value;
                    show = selectedBulan === '' || row.dataset.bulan === selectedBulan;
                    break;
            }

            row.style.display = show ? '' : 'none';
        });

        // Update divisi statistics for regular categories
        updateDivisiStatistics();
    }

    // Update semua statistik
    updateStatistics(category);
}

