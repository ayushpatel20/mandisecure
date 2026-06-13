@php
    $steps        = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
    $currentIndex = array_search($order->status, $steps);
    $isCancelled  = $order->status === 'cancelled';
@endphp

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        @if ($isCancelled)
            <div class="alert alert-danger mb-0 py-2 d-flex align-items-center gap-2">
                <i class="bi bi-x-circle fs-5"></i>
                <span>This order was <strong>cancelled</strong>.</span>
            </div>
        @else
            <div class="d-flex justify-content-between align-items-start position-relative px-2">

                {{-- Track lines --}}
                <div class="position-absolute" style="top:16px;left:2rem;right:2rem;height:3px;background:#dee2e6;z-index:0"></div>
                <div class="position-absolute"
                     style="top:16px;left:2rem;height:3px;
                            width:calc({{ $currentIndex !== false ? ($currentIndex / (count($steps) - 1)) * 100 : 0 }}% - {{ $currentIndex > 0 ? '0px' : '0px' }});
                            background:#198754;z-index:1;transition:width 0.4s;max-width:calc(100% - 4rem)">
                </div>

                @foreach ($steps as $i => $step)
                    @php
                        $done    = $currentIndex !== false && $i <= $currentIndex;
                        $current = $currentIndex !== false && $i === $currentIndex;
                        $stepLog = $order->statusLogs->firstWhere('status', $step);
                    @endphp
                    <div class="d-flex flex-column align-items-center position-relative" style="z-index:2;min-width:60px">
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold mb-1"
                             style="width:32px;height:32px;font-size:0.75rem;transition:background 0.3s;
                                    background:{{ $done ? '#198754' : '#dee2e6' }};
                                    color:{{ $done ? '#fff' : '#6c757d' }};
                                    {{ $current ? 'box-shadow:0 0 0 4px rgba(25,135,84,0.2)' : '' }}">
                            @if ($done && !$current)
                                <i class="bi bi-check"></i>
                            @else
                                {{ $i + 1 }}
                            @endif
                        </div>
                        <small class="text-center {{ $current ? 'text-success fw-semibold' : 'text-muted' }}"
                               style="font-size:0.65rem;line-height:1.2">
                            {{ ucfirst($step) }}
                        </small>
                        @if ($stepLog)
                            <small class="text-muted text-center" style="font-size:0.6rem">
                                {{ $stepLog->created_at->format('d M') }}
                            </small>
                        @endif
                    </div>
                @endforeach

            </div>
        @endif
    </div>
</div>
