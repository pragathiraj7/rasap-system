/**
 * RASAP - Student Monitoring and Documentation System
 * Chart.js Dashboard Analytics (dashboardCharts.js)
 * Rajagiri College of Social Sciences (Autonomous), Kochi
 * --------------------------------------------------------------------------
 * Renders department-wise student distribution bar chart and document status doughnut chart.
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Check if Chart.js library is available
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js library is not loaded. Analytics charts skipped.');
        return;
    }

    // ----------------------------------------------------------------------
    // 2. Department-Wise Student Distribution Bar Chart
    // ----------------------------------------------------------------------
    const deptChartCanvas = document.getElementById('deptStudentChart');

    if (deptChartCanvas) {
        const deptCtx = deptChartCanvas.getContext('2d');
        
        // Data can be passed dynamically from PHP data attributes if set
        const deptLabels = JSON.parse(deptChartCanvas.dataset.labels || '["Computer Applications", "Social Work", "Business Admin", "Commerce", "Psychology"]');
        const deptCounts = JSON.parse(deptChartCanvas.dataset.counts || '[45, 32, 28, 40, 25]');

        new Chart(deptCtx, {
            type: 'bar',
            data: {
                labels: deptLabels,
                datasets: [{
                    label: 'Enrolled RASAP Students',
                    data: deptCounts,
                    backgroundColor: [
                        '#1B365D', // Primary Navy
                        '#4B6B94', // Slate Blue
                        '#FFB800', // Gold Accent
                        '#0D9488', // Teal
                        '#6366F1'  // Indigo
                    ],
                    borderColor: '#1B365D',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return ` ${context.raw} RASAP Students`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        title: {
                            display: true,
                            text: 'Number of Students',
                            color: '#64748B',
                            font: { weight: '600' }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // ----------------------------------------------------------------------
    // 3. Document Verification Status Breakdown (Doughnut Chart)
    // ----------------------------------------------------------------------
    const docStatusCanvas = document.getElementById('docStatusChart');

    if (docStatusCanvas) {
        const docCtx = docStatusCanvas.getContext('2d');

        const approved = parseInt(docStatusCanvas.dataset.approved || '142');
        const pending = parseInt(docStatusCanvas.dataset.pending || '24');
        const returned = parseInt(docStatusCanvas.dataset.returned || '8');
        const rejected = parseInt(docStatusCanvas.dataset.rejected || '5');

        new Chart(docCtx, {
            type: 'doughnut',
            data: {
                labels: ['Approved', 'Pending Review', 'Returned', 'Rejected'],
                datasets: [{
                    data: [approved, pending, returned, rejected],
                    backgroundColor: [
                        '#10B981', // Success Green
                        '#F59E0B', // Warning Amber
                        '#3B82F6', // Info Blue
                        '#EF4444'  // Danger Red
                    ],
                    borderWidth: 2,
                    borderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: { size: 12, weight: '600' }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    }
});
