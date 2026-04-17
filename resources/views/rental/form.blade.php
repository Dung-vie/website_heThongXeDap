<x-layout>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-star-fill"></i> Đánh giá xe & trạm</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('review.store', $rental->id) }}" method="POST">
                        @csrf

                        {{-- Đánh giá xe --}}
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="bi bi-bicycle"></i> Xe: {{ $rental->bike->plate_number }}
                        </h6>

                        <div class="mb-3">
                            <label class="form-label">Điểm đánh giá xe (1–5)</label>
                            <div class="d-flex gap-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <div class="form-check">
                                              <input class="form-check-input" type="radio"
                                               name="bike_rating" value="{{ $i }}"
                                               id="bike_rating_{{ $i }}" required>
                                        <label class="form-check-label" for="bike_rating_{{ $i }}">
                                            {{ $i }}⭐
                                        </label>
                                    </div>
                                @endfor
                            </div>
                            @error('bike_rating') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Bình luận về xe (tối đa 200 ký tự)</label>
                            <textarea name="bike_comment" class="form-control" rows="2"
                                      maxlength="200" placeholder="Xe sạch sẽ, dễ đi..."></textarea>
                        </div>

                        <hr>

                        {{-- Đánh giá trạm --}}
                        <h6 class="fw-bold text-success mb-3">
                            <i class="bi bi-geo-alt"></i> Trạm: {{ $rental->returnStation->name }}
                        </h6>

                        <div class="mb-3">
                            <label class="form-label">Điểm đánh giá trạm (1–5)</label>
                            <div class="d-flex gap-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                               name="station_rating" value="{{ $i }}"
                                               id="station_rating_{{ $i }}" required>
                                        <label class="form-check-label" for="station_rating_{{ $i }}">
                                            {{ $i }}⭐
                                        </label>
                                    </div>
                                @endfor
                            </div>
                            @error('station_rating') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Bình luận về trạm (tối đa 200 ký tự)</label>
                            <textarea name="station_comment" class="form-control" rows="2"
                                      maxlength="200" placeholder="Trạm rộng rãi, dễ tìm..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                            <i class="bi bi-send"></i> Gửi đánh giá
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>