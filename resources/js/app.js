import './bootstrap';
import { exportLogs } from './exportLogs';

document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('exportBtn');
    if (btn) {
        btn.addEventListener('click', () => {
            const filters = {
                user_type: document.getElementById('user_type').value,
                numero_documento: document.getElementById('numero_documento').value,
                nombre: document.getElementById('nombre').value,
                fecha_desde: document.getElementById('fecha_desde').value,
                fecha_hasta: document.getElementById('fecha_hasta').value,
                hora_desde: document.getElementById('hora_desde').value,
                hora_hasta: document.getElementById('hora_hasta').value,
            };
            exportLogs(filters);
        });
    }

    // Visitor form validation
    const visitorForm = document.querySelector('form[action="/incomes/store"]') || document.querySelector('form[action="{{ route(\'incomes.store\') }}"]');
    if (visitorForm) {
        visitorForm.addEventListener('submit', function handler(event) {
            event.preventDefault();
            const numeroDocumento = visitorForm.querySelector('input[name="numero_documento"]').value.trim();
            if (!numeroDocumento) {
                alert('Por favor ingresa el número de documento.');
                return;
            }
            fetch(`/api/check-document-exists?numero_documento=${encodeURIComponent(numeroDocumento)}&type=visitor`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        alert('El número de documento ya existe para un visitante.');
                    } else {
                        visitorForm.removeEventListener('submit', handler);
                        visitorForm.submit();
                    }
                })
                .catch(() => {
                    alert('Error al verificar el número de documento.');
                });
        });
    }

    // Employee form validation
    const employeeForm = document.querySelector('form[action="/employee/store"]') || document.querySelector('form[action="{{ route(\'employee.store\') }}"]');
    if (employeeForm) {
        employeeForm.addEventListener('submit', function handler(event) {
            event.preventDefault();
            const numeroDocumento = employeeForm.querySelector('input[name="numero_documento"]').value.trim();
            if (!numeroDocumento) {
                alert('Por favor ingresa el número de documento.');
                return;
            }
            fetch(`/api/check-document-exists?numero_documento=${encodeURIComponent(numeroDocumento)}&type=employee`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        alert('El número de documento ya existe para un empleado.');
                    } else {
                        employeeForm.removeEventListener('submit', handler);
                        employeeForm.submit();
                    }
                })
                .catch(() => {
                    alert('Error al verificar el número de documento.');
                });
        });
    }
});
