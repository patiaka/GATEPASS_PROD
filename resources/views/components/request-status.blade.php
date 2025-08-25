@props(['model','type'])
@php
$status = null;

if ($model->isPending()) {
$status = ['✅ Pending', 'btn-secondary'];
} elseif ($model->isProgress() && $model->isHodApproved() && $type === 'hod') {
$status = ['✅ Approved', 'btn-secondary'];
} elseif ($model->isRejected() && $model->isHodApproved() && $type === 'hod') {
$status = ['❌ Rejected', 'btn-secondary'];
} elseif ($model->isPending()) {
$status = ['✅ Pending', 'btn-secondary'];
} elseif ($model->isApproved() && $model->isGmApproved() && $type === 'gm') {
$status = ['✅ Approved', 'btn-secondary'];
} elseif ($model->isRejected() && $model->isGmApproved() && $type === 'gm') {
$status = ['❌ Rejected', 'btn-secondary'];
}
@endphp

@if ($status)
<a href="#" class="{{ $status[1] }}">
    {{ $status[0] }}
</a>
@endif