{{-- @props(['model','type'])

@php
$status = null;

if ($type === 'hod') {
if ($model->isPending()) {
$status = ['⏳ Pending', 'btn-secondary'];
} elseif ($model->isApproved() && $model->isHodApproved()) {
$status = ['✅ Approved', 'btn-success'];
} elseif ($model->isRejected() && $model->isHodApproved()) {
$status = ['❌ Rejected', 'btn-danger'];
}
}

if ($type === 'gm') {
if ($model->isProgress()) {
$status = ['⏳ Pending', 'btn-secondary'];
} elseif ($model->isApproved() && $model->isGmApproved()) {
$status = ['✅ Approved', 'btn-success'];
} elseif ($model->isRejected() && $model->isGmApproved()) {
$status = ['❌ Rejected', 'btn-danger'];
}
}
@endphp

@if ($status)
<a href="#" class="{{ $status[1] }}">
    {{ $status[0] }}
</a>
@endif --}}

@props(['status'])

<a href="#" class="{{ $status[1] }}">
    {{ $status[0] }}
</a>
