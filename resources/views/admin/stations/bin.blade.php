<x-admin-layout>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4><i class="bi bi-trash"></i> Thùng rác - Trạm xe</h4>
    <a href="/admin/stations" class="btn btn-sm btn-secondary">← Quay lại</a>
</div>

<form id="bulkForm" action="/admin/stations/bulk" method="POST">
    @csrf
    <input type="hidden" name="action" id="bulkAction">

    <div class="d-flex gap-2 mb-3">
        <button type="button" class="btn btn-sm btn-success"
                onclick="bulkAction('restore')">
            <i class="bi bi-arrow-counterclockwise"></i> Khôi phục được chọn
        </button>
        <button type="button" class="btn btn-sm btn-danger"
                onclick="bulkAction('force-delete')">
            <i class="bi bi-x-circle"></i> Xóa được chọn
        </button>
        <button type="button" class="btn btn-sm btn-outline-success"
                onclick="bulkAll('restore')">
            <i class="bi bi-arrow-counterclockwise"></i> Khôi phục tất cả
        </button>
        <button type="button" class="btn btn-sm btn-outline-danger"
                onclick="bulkAll('force-delete')">
            <i class="bi bi-trash"></i> Xóa tất cả
        </button>
    </div>

    <table class="table table-bordered bg-white align-middle">
        <thead class="table-dark">
            <tr>
                <th><input type="checkbox" id="checkAll"></th>
                <th>Tên trạm xe</th>
                <th>Tình trạng</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        @forelse($stations as $station)
        <tr>
            <td><input type="checkbox" name="ids[]" value="{{ $station->id }}"></td>
            <td>{{ $station->name }}</td>
            <td>
                @if($station->status === 'active')
                    <span class="badge bg-success">Hoạt động</span>
                @else
                    <span class="badge bg-danger">Bảo trì</span>
                @endif
            </td>
            <td class="d-flex gap-1">
                <form action="/admin/stations/{{ $station->id }}/restore" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-success">
                        <i class="bi bi-arrow-counterclockwise"></i> Khôi phục
                    </button>
                </form>
                <form action="/admin/stations/{{ $station->id }}/force" method="POST"
                      onsubmit="return confirm('Xóa vĩnh viễn trạm này?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">
                        <i class="bi bi-x-circle"></i> Xóa hẳn
                    </button>
                </form>
            </td>
        </tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted">Thùng rác trống</td></tr>
        @endforelse
        </tbody>
    </table>

    {{ $stations->links() }}
</form>

@push('scripts')
<script>
document.getElementById('checkAll').addEventListener('change', function() {
    document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = this.checked);
});

function bulkAction(action) {
    const checked = document.querySelectorAll('input[name="ids[]"]:checked');
    if (!checked.length) { alert('Chưa chọn trạm nào'); return; }
    if (!confirm('Xác nhận thực hiện?')) return;
    document.getElementById('bulkAction').value = action;
    document.getElementById('bulkForm').submit();
}

function bulkAll(action) {
    if (!confirm('Xác nhận thực hiện tất cả?')) return;
    // check all rồi submit
    document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = true);
    document.getElementById('bulkAction').value = action;
    document.getElementById('bulkForm').submit();
}
</script>
@endpush
</x-admin-layout>
