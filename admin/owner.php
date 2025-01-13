<?php include_once('header.php'); ?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<div class="dashboard-wrapper">
    <main class="animate__animated animate__fadeIn">
        <div class="container-fluid p-4">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 text-gray-800">
                        <i class="fas fa-users-cog me-2"></i>Owner Management
                    </h1>
                    <p class="text-muted">Manage and monitor all property owners</p>
                </div>
                <a href="OwnerRegistration.php" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Add New Owner
                </a>
            </div>

            <!-- Search Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form id="search-form" class="row g-3">
                        <div class="col-md-5">
                            <div class="search-group">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" id="search-name" class="form-control search-input" 
                                       placeholder="Search by owner name...">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="search-group">
                                <i class="fas fa-building search-icon"></i>
                                <input type="text" id="search-unit" class="form-control search-input" 
                                       placeholder="Search by unit number...">
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>

            <!-- Data Table Card -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="owner-table" class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col" width="5%">#</th>
                                    <th scope="col" width="25%">Owner Details</th>
                                    <th scope="col" width="20%">Contact</th>
                                    <th scope="col" width="15%">Unit</th>
                                    <th scope="col" width="15%">Status</th>
                                    <th scope="col" width="20%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be inserted here by JavaScript -->
                            </tbody>
                        </table>

                        <!-- Loading Spinner -->
                        <div id="loading-spinner" class="text-center py-4 d-none">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div id="empty-state" class="text-center py-5 d-none">
                            <img src="../assets/images/empty-state.svg" alt="No data" style="width: 200px;">
                            <h5 class="mt-3">No owners found</h5>
                            <p class="text-muted">Try adjusting your search criteria</p>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <nav id="pagination" aria-label="Page navigation" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <!-- Pagination will be inserted here by JavaScript -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
.dashboard-wrapper {
    background: #f8f9fc;
    min-height: calc(100vh - 60px);
    padding: 20px;
}

.card {
    border: none;
    border-radius: 12px;
    transition: transform 0.2s ease;
}

.search-group {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
}

.search-input {
    padding-left: 40px;
    border-radius: 8px;
    border: 1px solid #e3e6f0;
}

.search-input:focus {
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    border-color: #4e73df;
}

.btn {
    border-radius: 8px;
    padding: 8px 16px;
    font-weight: 500;
}

.btn-primary {
    background: #4e73df;
    border-color: #4e73df;
}

.table {
    margin-bottom: 0;
}

.table thead th {
    background: #f8f9fc;
    border-bottom: 2px solid #e3e6f0;
    color: #5a5c69;
    font-weight: 600;
    white-space: nowrap;
}

.table td {
    vertical-align: middle;
    padding: 1rem;
}

.owner-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #4e73df;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    margin-right: 15px;
}

.action-btn {
    width: auto;
    min-width: 35px;
    height: 35px;
    padding: 0 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    margin: 0 3px;
    transition: all 0.2s ease;
    color: #fff !important;
}

.action-btn i {
    font-size: 14px;
    margin-right: 6px;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.pagination {
    margin-bottom: 0;
}

.page-link {
    border: none;
    padding: 8px 16px;
    color: #5a5c69;
    margin: 0 3px;
    border-radius: 8px;
}

.page-item.active .page-link {
    background: #4e73df;
    color: white;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
}

.status-active {
    background: #e8f5e9;
    color: #2e7d32;
}

.status-inactive {
    background: #ffebee;
    color: #c62828;
}
</style>

<?php include_once('../footer.php');?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let currentPage = 1;
let searchTimeout;

function fetchOwners(page = 1) {
    $('#loading-spinner').removeClass('d-none');
    $('#owner-table tbody').addClass('d-none');
    $('#empty-state').addClass('d-none');
    
    const name = $('#search-name').val().trim();
    const unit = $('#search-unit').val().trim();
    
    $.ajax({
        url: 'search.php',
        method: 'GET',
        data: { name, unit, page },
        dataType: 'json',
        success: function(response) {
            $('#loading-spinner').addClass('d-none');
            $('#owner-table tbody').removeClass('d-none');
            
            if (response.error) {
                Swal.fire('Error', response.error, 'error');
                return;
            }

            if (!response.owners.length) {
                $('#empty-state').removeClass('d-none');
                $('#owner-table tbody').html('');
                return;
            }

            let tableBody = '';
            response.owners.forEach(owner => {
                const initial = owner.name.charAt(0).toUpperCase();
                const status = Math.random() > 0.5 ? 'active' : 'inactive'; // 模拟状态

                tableBody += `
                    <tr class="animate__animated animate__fadeIn">
                        <td>${owner.id}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="owner-avatar">${initial}</div>
                                <div>
                                    <div class="fw-bold">${owner.name}</div>
                                    <small class="text-muted">${owner.email}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div><i class="fas fa-phone-alt me-2"></i>${owner.phone}</div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-building me-2"></i>
                                Unit ${owner.unit}
                            </div>
                        </td>
                        <td>
                            <span class="status-badge ${status === 'active' ? 'status-active' : 'status-inactive'}">
                                <i class="fas fa-circle me-1" style="font-size: 8px;"></i>
                                ${status.charAt(0).toUpperCase() + status.slice(1)}
                            </span>
                        </td>
                        <td>
                            <button class="action-btn btn btn-primary" onclick="editOwner(${owner.id})" title="Edit">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="action-btn btn btn-danger" onclick="deleteOwner(${owner.id})" title="Delete">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                           
                        </td>
                    </tr>
                `;
            });

            $('#owner-table tbody').html(tableBody);

            // 更新分页
            updatePagination(response.totalPages, page);
        },
        error: function() {
            $('#loading-spinner').addClass('d-none');
            Swal.fire('Error', 'Failed to load data. Please try again.', 'error');
        }
    });
}

function updatePagination(totalPages, currentPage) {
    let pagination = '';
    
    if (totalPages > 1) {
        pagination += `
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage-1}">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>`;

        for (let i = 1; i <= totalPages; i++) {
            pagination += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>`;
        }

        pagination += `
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage+1}">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>`;
    }

    $('#pagination .pagination').html(pagination);
}

function deleteOwner(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74a3b',
        cancelButtonColor: '#858796',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `delete_owner.php?id=${id}`;
        }
    });
}

function editOwner(id) {
    window.location.href = `edit_owner.php?id=${id}`;
}

function viewDetails(id) {
    // 实现查看详情功能
    Swal.fire({
        title: 'Owner Details',
        text: 'Details view functionality coming soon...',
        icon: 'info'
    });
}

$(document).ready(function() {
    fetchOwners();
    
    // 实时搜索
    $('#search-name, #search-unit').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentPage = 1;
            fetchOwners(currentPage);
        }, 500);
    });
    
    $('#search-form').on('submit', function(e) {
        e.preventDefault();
        currentPage = 1;
        fetchOwners(currentPage);
    });
    
    $('#pagination').on('click', '.page-link', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page) {
            currentPage = page;
            fetchOwners(page);
        }
    });
});
</script>