@component('mail::message')
# Registration Approved ✓

Hello {{ $employee->name }},

Great news! Your registration has been approved by the admin.

You can now login to the Employee Management System and access your profile.

## Login Credentials
- **Email:** {{ $employee->email }}
- **Password:** The password you set during registration

@component('mail::button', ['url' => route('login')])
Login Now
@endcomponent

## What You Can Do Now
- View and update your profile
- Apply for leave
- Check your leave requests and balances
- View your leave history

If you have any questions or need assistance, please contact the HR department.

Best regards,  
Employee Management System
@endcomponent
