<x-admin-layout>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="bi bi-bicycle"></i> Quản lý xe</h4>
        <div class="d-flex gap-2">
            <a href="/admin/bikes/create" class="btn btn-success btn-sm">
                <i class="bi bi-plus-lg"></i> Thêm xe
            </a>
            <a href="/admin/bikes/bin" class="btn btn-secondary btn-sm">
                <i class="bi bi-trash"></i> Thùng rác
            </a>
        </div>
    </div>

    <form id="bulkForm" action="/admin/bikes/bulk" method="POST">
        @csrf
        <input type="hidden" name="action" id="bulkAction">

        <div class="table-responsive">
            <table class="table table-bordered table-hover bg-white align-middle">
                <thead class="table-dark">
                    <tr>
                        <th><input type="checkbox" id="checkAll"></th>
                        <th>Biển số</th>
                        <th>Vị trí hiện tại</th>
                        <th>Tình trạng</th>
                        <th>Điểm đánh giá</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bikes as $bike)
                        <tr>
                            <td><input type="checkbox" name="ids[]" value="{{ $bike->id }}"></td>

                            {{-- Biển số → link chi tiết --}}
                            <td>
                                <a href="/admin/bikes/{{ $bike->id }}" class="fw-bold text-decoration-none">
                                    {{ $bike->plate_number }}
                                </a>
                            </td>

                            {{-- Vị trí --}}
                            <td>
                                @if ($bike->isRented())
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-person-on-bicycle"></i> Đang thuê
                                    </span>
                                @else
                                    <span class="text-success">
                                        <i class="bi bi-pin-map"></i>
                                        {{ $bike->station->name ?? 'Không rõ' }}
                                    </span>
                                @endif
                            </td>

                            {{-- Tình trạng --}}
                            <td>
                                @if ($bike->status === 'normal')
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Bình thường</span>
                                @else
                                    <span class="badge bg-danger"><i class="bi bi-wrench"></i> Đang sửa</span>
                                @endif
                            </td>

                            {{-- Điểm đánh giá --}}
                            <td>
                                @php $rating = round($bike->avg_rating ?? 0) @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= $rating ? 'text-warning' : 'text-muted' }}">★</span>
                                @endfor
                                <small class="text-muted">({{ number_format($bike->avg_rating ?? 0, 1) }})</small>
                            </td>

                            {{-- Hành động --}}
                            <td>
                                <a href="/admin/bikes/{{ $bike->id }}/edit" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger"
                                    onclick="confirmDelete('/admin/bikes/{{ $bike->id }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $bikes->links() }}
    </form>

    {{-- Form xóa riêng --}}
    <form id="deleteForm" method="POST" style="display:none">
        @csrf @method('DELETE')
    </form>

    @push('scripts')
        <script>
            // Check all
            document.getElementById('checkAll').addEventListener('change', function() {
                document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = this.checked);
            });

            // Confirm xóa 1 xe
            function confirmDelete(url) {
                if (!confirm('Bạn có muốn xóa xe này không?')) return;
                const f = document.getElementById('deleteForm');
                f.action = url;
                f.submit();
            }
        </script>
    @endpush
</x-admin-layout>
