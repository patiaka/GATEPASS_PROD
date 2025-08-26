@props(['row'])
<span @class([ 'px-2 py-1 text-xs font-medium text-white rounded-full' , 'bg-blue-500'=> $row->isApproved(),
    'bg-red-500' => $row->isRejected(),
    'bg-red-700' => $row->isExpired(),
    'bg-sky-500' => $row->isPending(),
    'bg-yellow-500' => $row->isProgress(),
    ])>
    {{ $row->status }}
</span>
