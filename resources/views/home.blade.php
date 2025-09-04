@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="fas fa-tachometer-alt text-primary me-2"></i>
                    Dashboard
                </h2>
                <div class="text-muted mt-1">
                    Welcome to your cashier management system, {{ auth()->user()->nama }}!
                    <span class="badge {{ auth()->user()->role === 'admin' ? 'bg-red' : 'bg-blue' }} ms-2">
                        {{ auth()->user()->getRoleDisplayName() }}
                    </span>
                </div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <span class="d-none d-sm-inline">
                        <div class="text-muted text-end">
                            <div class="text-h3 text-primary" id="currentTime"></div>
                            <div class="text-muted" id="currentDate"></div>
                        </div>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Stats Cards -->
        <div class="row row-deck row-cards mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card card-cashier">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Today's Sales</div>
                            <div class="ms-auto">
                                <i class="fas fa-cash-register text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="h1 mb-3" id="todaySales">Rp 0</div>
                        <div class="d-flex mb-2">
                            <div>vs yesterday</div>
                            <div class="ms-auto">
                                <span class="text-green d-inline-flex align-items-center lh-1" id="salesGrowth">
                                    +0% <i class="fas fa-chart-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-cashier">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Today's Transactions</div>
                            <div class="ms-auto">
                                <i class="fas fa-receipt text-success fa-2x"></i>
                            </div>
                        </div>
                        <div class="h1 mb-3" id="todayTransactions">0</div>
                        <div class="d-flex mb-2">
                            <div>Average per hour</div>
                            <div class="ms-auto">
                                <span class="text-blue d-inline-flex align-items-center lh-1" id="avgPerHour">
                                    0 <i class="fas fa-clock"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-cashier">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Active Products</div>
                            <div class="ms-auto">
                                <i class="fas fa-box text-info fa-2x"></i>
                            </div>
                        </div>
                        <div class="h1 mb-3" id="totalProducts">0</div>
                        <div class="d-flex mb-2">
                            <div>Low stock items</div>
                            <div class="ms-auto">
                                <span class="text-warning d-inline-flex align-items-center lh-1" id="lowStockCount">
                                    0 <i class="fas fa-exclamation-triangle"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-cashier">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Pending Sales</div>
                            <div class="ms-auto">
                                <i class="fas fa-clock text-warning fa-2x"></i>
                            </div>
                        </div>
                        <div class="h1 mb-3" id="pendingSales">0</div>
                        <div class="d-flex mb-2">
                            <div>Need attention</div>
                            <div class="ms-auto">
                                <span class="text-danger d-inline-flex align-items-center lh-1" id="urgentSales">
                                    0 <i class="fas fa-exclamation-circle"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-cashier">
                    <div class="card-header bg-gradient-primary text-white">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-bolt me-2"></i>Quick Actions
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('cashier') }}" class="btn btn-primary btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4">
                                    <i class="fas fa-cash-register fa-3x mb-3"></i>
                                    <span class="h5 mb-2">Open Cashier</span>
                                    <small class="text-muted">Start new transaction</small>
                                </a>
                            </div>
                            
                            @if(auth()->user()->isAdmin())
                            <!-- Admin only quick actions -->
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('products.index') }}" class="btn btn-success btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4">
                                    <i class="fas fa-box fa-3x mb-3"></i>
                                    <span class="h5 mb-2">Manage Products</span>
                                    <small class="text-muted">Add/edit inventory</small>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('sale-details') }}" class="btn btn-info btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4">
                                    <i class="fas fa-chart-line fa-3x mb-3"></i>
                                    <span class="h5 mb-2">Sales Report</span>
                                    <small class="text-muted">View transactions</small>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('users.index') }}" class="btn btn-warning btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4">
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <span class="h5 mb-2">User Management</span>
                                    <small class="text-muted">Manage users & roles</small>
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Analytics -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card card-cashier">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-area me-2"></i>Sales Trend (Last 7 Days)
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="salesChart" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card card-cashier">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-pie me-2"></i>Payment Methods
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="paymentChart" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card card-cashier">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-clock me-2"></i>Recent Transactions
                        </h3>
                        <div class="card-actions">
                            <a href="{{ route('sale-details') }}" class="btn btn-primary btn-sm">
                                View All
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="recentTransactions">
                            <div class="text-center py-4">
                                <div class="empty">
                                    <div class="empty-img">
                                        <i class="fas fa-clock fa-3x text-muted"></i>
                                    </div>
                                    <p class="empty-title">No recent transactions</p>
                                    <p class="empty-subtitle text-muted">
                                        Start making sales to see activity here
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card card-cashier">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bell me-2"></i>Alerts & Notifications
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="alertsList">
                            <div class="text-center py-4">
                                <div class="empty">
                                    <div class="empty-img">
                                        <i class="fas fa-bell fa-3x text-muted"></i>
                                    </div>
                                    <p class="empty-title">No alerts</p>
                                    <p class="empty-subtitle text-muted">
                                        Everything is running smoothly
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update current time and date
    updateDateTime();
    setInterval(updateDateTime, 1000);
    
    // Load dashboard data
    loadDashboardData();
    
    // Initialize charts
    initializeCharts();
});

function updateDateTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('id-ID', { 
        hour: '2-digit', 
        minute: '2-digit', 
        second: '2-digit' 
    });
    const dateString = now.toLocaleDateString('id-ID', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
    
    document.getElementById('currentTime').textContent = timeString;
    document.getElementById('currentDate').textContent = dateString;
}

async function loadDashboardData() {
    try {
        const response = await fetch('/api/dashboard/stats', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            updateDashboardStats(data);
        } else {
            // Use mock data for demo
            updateDashboardStats(getMockData());
        }
    } catch (error) {
        console.error('Error loading dashboard data:', error);
        updateDashboardStats(getMockData());
    }
}

function getMockData() {
    return {
        todaySales: 2500000,
        salesGrowth: 7.5,
        todayTransactions: 23,
        avgPerHour: 2.3,
        totalProducts: 1995, // Updated to match actual data
        lowStockCount: 809, // Updated to match actual data
        pendingSales: 17, // Updated to match actual data
        urgentSales: 1,
        recentTransactions: [
            {
                invoice: 'INV-20241201-001',
                customer: 'John Doe',
                amount: 150000,
                status: 'completed',
                time: '2 min ago'
            },
            {
                invoice: 'INV-20241201-002',
                customer: 'Jane Smith',
                amount: 75000,
                status: 'completed',
                time: '15 min ago'
            },
            {
                invoice: 'INV-20241201-003',
                customer: 'Bob Johnson',
                amount: 200000,
                status: 'completed',
                time: '1 hour ago'
            }
        ],
        alerts: [
            {
                type: 'warning',
                title: 'Low stock alert',
                message: '809 products have low stock (< 5 items)',
                time: '30 min ago'
            },
            {
                type: 'warning',
                title: 'Pending sales',
                message: '17 sales are pending payment',
                time: '15 min ago'
            },
            {
                type: 'success',
                title: 'New sale completed',
                message: 'Invoice INV-20241201-001',
                time: '2 min ago'
            }
        ]
    };
}

function updateDashboardStats(data) {
    // Update stat cards
    document.getElementById('todaySales').textContent = `Rp ${data.todaySales.toLocaleString()}`;
    document.getElementById('salesGrowth').innerHTML = `+${data.salesGrowth}% <i class="fas fa-chart-line"></i>`;
    document.getElementById('todayTransactions').textContent = data.todayTransactions;
    document.getElementById('avgPerHour').innerHTML = `${data.avgPerHour} <i class="fas fa-clock"></i>`;
    document.getElementById('totalProducts').textContent = data.totalProducts;
    document.getElementById('lowStockCount').innerHTML = `${data.lowStockCount} <i class="fas fa-exclamation-triangle"></i>`;
    document.getElementById('pendingSales').textContent = data.pendingSales;
    document.getElementById('urgentSales').innerHTML = `${data.urgentSales} <i class="fas fa-exclamation-circle"></i>`;
    
    // Update recent transactions
    updateRecentTransactions(data.recentTransactions);
    
    // Update alerts
    updateAlerts(data.alerts);
}

