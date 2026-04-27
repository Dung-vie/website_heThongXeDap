<x-admin-layout>
<h4>{{ isset($station) ? 'Sửa trạm xe' : 'Thêm trạm xe' }}</h4>
<a href="/admin/stations" class="btn btn-sm btn-secondary mb-3">← Quay lại</a>

<form action="{{ isset($station) ? '/admin/stations/'.$station->id : '/admin/stations' }}"
      method="POST" class="card p-4 bg-white" style="max-width:560px">
    @csrf
    @if(isset($station)) @method('PUT') @endif

    <div class="mb-3">
        <label class="form-label">Tên trạm <span class="text-danger">*</span></label>
        <input type="text" name="name"
               class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $station->name ?? '') }}" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Địa chỉ <span class="text-danger">*</span></label>
        <input type="text" name="address"
               class="form-control @error('address') is-invalid @enderror"
               value="{{ old('address', $station->address ?? '') }}" required>
        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Mã phường (ward_code)</label>
        <input type="text" name="ward_code"
               class="form-control"
               value="{{ old('ward_code', $station->ward_code ?? '') }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Tổng số chỗ <span class="text-danger">*</span></label>
        <input type="number" name="slots" min="1"
               class="form-control @error('slots') is-invalid @enderror"
               value="{{ old('slots', $station->slots ?? '') }}" required>
        @error('slots') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Tình trạng <span class="text-danger">*</span></label>
        <select name="status" class="form-select" required>
            <option value="active"
                {{ old('status', $station->status ?? '') === 'active' ? 'selected' : '' }}>
                Đang hoạt động
            </option>
            <option value="maintenance"
                {{ old('status', $station->status ?? '') === 'maintenance' ? 'selected' : '' }}>
                Đang bảo trì
            </option>
        </select>
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-primary">
            {{ isset($station) ? 'Cập nhật' : 'Thêm trạm' }}
        </button>
        <a href="/admin/stations" class="btn btn-secondary">Hủy</a>
    </div>
</form>
</x-admin-layout>
