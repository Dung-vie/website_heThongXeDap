<x-layout>
    <h4 class= "fw-4 mt-4">
        <p class= "text-primary">Lịch sử</p>
    </h4>
        @if($rentals->isEmpty())
            <div class="alert alert-info">Bạn chưa có lịch sử thuê xe.</div>
        @else
            <div class="row">
                @foreach($rentals as $rental)
                    <div class="col-12 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="mb-1">
                                            <i class="bi bi-calendar-check text-success"></i>
                                            <strong>Bắt đầu:</strong> {{ $rental->rent_at->format('d/m/Y H:i') }}
                                        </p>
                                        <p class="mb-1">
                                            <i class="bi bi-calendar-x text-danger"></i>
                                            <strong>Trả xe:</strong> {{ $rental->return_at?->format('d/m/Y H:i') ?? '---' }}
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1">
                                            <i class="bi bi-bicycle text-primary"></i>
                                            <strong>Biển số:</strong> {{ $rental->bike->plate_number }}
                                        </p>
                                        <p class="mb-1">
                                            <i class="bi bi-geo-alt text-warning"></i>
                                            <strong>Lấy xe:</strong> {{ $rental->rentStation->name }}
                                        </p>
                                        <p class="mb-1">
                                            <i class="bi bi-geo-alt-fill text-success"></i>
                                            <strong>Trả xe:</strong> {{ $rental->returnStation?->name ?? '---' }}
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <p class="mb-1">
                                            <i class="bi bi-clock"></i>
                                            <strong>Thời gian:</strong> {{ $rental->total_mins ?? 0 }} phút
                                        </p>
                                        <p class="mb-0">
                                            <span class="badge bg-success fs-6">
                                                <i class="bi bi-cash"></i>
                                                {{ number_format($rental->total_amount ?? 0, 0, ',', '.') }} đ
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Phân trang --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $rentals->links() }}
            </div>
        @endif
</x-layout>
