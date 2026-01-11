// Helper functions untuk integrasi dengan backend API

// Show loading indicator
function showLoading(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.innerHTML = '<div class="loading-spinner"></div>';
    }
}

// Hide loading indicator
function hideLoading() {
    const spinners = document.querySelectorAll('.loading-spinner');
    spinners.forEach(spinner => spinner.remove());
}

// Show alert message
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
    `;
    
    const container = document.querySelector('.content') || document.body;
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto hide after 5 seconds
    setTimeout(() => alertDiv.remove(), 5000);
}

// Fetch API wrapper dengan error handling
async function apiRequest(url, options = {}) {
    try {
        const response = await fetch(url, options);
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.message || 'Terjadi kesalahan');
        }
        
        return data;
    } catch (error) {
        console.error('API Error:', error);
        showAlert(error.message, 'danger');
        throw error;
    }
}

// Format tanggal Indonesia
function formatTanggal(dateString) {
    if (!dateString) return '-';
    
    const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                   'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    
    const date = new Date(dateString);
    const tanggal = date.getDate();
    const bulanNama = bulan[date.getMonth()];
    const tahun = date.getFullYear();
    
    return `${tanggal} ${bulanNama} ${tahun}`;
}

// Format status pengajuan
function getStatusBadge(status) {
    const statusMap = {
        'pending': { class: 'warning', text: 'Menunggu' },
        'diproses': { class: 'info', text: 'Diproses' },
        'selesai': { class: 'success', text: 'Selesai' },
        'ditolak': { class: 'danger', text: 'Ditolak' }
    };
    
    const statusInfo = statusMap[status] || { class: 'secondary', text: status };
    return `<span class="badge bg-${statusInfo.class}">${statusInfo.text}</span>`;
}

// Confirm dialog
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

// Validate form
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    const inputs = form.querySelectorAll('[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });
    
    return isValid;
}

// Create table row
function createTableRow(data, columns) {
    const tr = document.createElement('tr');
    
    columns.forEach(col => {
        const td = document.createElement('td');
        
        if (typeof col === 'function') {
            td.innerHTML = col(data);
        } else {
            td.textContent = data[col] || '-';
        }
        
        tr.appendChild(td);
    });
    
    return tr;
}

// Populate table
function populateTable(tableId, data, columns) {
    const tbody = document.querySelector(`#${tableId} tbody`);
    if (!tbody) return;
    
    tbody.innerHTML = '';
    
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="' + columns.length + '" class="text-center">Tidak ada data</td></tr>';
        return;
    }
    
    data.forEach(item => {
        tbody.appendChild(createTableRow(item, columns));
    });
}

// Debounce function untuk search
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Export to CSV
function exportToCSV(data, filename = 'data.csv') {
    if (!data || data.length === 0) {
        alert('Tidak ada data untuk diekspor');
        return;
    }
    
    const headers = Object.keys(data[0]);
    const csvContent = [
        headers.join(','),
        ...data.map(row => headers.map(header => row[header] || '').join(','))
    ].join('\n');
    
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    
    link.setAttribute('href', url);
    link.setAttribute('download', filename);
    link.style.visibility = 'hidden';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Print table
function printTable(tableId) {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Print</title>');
    printWindow.document.write('<style>table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(table.outerHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}

// Logout function
function logout() {
    if (confirm('Yakin ingin keluar?')) {
        window.location.href = '../../logout.php';
    }
}
