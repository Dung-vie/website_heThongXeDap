<x-admin-layout>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4><i class="bi bi-pin-map"></i> Quản lý trạm xe</h4>
    <div class="d-flex gap-2">
        <a href="/admin/stations/create" class="btn btn-success btn-sm">
            <i class="bi bi-plus-lg"></i> Thêm trạm xe
        </a>
        <a href="/admin/stations/bin" class="btn btn-secondary btn-sm">
            <i class="bi bi-trash"></i> Thùng rác
        </a>
    </div>
</div>

<form id="bulkForm" action="/admin/stations/bulk" method="POST">
    @csrf
    <input type="hidden" name="action" id="bulkAction">

    <div class="table-responsive">
    <table class="table table-bordered table-hover bg-white align-middle">
        <thead class="table-dark">
            <tr>
                <th><input type="checkbox" id="checkAll"></th>
                <th>Tên trạm</th>
                <th>Địa chỉ</th>
                <th>Chỗ trống / Tổng</th>
                <th>Số xe</th>
                <th>Tình trạng</th>
                <th>Điểm đánh giá</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        @foreach($stations as $station)
        <tr>
            <td><input type="checkbox" name="ids[]" value="{{ $station->id }}"></td>

            {{-- Tên trạm → link chi tiết --}}
            <td>
                <a href="/admin/stations/{{ $station->id }}" class="fw-bold text-decoration-none">
                    {{ $station->name }}
                </a>
            </td>

            {{-- Địa chỉ --}}
            <td><small>{{ $station->address }}</small></td>

            {{-- Chỗ trống / Tổng --}}
            @php
                $emptySlot  = $station->slots - $station->bike_count;
                $emptyRatio = $station->slots > 0 ? $emptySlot / $station->slots : 0;
            @endphp
            <td>
                <span class="{{ $emptyRatio >= 0.5 ? 'text-success fw-bold' : 'text-danger fw-bold' }}">
                    <i class="bi bi-p-square"></i> {{ $emptySlot }}
                </span>
                / {{ $station->slots }}
            </td>

            {{-- Số xe --}}
            @php $bikeRatio = $station->slots > 0 ? $station->bike_count / $station->slots : 0; @endphp
            <td>
                <span class="{{ $bikeRatio >= 0.5 ? 'text-success fw-bold' : 'text-danger fw-bold' }}">
                    <i class="bi bi-bicycle"></i> {{ $station->bike_count }}
                </span>
            </td>

            {{-- Tình trạng --}}
            <td>
                @if($station->status === 'active')
                    <span class="badge bg-success">
                        <i class="bi bi-check-circle"></i> Hoạt động
                    </span>
                @else
                    <span class="badge bg-danger">
                        <i class="bi bi-wrench"></i> Bảo trì
                    </span>
                @endif
            </td>

            {{-- Điểm đánh giá --}}
            <td>
                @php $rating = round($station->avg_rating ?? 0) @endphp
                @for($i = 1; $i <= 5; $i++)
                    <span class="{{ $i <= $rating ? 'text-warning' : 'text-muted' }}">★</span>
                @endfor
                <small class="text-muted">({{ number_format($station->avg_rating ?? 0, 1) }})</small>
            </td>

            {{-- Hành động --}}
            <td>
                <a href="/admin/stations/{{ $station->id }}/edit" class="btn btn-sm btn-primary">
                    <i class="bi bi-pencil"></i>
                </a>
                <button type="button" class="btn btn-sm btn-danger"
                        onclick="confirmDelete({{ $station->id }})">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    </div>

    {{ $stations->links() }}
</form>

{{-- Form xóa riêng --}}
<form id="deleteForm" method="POST" style="display:none">
    @csrf @method('DELETE')
</form>

@push('scripts')
<script>
document.getElementById('checkAll').addEventListener('change', function() {
    document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = this.checked);
});

function confirmDelete(id) {
    if (!confirm('Bạn có muốn xóa trạm xe này không?')) return;
    const f = document.getElementById('deleteForm');
    f.action = '/admin/stations/' + id;
    f.submit();
}
</script>
@endpush


</x-admin-layout>
