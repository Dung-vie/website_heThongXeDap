<p> <strong>Địa chỉ:</strong> {{ $station->address }}</p>

<div class="row mb-3 text-center">
    <div class="col">
        Tổng chỗ: <strong>{{ $station->total_slots }}</strong>
    </div>

    @php
        $emptySlots = $station->total_slots - $parked;
        $emptyColor = $emptySlots >= $station->total_slots * 0.5 ? 'text-success' : 'text-danger';
        $bikeColor  = $parked >= $station->total_slots * 0.5 ? 'text-success' : 'text-danger';
    @endphp

    <div class="col {{ $emptyColor }}">
        Chỗ trống: <strong>{{ $emptySlots }}</strong>
    </div>

    <div class="col {{ $bikeColor }}">
        Xe hiện có: <strong>{{ $parked }}</strong>
    </div>
</div>

<hr>

<h6>Đánh giá gần đây</h6>

<div>
    @forelse($station->reviews as $r)
        <div class="border-top pt-2 mt-2">
            <strong>{{ $r->user->name ?? 'Ẩn danh' }}</strong>
            {{ str_repeat('⭐', $r->station_rating) }}
            {{ str_repeat('☆', 5 - $r->station_rating) }}

            <p class="mb-0 text-muted small">
                {{ $r->station_comment ?? '(Không có bình luận)' }}
            </p>
        </div>
    @empty
        <p class="text-muted">Chưa có đánh giá.</p>
    @endforelse
</div>
