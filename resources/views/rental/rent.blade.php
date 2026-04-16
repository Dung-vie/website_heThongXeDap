<x-layout>
    <div class="row justify-content-center align-items-center g-2">
        <div class="col-md-7">
        <h4 class= "fw-4 mt-4">
            <p class= "text-primary text-center">Thuê xe</p>
        </h4>

        @if ($activeRental)
            {{-- Đang có xe chưa trả → hiển thị thông tin --}}
            <div class="alert alert-warning">
                <h5><i class="bi bi-exclamation-triangle"></i> Bạn đang có xe chưa trả</h5>
                <ul class="mb-0">
                    <li><strong>Bắt đầu thuê:</strong> {{ $activeRental->rented_at->format('H:i d/m/Y') }}</li>
                    <li><strong>Biển số xe:</strong> {{ $activeRental->bike->plate_number }}</li>
                    <li><strong>Trạm lấy xe:</strong> {{ $activeRental->pickupStation->name }}</li>
                    <li><strong>Số phút thuê:</strong> <span id="mins-count">...</span> phút</li>
                    <li><strong>Số tiền tạm tính:</strong> <span id="amount-count">...</span> đ</li>
                </ul>
            </div>
            <a href="{{ route('rental.returnForm') }}" class="btn btn-warning">
                <i class="bi bi-arrow-return-left"></i> Đến trang trả xe
            </a>
        @else
            {{-- Form thuê xe --}}
            @error('msg')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <form action="{{ route('rental.rent') }}" method="POST">
                @csrf

                {{-- Chọn phường --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Chọn Phường</label>
                    <select id="ward-select" class="form-select">
                        <option value="">-- Chọn Phường --</option>
                    </select>
                </div>

                {{-- Chọn trạm --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Chọn Trạm xe</label>
                    <select id="station-sel" name="pickup_station_id" class="form-select" disabled>
                        <option value="">-- Chọn Trạm --</option>
                    </select>
                </div>

                {{-- Chọn biển số --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">Chọn biển số xe</label>
                    <select id="bike-sel" name="bike_id" class="form-select" disabled>
                        <option value="">-- Chọn xe --</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100 btn-lg">
                    <i class="bi bi-bicycle"></i> Thuê xe
                </button>
            </form>
        @endif
    </div>
    </div>



    <script>
        // Load phường
        fetch('/json/communes.json')
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById('ward-select');
                select.innerHTML = '<option disabled selected>Chọn phường</option>';

                const communes = data.communes || data;
                communes.forEach(c => {
                    const option = document.createElement('option');
                    option.value = c.code;
                    option.textContent = c.name;
                    select.appendChild(option);
                });
            });


        // Chọn phường → load trạm
        document.getElementById('ward-select')?.addEventListener('change', function() {
            const staSel = document.getElementById('station-sel');
            const bikeSel = document.getElementById('bike-sel');
            staSel.innerHTML = '<option value="">-- Chọn Trạm --</option>';
            bikeSel.innerHTML = '<option value="">-- Chọn xe --</option>';
            staSel.disabled = true;
            bikeSel.disabled = true;

            if (!this.value) return;

            fetch('/api/stations-for-rental?ward_code=' + this.value)
                .then(r => r.json())
                .then(stations => {
                    stations.forEach(s => {
                        const o = document.createElement('option');
                        o.value = s.id;
                        o.textContent = s.name;
                        staSel.appendChild(o);
                    });
                    staSel.disabled = false;
                });
        });

        // Chọn trạm → load biển số
        document.getElementById('station-sel')?.addEventListener('change', function() {
            const bikeSel = document.getElementById('bike-sel');
            bikeSel.innerHTML = '<option value="">-- Chọn xe --</option>';
            bikeSel.disabled = true;

            if (!this.value) return;

            fetch('/api/bikes-in-station/' + this.value)
                .then(r => r.json())
                .then(bikes => {
                    bikes.forEach(b => {
                        const o = document.createElement('option');
                        o.value = b.id;
                        o.textContent = b.plate_number;
                        bikeSel.appendChild(o);
                    });
                    bikeSel.disabled = false;
                });
        });

        // Đồng hồ đếm thời gian thuê
        @if ($activeRental ?? false)
            const rentedAt = new Date('{{ $activeRental->rented_at->toISOString() }}');
            const price = {{ $activeRental->price }};

            function updateClock() {
                const mins = Math.floor((Date.now() - rentedAt) / 60000);
                document.getElementById('mins-count').textContent = mins;
                document.getElementById('amount-count').textContent = (mins * price).toLocaleString('vi-VN');
            }
            updateClock();
            setInterval(updateClock, 10000); // cập nhật mỗi 10 giây
        @endif
    </script>
</x-layout>
