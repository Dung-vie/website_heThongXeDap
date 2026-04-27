<x-admin-layout>
    <h4><i class="bi bi-bicycle"></i> Chi tiết xe: {{ $bike->plate_number }}</h4>
<a href="/admin/bikes" class="btn btn-sm btn-secondary mb-3">← Quay lại</a>

<div class="row g-3">
    <div class="col-md-5">
        <div class="card p-3">
            <p><strong>Biển số:</strong> {{ $bike->plate_number }}</p>
            <p><strong>Trạm:</strong> {{ $bike->station->name ?? 'Đang được thuê' }}</p>
            <p><strong>Tình trạng:</strong>
                @if($bike->status === 'normal')
                    <span class="badge bg-success">Bình thường</span>
                @else
                    <span class="badge bg-danger">Đang sửa</span>
                @endif
            </p>
            <p><strong>Đánh giá TB:</strong>
                @for($i = 1; $i <= 5; $i++)
                    <span class="{{ $i <= round($bike->reviews->avg('bike_rating') ?? 0) ? 'text-warning' : 'text-muted' }}">★</span>
                @endfor
            </p>
        </div>
    </div>

    <div class="col-md-7">
        <h5>Tất cả đánh giá</h5>
        @forelse($bike->reviews as $review)
        <div class="card p-2 mb-2">
            <div class="d-flex justify-content-between">
                <strong>{{ $review->user->name }}</strong>
                <span>
                    @for($i=1;$i<=5;$i++)
                        <span class="{{ $i <= $review->bike_rating ? 'text-warning' : 'text-muted' }}">★</span>
                    @endfor
                </span>
            </div>
            <p class="mb-0 text-muted small">{{ $review->bike_comment }}</p>
        </div>
        @empty
            <p class="text-muted">Chưa có đánh giá</p>
        @endforelse
    </div>
</div>

</x-admin-layout>
