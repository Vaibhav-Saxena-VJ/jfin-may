@extends('layouts.header')

@section('title')
    @parent
    Loans
@endsection

@section('content')
    @parent
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" rel="stylesheet" />

    <!-- DataTables Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="d-flex align-items-center">
                    <ol class="breadcrumb m-0 bg-transparent">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            @if (session()->get('role_id') == 2)
                                Assigned Loans
                            @else
                                All Loans
                            @endif
                        </li>
                    </ol>
                </nav>


            </div>
        </div>
        <div class="card-body">

            <form id="filterForm" method="GET" action="{{ route('agent.allAgentLoans') }}">
                <div class="mb-3">
                    <label for="statusFilter" class="form-label">Filter by Loan Status:</label>
                    <select id="statusFilter" name="status" class="form-select" style="max-width: 250px;">
                        <option value="">All</option>
                        <option value="in process" {{ request('status') == 'in process' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="disbursed" {{ request('status') == 'disbursed' ? 'selected' : '' }}>Disbursed
                        </option>
                    </select>
                </div>
            </form>

            <div class="table-responsive">
                <table id="example" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Loan ID</th>
                            <th>User</th>
                            <th>Loan Category</th>
                            <th>Amount</th>
                            <th>Tenure</th>
                            <th>Agent Status</th>
                            @if (session()->get('role_id') == 2)
                                <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['loans'] as $loan)
                            <tr>
                                <td>{{ $loan->loan_reference_id }}</td>
                                <td>{{ $loan->user_name }}</td>
                                <td>{{ $loan->loan_category_name }}</td>
                                <td>{{ $loan->amount }}</td>
                                <td>{{ $loan->tenure }}</td>
                                <td>{{ ucfirst($loan->agent_action) ?? 'Pending' }}</td>
                                @if (session()->get('role_id') == 2)
                                    <td>
                                        <a class="btn btn-primary btn-xs view" title="View"
                                            href="{{ route('agent.loan.view', ['id' => $loan->loan_id]) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @if ($loan->status !== 'disbursed')
                                            <a class="btn btn-primary btn-xs edit" title="Edit"
                                                href="{{ route('agent.editLoan', ['id' => $loan->loan_id]) }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @else
                                            <button class="btn btn-secondary btn-xs edit" title="Cannot Edit" disabled>
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        @endif
                                        <button class="btn btn-danger btn-xs delete" title="Delete"
                                            onclick="deleteLoan('{{ $loan->loan_id }}')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Loan ID</th>
                            <th>User</th>
                            <th>Loan Category</th>
                            <th>Amount</th>
                            <th>Tenure</th>
                            <th>Agent Status</th>
                            @if (session()->get('role_id') == 2)
                                <th>Action</th>
                            @endif
                        </tr>
                    </tfoot>
                </table>

                <!-- Pagination Links -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="dataTables_info">
                        Showing {{ $data['loans']->firstItem() }} to {{ $data['loans']->lastItem() }} of
                        {{ $data['loans']->total() }}
                        entries
                    </div>
                    <div class="dataTables_paginate paging_simple_numbers">
                        <nav>
                            {{ $data['loans']->onEachSide(1)->links('pagination::bootstrap-4') }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @parent
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();

            @if (session('success'))
                Swal.fire({
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            @elseif (session('error'))
                Swal.fire({
                    title: 'Error!',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            @endif
        });

        // Define deleteLoan function
        function deleteLoan(loanId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteLoan') }}",
                        method: "POST",
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'loan_id': loanId,
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                'Your loan has been deleted.',
                                'success'
                            ).then(function() {
                                location.reload();
                            });
                        },
                        error: function(response) {
                            Swal.fire(
                                'Error!',
                                'There was an issue deleting the loan.',
                                'error'
                            );
                        }
                    });
                }
            });
        }

        document.getElementById('statusFilter').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    </script>
@endsection
