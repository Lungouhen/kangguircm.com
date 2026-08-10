@extends('layouts.admin')
@section('content')<div class="p-6"><p>Payroll records are generated rather than edited.</p><a href="{{ route('admin.hrm.payrolls.show', $payroll) }}">Return to payroll</a></div>@endsection
