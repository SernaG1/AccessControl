import * as XLSX from 'xlsx';

async function fetchExportData(filters) {
    const params = new URLSearchParams(filters);
    const response = await fetch('/reports/export-data?' + params.toString(), {
        headers: {
            'Accept': 'application/json',
        },
        credentials: 'same-origin'
    });
    if (!response.ok) {
        throw new Error('Error fetching export data');
    }
    return await response.json();
}

function exportToExcel(data, filename = 'logs_report.xlsx') {
    const worksheet = XLSX.utils.json_to_sheet(data);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Logs');
    XLSX.writeFile(workbook, filename);
}

export async function exportLogs(filters) {
    try {
        const data = await fetchExportData(filters);
        if (data.length === 0) {
            alert('No data to export with the current filters.');
            return;
        }
        exportToExcel(data);
    } catch (error) {
        console.error(error);
        alert('Failed to export logs.');
    }
}

window.exportLogs = exportLogs;