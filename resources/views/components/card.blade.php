@props(['count', 'title', 'icon', 'color'])
<div class="col-sm-6 col-lg-3 mb-4">
    <div class="card h-100 card-border-shadow-{{ $color }}">
        <div class="card-body">
            <div class="d-flex align-items-center mb-2 pb-1">
                <div class="avatar me-2">
                    <span @class(['avatar-initial rounded','bg-label-info'=> $color, 'bg-label-warning' => $color ===
                        'warning', 'bg-label-danger' => $color === 'danger', 'bg-label-success' => $color ===
                        'success']) ><i class="bx {{ $icon }}"></i></span>
                </div>
                <h4 class="ms-1 mb-0">{{ $count }}</h4>
            </div>
            <p class="mb-1">{{ $title }}</p>
        </div>
    </div>
</div>
