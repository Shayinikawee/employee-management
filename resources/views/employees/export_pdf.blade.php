<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Employee List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #0f172a;
        }
        .header p {
            margin: 4px 0 0;
            font-size: 11px;
            color: #64748b;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        thead th {
            background-color: #0f172a;
            color: #ffffff;
            text-align: left;
            padding: 8px 6px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        tbody td {
            padding: 6px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10px;
        }
        tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 600;
        }
        .badge-active {
            background-color: #dcfce7;
            color: #166534;
        }
        .badge-inactive {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Road Development Authority — Deniyaya Office</h1>
        <p>Employee List &mdash; Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>PF Number</th>
                <th>Email</th>
                <th>NIC</th>
                <th>Contact</th>
                <th>Designation</th>
                <th>Grade</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $index => $employee)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->pf_number }}</td>
                <td>{{ $employee->email }}</td>
                <td>{{ $employee->nic }}</td>
                <td>{{ $employee->contact_number }}</td>
                <td>{{ $employee->current_designation }}</td>
                <td>{{ $employee->grade }}</td>
                <td>
                    <span class="badge {{ $employee->is_active ? 'badge-active' : 'badge-inactive' }}">
                        {{ $employee->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 20px; color: #94a3b8;">No employees found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total Employees: {{ $employees->count() }} &bull; RDA Employee Management System &bull; &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>
