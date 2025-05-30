<?php $__env->startSection('title'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('title'); ?>
    JFS | Dashboard
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('content'); ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
            <div class="container-fluid bg-white">
                <!-- Content Row for Cards -->
                <div class="row pt-5 pb-5">
                    <div class="col-md-4 mb-3 bg-white rounded border shadow pt-4">
                        <div class="row align-items-center justify-content-center">
                        <!-- Card 1: Total Loans -->
                            <div class="col-md-4">
                                <a href="#" target="_blank" style="text-decoration: none;">
                                    <div class="text-center dash-card">
                                        <h6 class="card-title">Total Customers</h6>
                                        <h3 class=""><?php echo e($totalCustomers); ?></h3>
                                    </div>
                                </a>
                            </div>

                            <!-- Card 2: Disbursed Loans -->
                            <div class="col-md-4">
                                <a href="#" target="_blank" style="text-decoration: none;">
                                    <div class="text-center dash-card">
                                        <h6 class="card-title">Total<br>Officers</h6>
                                        <h3 class=""><?php echo e($totalOfficers); ?></h3>
                                    </div>
                                </a>
                            </div>
                            <!-- Card 3: Approved Loans -->
                            <div class="col-md-4">
                                <a href="#" target="_blank" style="text-decoration: none;">
                                    <div class="text-center dash-card">
                                        <h6 class="card-title">Total<br>Loans</h6>
                                        <h3 class=""><?php echo e($totalLoans); ?></h3>
                                    </div>
                                </a>
                            </div>

                            <!-- Card 4: Rejected Loans -->
                            <div class="col-md-4">
                                <a href="#" target="_blank" style="text-decoration: none;">
                                    <div class="text-center dash-card">
                                        <h6 class="card-title">Disbursed Loans</h6>
                                        <h3 class=""><?php echo e($disbursedLoans); ?></h3>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="#" target="_blank" style="text-decoration: none;">
                                    <div class="text-center dash-card">
                                        <h6 class="card-title">Total<br>Leads</h6>
                                        <h3 class=""><?php echo e($leads); ?></h3>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="#" target="_blank" style="text-decoration: none;">
                                    <div class="text-center dash-card">
                                        <h6 class="card-title">Total Enquiries</h6>
                                        <h3 class=""><?php echo e($enquiries); ?></h3>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="#" target="_blank" style="text-decoration: none;">
                                    <div class="text-center dash-card">
                                        <h6 class="card-title">Approved Loans</h6>
                                        <h3 class=""><?php echo e($approvedLoans); ?></h3>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="#" target="_blank" style="text-decoration: none;">
                                    <div class="text-center dash-card">
                                        <h6 class="card-title">Rejected Loans</h6>
                                        <h3 class=""><?php echo e($rejectedLoans); ?></h3>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="#" target="_blank" style="text-decoration: none;">
                                    <div class="text-center dash-card">
                                        <h6 class="card-title">Total Properties</h6>
                                        <h3 class=""><?php echo e($properties); ?></h3>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="card-body bg-white rounded border shadow">
                            <h5 class="text-center font-weight-bold text-dark">Loan Status Distribution</h5>
                            <canvas id="loanStatusChart" height="200"></canvas>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3 bg-white rounded border shadow">
                        <div class="card-body">
                            <h5 class="text-center font-weight-bold text-dark">Monthly Disbursed Loans</h5>
                            <canvas id="monthlyDisbursedChart" height="200"></canvas>
                        </div>
                    </div>

                    <div class="col-8">
                        <div class="card-body border rounded shadow">
                            <h3 class="text-dark">Recent Loans</h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>                                                
                                            <th> Reference ID </th>
                                            <th> Name </th>
                                            <th> Amount </th>
                                            <th> Category </th>
                                            <th> Status </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $recentLoans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                
                                                <td><?php echo e($loan->loan_reference_id); ?></td>
                                                <td><?php echo e($loan->user_name); ?></td>
                                                <td><?php echo e($loan->amount); ?></td>
                                                <td><?php echo e($loan->loan_category_name); ?></td>
                                                <td>
                                                    <div class="status-box <?php echo e(strtolower(str_replace(' ', '-', $loan->status))); ?>">
                                                        <?php echo e(ucfirst($loan->status)); ?>

                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="6" class="text-center">No recent loans available</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- To-do tasks -->
                    <div class="col-md-4 bg-white rounded border shadow">
                        <div class="card-body">
                            <h5 class="text-center font-weight-bold text-dark">To-Do Tasks</h5>
                            <!-- Task Input -->
                            <div class="input-group mb-3">
                                <input type="text" id="taskInput" class="form-control" placeholder="Enter a new task" aria-label="New Task">
                                <button class="btn btn-primary" type="button" id="addTaskButton">Add</button>
                            </div>
                            <!-- Task List -->
                            <ul id="taskList" class="list-group">
                                <!-- Tasks will be dynamically added here -->
                            </ul>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
        <input type="hidden" id="servertime" value="">
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('script'); ?>
    <!-- Page level plugins -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Loan Status Distribution Chart (Donut Chart)
    var ctx1 = document.getElementById('loanStatusChart').getContext('2d');
    var loanStatusChart = new Chart(ctx1, {
        type: 'doughnut', // Change to doughnut for donut chart
        data: {
            labels: ['In Process', 'Approved', 'Disbursed', 'Rejected'],
            datasets: [{
                data: [<?php echo e($loanStatuses['In Process']); ?>, <?php echo e($loanStatuses['Approved']); ?>, <?php echo e($loanStatuses['Disbursed']); ?>, <?php echo e($loanStatuses['Rejected']); ?>],
                backgroundColor: ['#D49B54', '#1E5128', '#1E3E62', '#C74B50'], // Colors for each status
                borderColor: '#ffffff',
                borderWidth: 2 // Border width for each segment
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top', // Legend position
                    labels: {
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.raw || 0;
                            return label + ': ' + value;
                        }
                    }
                }
            },
            cutout: '70%', // Inner cutout for donut chart
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('monthlyDisbursedChart').getContext('2d');
        const monthlyData = <?php echo json_encode($monthlyDisbursedData, 15, 512) ?>;

        const months = monthlyData.map(item => item.month);
        const totalLoans = monthlyData.map(item => item.total_loans);
        const totalAmount = monthlyData.map(item => item.total_amount);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Total Loans',
                        data: totalLoans,
                        backgroundColor: '#C74B50',
                        borderRadius: 10, // Rounds the bar corners
                        borderWidth: 1,
                    },
                    {
                        label: 'Total Amount',
                        data: totalAmount,
                        backgroundColor: '#1E3E62',
                        borderRadius: 10, // Rounds the bar corners
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
            },
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const taskInput = document.getElementById('taskInput');
        const addTaskButton = document.getElementById('addTaskButton');
        const taskList = document.getElementById('taskList');

        // Add Task
        addTaskButton.addEventListener('click', function () {
            const taskValue = taskInput.value.trim();

            if (taskValue) {
                const listItem = document.createElement('li');
                listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                listItem.textContent = taskValue;

                // Add cross mark to task
                const removeButton = document.createElement('span');
                removeButton.className = 'text-danger ms-2';
                removeButton.style.cursor = 'pointer';
                removeButton.innerHTML = '&times;';
                removeButton.addEventListener('click', function () {
                    taskList.removeChild(listItem);
                });

                listItem.appendChild(removeButton);
                taskList.appendChild(listItem);

                // Clear input after adding task
                taskInput.value = '';
            } else {
                alert('Please enter a task.');
            }
        });

        // Optional: Allow pressing Enter to add tasks
        taskInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                addTaskButton.click();
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\jfinserv\resources\views/admin/dashboard-dark.blade.php ENDPATH**/ ?>