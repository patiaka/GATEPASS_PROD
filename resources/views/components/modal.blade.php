@props(['title'])
<div wire:ignore.self>
    <div id="modalCenter" class="modal custom-modal fade" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    {{ $slot }}

                </div>
            </div>
        </div>
    </div>
</div>