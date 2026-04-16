// Pegawai Bon Create Page JavaScript

let itemCount = 1;

function addItem() {
    itemCount++;
    const itemList = document.getElementById('itemList');
    
    const newItem = document.createElement('div');
    newItem.className = 'item-row border rounded-lg p-4 bg-gray-50';
    newItem.style.animation = 'slideDown 0.3s ease';
    
    newItem.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Barang</label>
                <select name="barang_id[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required onchange="updateStockInfo(this)">
                    <option value="">Pilih Barang</option>
                    @foreach($barangs as $barang)
                        <option value="{{ $barang->id }}" data-stok="{{ $barang->stok }}" data-satuan="{{ $barang->satuan }}">
                            {{ $barang->nama_barang }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                <input type="number" name="jumlah[]" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="0" required onchange="validateStock(this)">
            </div>
            <div class="flex items-end gap-2">
                <button type="button" onclick="removeItem(this)" class="bg-red-600 text-white px-3 py-2 rounded hover:bg-red-700 transition">
                    Hapus
                </button>
                <div class="mt-2 text-sm text-gray-600 stok-info"></div>
            </div>
        </div>
    `;
    
    itemList.appendChild(newItem);
    
    // Add event listeners to new elements
    const newSelect = newItem.querySelector('select[name="barang_id[]"]');
    const newInput = newItem.querySelector('input[name="jumlah[]"]');
    
    newSelect.addEventListener('change', function() {
        updateStockInfo(this);
    });
    
    newInput.addEventListener('input', function() {
        validateStock(this);
    });
}

function removeItem(button) {
    const itemRows = document.querySelectorAll('.item-row');
    if (itemRows.length > 1) {
        const itemRow = button.closest('.item-row');
        itemRow.style.animation = 'slideUp 0.3s ease';
        setTimeout(() => {
            itemRow.remove();
        }, 300);
    } else {
        showNotification('Minimal harus ada satu barang!', 'warning');
    }
}

function updateStockInfo(select) {
    const selectedOption = select.selectedOptions[0];
    const stokInfo = select.closest('.item-row').querySelector('.stok-info');
    
    if (selectedOption.value) {
        const stok = selectedOption.dataset.stok;
        const satuan = selectedOption.dataset.satuan;
        stokInfo.textContent = `Stok tersedia: ${stok} ${satuan}`;
        stokInfo.className = 'mt-2 text-sm text-green-600 stok-info';
    } else {
        stokInfo.textContent = '';
    }
}

function validateStock(input) {
    const itemRow = input.closest('.item-row');
    const select = itemRow.querySelector('select[name="barang_id[]"]');
    const stokInfo = itemRow.querySelector('.stok-info');
    
    if (select.value && input.value) {
        const selectedOption = select.selectedOptions[0];
        const stok = parseInt(selectedOption.dataset.stok);
        const jumlah = parseInt(input.value);
        
        if (jumlah > stok) {
            input.classList.add('border-red-500');
            input.classList.remove('border-gray-300');
            stokInfo.textContent = `⚠️ Jumlah melebihi stok (${stok} ${selectedOption.dataset.satuan})`;
            stokInfo.className = 'mt-2 text-sm text-red-600 stok-info';
            return false;
        } else {
            input.classList.remove('border-red-500');
            input.classList.add('border-gray-300');
            updateStockInfo(select);
            return true;
        }
    }
    
    return true;
}

function validateForm() {
    const barangSelects = document.querySelectorAll('select[name="barang_id[]"]');
    const jumlahInputs = document.querySelectorAll('input[name="jumlah[]"]');
    
    let hasValidItem = false;
    let hasDuplicate = false;
    const selectedBarangs = new Set();
    
    for (let i = 0; i < barangSelects.length; i++) {
        const barangId = barangSelects[i].value;
        const jumlah = parseInt(jumlahInputs[i].value) || 0;
        
        if (barangId && jumlah > 0) {
            if (selectedBarangs.has(barangId)) {
                hasDuplicate = true;
                break;
            }
            selectedBarangs.add(barangId);
            
            if (!validateStock(jumlahInputs[i])) {
                return false;
            }
            
            hasValidItem = true;
        }
    }
    
    if (hasDuplicate) {
        showNotification('Tidak boleh memilih barang yang sama!', 'error');
        return false;
    }
    
    if (!hasValidItem) {
        showNotification('Minimal harus ada satu barang yang dipilih dengan jumlah yang valid!', 'error');
        return false;
    }
    
    return true;
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg z-50 notification-${type}`;
    
    switch(type) {
        case 'error':
            notification.classList.add('bg-red-100', 'border', 'border-red-400', 'text-red-700');
            break;
        case 'warning':
            notification.classList.add('bg-yellow-100', 'border', 'border-yellow-400', 'text-yellow-700');
            break;
        case 'success':
            notification.classList.add('bg-green-100', 'border', 'border-green-400', 'text-green-700');
            break;
        default:
            notification.classList.add('bg-blue-100', 'border', 'border-blue-400', 'text-blue-700');
    }
    
    notification.textContent = message;
    notification.style.animation = 'slideInRight 0.3s ease';
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Initialize form
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('bonForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
            }
        });
    }
    
    // Add event listeners to existing elements
    document.querySelectorAll('select[name="barang_id[]"]').forEach(select => {
        select.addEventListener('change', function() {
            updateStockInfo(this);
        });
    });
    
    document.querySelectorAll('input[name="jumlah[]"]').forEach(input => {
        input.addEventListener('input', function() {
            validateStock(this);
        });
    });
    
    // Initialize stock info for existing items
    document.querySelectorAll('select[name="barang_id[]"]').forEach(select => {
        if (select.value) {
            updateStockInfo(select);
        }
    });
});

// Export functions for global access
window.PegawaiBonCreate = {
    addItem,
    removeItem,
    updateStockInfo,
    validateStock,
    validateForm,
    showNotification
};
