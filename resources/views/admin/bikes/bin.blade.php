<X-admin-layout>
    @section('content')

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4><i class="bi bi-trash"></i> Thùng rác - Xe</h4>
            <a href="/admin/bikes" class="btn btn-sm btn-secondary">← Quay lại</a>
        </div>

        <form id="bulkForm" action="/admin/bikes/bulk" method="POST">
            @csrf
            <input type="hidden" name="action" id="bulkAction">

            {{-- Bulk action buttons --}}
            <div class="d-flex gap-2 mb-3">
                <button type="button" class="btn btn-sm btn-success" onclick="bulkAction('restore')">
                    <i class="bi bi-arrow-counterclockwise"></i> Khôi phục được chọn
                </button>
                <button type="button" class="btn btn-sm btn-danger" onclick="bulkAction('force-delete')">
                    <i class="bi bi-x-circle"></i> Xóa được chọn
                </button>
                <button type="button" class="btn btn-sm btn-outline-success" onclick="bulkActionAll('restore-all')">
                    <i class="bi bi-arrow-counterclockwise"></i> Khôi phục tất cả
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="bulkActionAll('delete-all')">
                    <i class="bi bi-trash"></i> Xóa tất cả
                </button>
            </div>

            <table class="table table-bordered bg-white align-middle">
                <thead class="table-dark">
                    <tr>
                        <th><input type="checkbox" id="checkAll"></th>
                        <th>Biển số</th>
                        <th>Tình trạng</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bikes as $bike)
                        <tr>
                            <td><input type="checkbox" name="ids[]" value="{{ $bike->id }}"></td>
                            <td>{{ $bike->plate_number }}</td>
                            <td>
                                @if ($bike->status === 'normal')
                                    <span class="badge bg-success">Bình thường</span>
                                @else
                                    <span class="badge bg-danger">Đang sửa</span>
                                @endif
                            </td>
                            <td class="d-flex gap-1">
                                {{-- Khôi phục --}}
                                <form action="/admin/bikes/{{ $bike->id }}/restore" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-success">
                                        <i class="bi bi-arrow-counterclockwise"></i> Khôi phục
                                    </button>
                                </form>
                                {{-- Xóa hẳn --}}
                                <form action="/admin/bikes/{{ $bike->id }}/force" method="POST"
                                    onsubmit="return confirm('Xóa vĩnh viễn xe này?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="bi bi-x-circle"></i> Xóa hẳn
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Thùng rác trống</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $bikes->links() }}
        </form>

        {{-- Form restore-all / delete-all --}}
        <form id="allForm" method="POST" style="display:none">
            @csrf
            <input type="hidden" name="action" id="allAction">
        </form>

        @push('scripts')
            <script>
                document.getElementById('checkAll').addEventListener('change', function() {
                    document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = this.checked);
                });

                function bulkAction(action) {
                    const checked = document.querySelectorAll('input[name="ids[]"]:checked');
                    if (!checked.length) {
                        alert('Chưa chọn xe nào');
                        return;
                    }
                    if (!confirm('Xác nhận thực hiện?')) return;
                    document.getElementById('bulkAction').value = action;
                    document.getElementById('bulkForm').submit();
                }

                function bulkActionAll(action) {
                    if (!confirm('Xác nhận thực hiện tất cả?')) return;
                    const f = document.getElementById('allForm');
                    f.action = '/admin/bikes/bulk';
                    document.getElementById('allAction').value = action === 'restore-all' ? 'restore' : 'force-delete';
                    // Chọn tất cả ids
                    document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = true);
                    document.getElementById('bulkAction').value = document.getElementById('allAction').value;
                    document.getElementById('bulkForm').submit();
                }
            </script>
        @endpush

    </X-admin-layout>
