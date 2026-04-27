<x-admin-layout>

<h4>{{ isset($bike) ? 'Sửa xe' : 'Thêm xe' }}</h4>

<form action="{{ isset($bike) ? '/admin/bikes/'.$bike->id : '/admin/bikes' }}" method="POST"
      class="card p-4 bg-white" style="max-width:500px">
    @csrf
    @if(isset($bike)) @method('PUT') @endif

    <div class="mb-3">
        <label class="form-label">Biển số xe <span class="text-danger">*</span></label>
        <input type="text" name="plate_number" class="form-control @error('plate_number') is-invalid @enderror"
               value="{{ old('plate_number', $bike->plate_number ?? '') }}" required>
        @error('plate_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Vị trí (Trạm) <span class="text-danger">*</span></label>
        <select name="station_id" class="form-select" required>
            <option value="">-- Chọn trạm --</option>
            @foreach($stations as $station)
                <option value="{{ $station->id }}"
                    {{ old('station_id', $bike->station_id ?? '') == $station->id ? 'selected' : '' }}>
                    {{ $station->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Tình trạng <span class="text-danger">*</span></label>
        <select name="status" class="form-select" required>
            <option value="normal"  {{ old('status', $bike->status ?? '') === 'normal'  ? 'selected' : '' }}>Bình thường</option>
            <option value="repair"  {{ old('status', $bike->status ?? '') === 'repair'  ? 'selected' : '' }}>Đang sửa</option>
        </select>
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-primary">{{ isset($bike) ? 'Cập nhật' : 'Thêm xe' }}</button>
        <a href="/admin/bikes" class="btn btn-secondary">Hủy</a>
    </div>
</form>

</x-admin-layout>
