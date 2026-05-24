<div class="flex gap-3 mb-4 " dir="rtl">

    <!-- 1. بطاقة إجمالي العملاء -->
    <div class="flex-fill" style="min-width: 250px;">
        <div class="card h-100 shadow-sm border-0 position-relative overflow-hidden"
            style="border-right: 5px solid #0d6efd !important;">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <h6 class="text-muted mb-1 fw-bold" style="font-size: 0.9rem;">إجمالي الإجرائات</h6>
                    <h3 class="mb-0 fw-black" style="color: #333; font-size: 1.8rem;">
                        {{ number_format($statusCounts->sum('count')) }}
                    </h3>
                </div>
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center"
                    style="background-color: rgba(13, 110, 253, 0.1);">
                    <i class="fs-4 bi bi-people text-primary"></i>
                </div>
            </div>
        </div>
    </div>

    @foreach ($statusCounts as $item)
        @if ($item['status'] !== 'new')
            <div class="flex-fill" style="min-width: 250px;">
                <div class="card h-100 shadow-sm border-0 position-relative overflow-hidden"
                    style="border-right: 5px solid 
                    @if ($item['status'] === 'contacted') #fd7e14 
                    @elseif($item['status'] === 'cancelled') #dc3545 
                    @elseif($item['status'] === 'confirmed') #198754 
                    @else #6c757d @endif !important;">

                    <div class="card-body d-flex align-items-center justify-content-between p-3">
                        <div>
                            <h6 class="text-muted mb-1 fw-bold" style="font-size: 0.9rem;">
                                {{ $item['label'] }}
                            </h6>
                            <h3 class="mb-0 fw-black" style="color: #333; font-size: 1.8rem;">
                                {{ number_format($item['count']) }}
                            </h3>
                        </div>

                        <div class="rounded-circle p-3 d-flex align-items-center justify-content-center"
                            style="background-color: 
                            @if ($item['status'] === 'contacted') rgba(253, 126, 20, 0.1) 
                            @elseif($item['status'] === 'cancelled') rgba(220, 53, 69, 0.1) 
                            @elseif($item['status'] === 'confirmed') rgba(25, 135, 84, 0.1) 
                            @else rgba(108, 117, 125, 0.1) @endif;">

                            <i class="fs-4 
                                @if ($item['status'] === 'contacted') bi bi-telephone-outbound text-warning
                                @elseif($item['status'] === 'cancelled') bi bi-person-x text-danger
                                @elseif($item['status'] === 'confirmed') bi bi-check-circle text-success
                                @else bi bi-info-circle text-secondary @endif">
                            </i>
                        </div>
                    </div>

                </div>
            </div>
        @endif
    @endforeach

</div>