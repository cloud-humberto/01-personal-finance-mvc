document.addEventListener('DOMContentLoaded', () => {
    // Chart.js initialization if category data exists
    if (window.chartData && window.chartData.length > 0) {
        const ctx = document.getElementById('expensesChart');
        if (ctx) {
            const labels = window.chartData.map(item => item.name);
            const data = window.chartData.map(item => parseFloat(item.total));
            const colors = window.chartData.map(item => item.color || '#6366f1');

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: colors,
                        borderWidth: 2,
                        borderColor: '#111827'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#94a3b8',
                                font: {
                                    family: "'Plus Jakarta Sans', sans-serif",
                                    size: 12
                                },
                                padding: 15
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw || 0;
                                    return ` $ ${value.toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
                                }
                            }
                        }
                    },
                    cutout: '65%'
                }
            });
        }
    }
});

// Modal helpers
function openTransactionModal() {
    const modal = document.getElementById('transactionModal');
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeTransactionModal() {
    const modal = document.getElementById('transactionModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// Close modal on backdrop click
window.addEventListener('click', (e) => {
    const modal = document.getElementById('transactionModal');
    if (modal && e.target === modal) {
        closeTransactionModal();
    }
});

// Filter categories by selected type (income/expense)
function filterCategories(type) {
    const select = document.getElementById('category_id');
    if (!select) return;

    const options = select.querySelectorAll('option');
    let firstMatch = null;

    options.forEach(option => {
        const optionType = option.getAttribute('data-type');
        if (optionType === type) {
            option.style.display = '';
            if (!firstMatch) firstMatch = option;
        } else {
            option.style.display = 'none';
        }
    });

    if (firstMatch) {
        select.value = firstMatch.value;
    }
}

// Initial category filter trigger
document.addEventListener('DOMContentLoaded', () => {
    filterCategories('expense');
});
