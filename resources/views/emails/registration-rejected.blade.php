@component('mail::message')
# Registration Not Approved

Hello {{ $employee->name }},

Your registration for the Employee Management System has been reviewed, but unfortunately it was not approved at this time.

## Reason for Rejection
{{ $employee->rejection_reason }}

## Next Steps
Please contact the HR department for more information about what additional information or corrections are needed. Once the issues are resolved, you can submit a new registration.

If you have any questions, please reach out to the HR department.

Best regards,  
Employee Management System
@endcomponent
