@extends('layouts.admin')
@section('content')
<div class="p-6"><a href="{{ route('admin.audit-logs.index') }}">&larr; Back</a><h1 class="text-2xl font-bold my-4">Audit log #{{ $auditLog->id }}</h1><pre class="bg-white p-4 rounded shadow overflow-auto">{{ json_encode($auditLog->toArray(), JSON_PRETTY_PRINT) }}</pre></div>
@endsection
