<x-admin-layout>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4><i class="bi bi-pin-map"></i> Chi tiết trạm: {{ $station->name }}</h4>
    <a href="/admin/stations" class="btn btn-sm btn-secondary">← Quay lại</a>
</div>

<div class="row g-3">
    {{-- Thông tin trạm --}}
    <div class="col-md-5">
        <div class="card p-3">
            <p><i class="bi bi-pin-map"></i> <strong>Tên:</strong> {{ $station->name }}</p>
            <p><i class="bi bi-geo-alt"></i> <strong>Địa chỉ:</strong> {{ $station->address }}</p>
            <p><i class="bi bi-grid-3x3-gap"></i> <strong>Tổng chỗ:</strong> {{ $station->slots }}</p>
            <p>
                <i class="bi bi-p-square"></i> <strong>Chỗ trống:</strong>
                <span class="{{ $emptySlot >= $station->slots * 0.5 ? 'text-success' : 'text-danger' }} fw-bold">
                    {{ $emptySlot }}
                </span>
            </p>
            <p>
                <i class="bi bi-bicycle"></i> <strong>Số xe:</strong>
                <span class="{{ $bikeCount >= $station->slots * 0.5 ? 'text-success' : 'text-danger' }} fw-bold">
                    {{ $bikeCount }}
                </span>
            </p>
            <p>
                <strong>Tình trạng:</strong>
                @if($station->status === 'active')
                    <span class="badge bg-success">Hoạt động</span>
                @else
                    <span class="badge bg-danger">Bảo trì</span>
                @endif
            </p>
            <p>
                <strong>Điểm TB:</strong>
                @for($i=1;$i<=5;$i++)
                    <span class="{{ $i <= round($station->reviews->avg('station_rating') ?? 0) ? 'text-warning' : 'text-muted' }}">★</span>
                @endfor
            </p>
        </div>
    </div>

    {{-- Tất cả đánh giá --}}
    <div class="col-md-7">
        <h5>Tất cả đánh giá ({{ $station->reviews->count() }})</h5>
        @forelse($station->reviews as $review)
        <div class="card p-2 mb-2">
            <div class="d-flex justify-content-between align-items-center">
                <strong>{{ $review->user->name }}</strong>
                <span>
                    @for($i=1;$i<=5;$i++)
                        <span class="{{ $i <= $review->station_rating ? 'text-warning' : 'text-muted' }}">★</span>
                    @endfor
                </span>
            </div>
            <p class="mb-0 text-muted small fst-italic">
                "{{ $review->station_comment ?? 'Không có bình luận' }}"
            </p>
            <small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small>
        </div>
        @empty
            <p class="text-muted">Chưa có đánh giá nào</p>
        @endforelse
    </div>
</div>
</x-admin-layout>
