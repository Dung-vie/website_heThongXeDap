<x-layout>
   <div class="col-md-7">
        <h4 class= "fw-4 mt-4">
            <p class= "text-primary">Trả xe</p>
        </h4>

                    {{-- Thông tin xe đang thuê --}}
                    <div class="card mb-4 border-warning shadow-sm">
                        <div class="card-header bg-warning text-dark fw-bold">
                            <i class="bi bi-info-circle"></i> Thông tin xe đang thuê
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li><i class="bi bi-calendar"></i> <strong>Thời gian bắt đầu:</strong>
                                    {{ $activeRental->rented_at->format('H:i d/m/Y ') }}</li>
                                <li><i class="bi bi-bicycle"></i> <strong>Biển số xe:</strong>
                                    {{ $activeRental->bike->plate_number }}</li>
                                <li><i class="bi bi-geo-alt"></i> <strong>Trạm lấy xe:</strong>
                                    {{ $activeRental->pickupStation->name }}</li>
                                <li><i class="bi bi-clock"></i> <strong>Số phút thuê tới hiện tại:</strong>
                                    <span id="mins-count">...</span> phút</li>
                                <li><i class="bi bi-cash"></i> <strong>Số tiền thuê:</strong>
                                    <span id="amount-count">...</span> đ</li>
                            </ul>
                        </div>
                    </div>
        
                    {{-- Form chọn trạm trả --}}
                    @error('msg') <div class="alert alert-danger">{{ $message }}</div> @enderror
        
                    <form action="{{ route('rental.return') }}" method="POST">
                        @csrf
                        <h6 class="fw-bold mb-3">Chọn trạm trả xe:</h6>
        
                        <div class="mb-3">
                            <label class="form-label">Chọn Phường</label>
                            <select id="ward-select" class="form-select">
                                <option value="">-- Chọn Phường --</option>
                            </select>
                        </div>
        
                        <div class="mb-4">
                            <label class="form-label">Chọn Trạm</label>
                            <select id="station-sel" name="return_station_id" class="form-select" disabled>
                                <option value="">-- Chọn Trạm --</option>
                            </select>
                        </div>
        
                        <button type="submit" class="btn btn-success w-100 btn-lg">
                            <i class="bi bi-check-circle"></i> Trả xe và thanh toán
                        </button>
                    </form>
                </div>
     
        
        <script>
        const rentedAt = new Date('{{ $activeRental->rented_at->toISOString() }}');
        const price    = {{ $activeRental->price }};
        
        function updateClock() {
            const mins = Math.floor((Date.now() - rentedAt) / 60000);
            document.getElementById('mins-count').textContent   = mins;
            document.getElementById('amount-count').textContent = (mins * price).toLocaleString('vi-VN');
        }
        updateClock();
        setInterval(updateClock, 10000);
        
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

 
        
        // Chọn phường → load trạm còn chỗ
        document.getElementById('ward-select').addEventListener('change', function () {

            const wardCode = this.value; 

            console.log("ward =", wardCode);

            if (!wardCode) return;

            fetch('/api/stations-for-return?ward_code=' + wardCode)
                .then(r => r.json())
                .then(res => {
                    const stations = res.data;

                    stations.forEach(s => {
                        const o = document.createElement('option');
                        o.value = s.id;
                        o.textContent = s.name;
                        document.getElementById('station-sel').appendChild(o);
                    });

                    document.getElementById('station-sel').disabled = false;
                });
            });
        </script>
</x-layout>