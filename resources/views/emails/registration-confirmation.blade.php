@component('mail::message')
# Registration Confirmation

Hello {{ $employee->name }},

Thank you for registering in the Employee Management System!

Your registration has been submitted and is awaiting admin approval. You will receive an email notification once your registration is reviewed.

## Your Information
- **PF Number:** {{ $employee->pf_number }}
- **Name:** {{ $employee->name }}
- **Email:** {{ $employee->email }}
- **Designation:** {{ $employee->current_designation }}

## Next Steps
1. Wait for admin approval (usually within 1-2 business days)
2. You will receive a confirmation email once approved
3. Once approved, you can login with your credentials and access your profile

If you have any questions, please contact the HR department.

Best regards,  
Employee Management System
@endcomponent