function updateRecentTransactions(transactions) {
    const container = document.getElementById('recentTransactions');
    
    if (transactions.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4">
                <div class="empty">
                    <div class="empty-img">
                        <i class="fas fa-clock fa-3x text-muted"></i>
                    </div>
                    <p class="empty-title">No recent transactions</p>
                    <p class="empty-subtitle text-muted">
                        Start making sales to see activity here
                    </p>
                </div>
            </div>
        `;
        return;
    }
    
    let html = `
        <div class="table-responsive">
            <table class="table table-vcenter">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    transactions.forEach(transaction => {
        const statusClass = transaction.status === 'completed' ? 'success' : 'warning';
        const statusText = transaction.status === 'completed' ? 'Completed' : 'Pending';
        
        html += `
            <tr>
                <td class="h6">${transaction.invoice}</td>
                <td>${transaction.customer}</td>
                <td class="text-success">Rp ${transaction.amount.toLocaleString()}</td>
                <td><span class="badge bg-${statusClass}">${statusText}</span></td>
                <td class="text-muted">${transaction.time}</td>
            </tr>
        `;
    });
    
    html += '</tbody></table></div>';
    container.innerHTML = html;
}

function updateAlerts(alerts) {
    const container = document.getElementById('alertsList');
    
    if (alerts.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4">
                <div class="empty">
                    <div class="empty-img">
                        <i class="fas fa-bell fa-3x text-muted"></i>
                    </div>
                    <p class="empty-title">No alerts</p>
                    <p class="empty-subtitle text-muted">
                        Everything is running smoothly
                    </p>
                </div>
            </div>
        `;
        return;
    }
    
    let html = '<div class="list-group list-group-flush">';
    
    alerts.forEach(alert => {
        const alertClass = alert.type === 'warning' ? 'warning' : 
                          alert.type === 'success' ? 'success' : 
                          alert.type === 'danger' ? 'danger' : 'info';
        
        html += `
            <div class="list-group-item">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="status-dot status-dot-dot bg-${alertClass}"></span>
                    </div>
                    <div class="col text-truncate">
                        <span class="text-reset d-block">${alert.title}</span>
                        <div class="d-block text-muted text-truncate mt-n1">
                            ${alert.message}
                        </div>
                        <small class="text-muted">${alert.time}</small>
                    </div>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    container.innerHTML = html;
}

function initializeCharts() {
    // Sales Trend Chart
    const salesChartOptions = {
        series: [{
            name: 'Sales',
            data: [1800000, 2200000, 2500000, 2100000, 2800000, 3200000, 2500000]
        }],
        chart: {
            type: 'area',
            height: 300,
            toolbar: {
                show: false
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        colors: ['#667eea'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.2,
                stops: [0, 90, 100]
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        xaxis: {
            categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            labels: {
                style: {
                    colors: '#6c757d'
                }
            }
        },
        yaxis: {
            labels: {
                formatter: function(value) {
                    return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                },
                style: {
                    colors: '#6c757d'
                }
            }
        },
        tooltip: {
            y: {
                formatter: function(value) {
                    return 'Rp ' + value.toLocaleString();
                }
            }
        }
    };
    
    const salesChart = new ApexCharts(document.querySelector("#salesChart"), salesChartOptions);
    salesChart.render();
    
    // Payment Methods Chart
    const paymentChartOptions = {
        series: [45, 30, 25],
        chart: {
            type: 'donut',
            height: 300
        },
        labels: ['Cash', 'Transfer', 'Debit Card'],
        colors: ['#28a745', '#17a2b8', '#ffc107'],
        plotOptions: {
            pie: {
                donut: {
                    size: '60%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
                            formatter: function (w) {
                                return '100%';
                            }
                        }
                    }
                }
            }
        },
        legend: {
            position: 'bottom'
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };
    
    const paymentChart = new ApexCharts(document.querySelector("#paymentChart"), paymentChartOptions);
    paymentChart.render();
}
</script>

<style>
/* Enhanced dashboard styles */
.card-cashier {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.card-cashier:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.12);
    border-color: rgba(0, 0, 0, 0.1);
}

.card-cashier .card-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 1rem 1rem 0 0 !important;
    padding: 1.5rem;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(248, 249, 250, 0.9) 100%);
}

.card-cashier .card-body {
    padding: 2rem;
    background: #ffffff;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Enhanced responsive design */
@media (max-width: 768px) {
    .card-cashier .card-body {
        padding: 1.25rem;
    }
    
    .card-cashier .card-header {
        padding: 1.25rem;
    }
    
    .btn-lg {
        padding: 0.75rem 1.25rem;
        font-size: 1rem;
        min-height: 48px;
    }
}

/* Status dot styling */
.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
}

.status-dot-dot {
    width: 12px;
    height: 12px;
}

/* Enhanced animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.empty {
    padding: 3rem 0;
    animation: fadeInUp 0.6s ease-out;
}

.empty-img {
    height: 8rem;
    margin-bottom: 2rem;
    opacity: 0.4;
    transition: all 0.3s ease;
}

.empty:hover .empty-img {
    opacity: 0.6;
    transform: scale(1.05);
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #495057;
}

.empty-subtitle {
    font-size: 1rem;
    margin-bottom: 0;
    color: #6c757d;
}
</style>
@endsection


