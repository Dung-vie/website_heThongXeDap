<x-layout>
    <div class="container top-biker">
        <h2 class="blink-text py-5">Top Biker</h2>

        @if ($topBikers->isEmpty())
            <p class="text-muted text-center">Chưa có dữ liệu tháng trước</p>
        @else
            <div class="d-flex justify-content-center align-items-end gap-3 mb-4">
                {{-- Hạng 2 --}}
                @php $rank2 = $topBikers->firstWhere('rank', 2) @endphp
                @if ($rank2)
                    <div class="card text-center p-4" style="width:200px;">
                        <div style="font-size:2rem;">🥈</div>
                        <div class="fw-bold">{{ $rank2->user->name }}</div>
                        <small class="text-muted">***{{ substr($rank2->user->phone, -3) }}</small>
                        <div class="small mt-1">{{ $rank2->total_mins }} phút</div>
                        <div class="small text-muted">{{ $rank2->total_rentals }} lần</div>
                    </div>
                @endif

                {{-- Hạng 1 - to hơn --}}
                @php $rank1 = $topBikers->firstWhere('rank', 1) @endphp
                @if ($rank1)
                    <div class="card text-center p-3 border-warning" style="width:230px; transform: translateY(-20px);">
                        <div style="font-size:2.5rem;">🥇</div>
                        <div class="fw-bold fs-5">{{ $rank1->user->name }}</div>
                        <small class="text-muted">***{{ substr($rank1->user->phone, -3) }}</small>
                        <div class="small mt-1 fw-bold text-warning">{{ $rank1->total_mins }} phút</div>
                        <div class="small text-muted">{{ $rank1->total_rentals }} lần</div>
                    </div>
                @endif

                {{-- Hạng 3 --}}
                @php $rank3 = $topBikers->firstWhere('rank', 3) @endphp
                @if ($rank3)
                    <div class="card text-center p-3" style="width:200px;">
                        <div style="font-size:2rem;">🥉</div>
                        <div class="fw-bold">{{ $rank3->user->name }}</div>
                        <small class="text-muted">***{{ substr($rank3->user->phone, -3) }}</small>
                        <div class="small mt-1">{{ $rank3->total_mins }} phút</div>
                        <div class="small text-muted">{{ $rank3->total_rentals }} lần</div>
                    </div>
                @endif

            </div>

            {{-- TOP 4-10: dạng danh sách --}}
            <div class="list-group" style="max-width: 600px; margin: 0 auto;">
                @foreach ($topBikers->where('rank', '>', 3) as $biker)
                    <div class="list-group-item d-flex align-items-center gap-3">
                        <span class="fw-bold text-muted" style="width:30px;">#{{ $biker->rank }}</span>
                        <span class="flex-grow-1 fw-semibold">{{ $biker->user->name }}</span>
                        <span class="text-muted small">***{{ substr($biker->user->phone, -3) }}</span>
                        <span class="small">{{ $biker->total_mins }} phút</span>
                        <span class="small text-muted">{{ $biker->total_rentals }} lần</span>
                    </div>
                @endforeach
            </div>

        @endif
    </div>

    <div class="container let-bikego">
        <h2 class="blink-text py-5">Let's Bikego</h2>
        <div class="place row justify-content-center align-items-center g-2 text-center">
            <div class="col-md-6">
                <h4 class="card-title">Số xe hiện có</h4>
                <p class="card-text fw-bold fs-4">{{ $totalBikes }} xe</p>
            </div>
            <div class="col-md-6">
                <h4 class="card-title">Số trạm hiện có</h4>
                <p class="card-text fw-bold fs-4">{{ $totalStations }} trạm</p>
            </div>
        </div>
    </div>

    <div class="container">
        <h2 class="blink-text py-5">Top Stations</h2>
        <div class="d-flex align-items-center gap-2">

            <button class="btn btn-primary rounded-circle" onclick="stationPrev()">&#8249;</button>

            <div style="overflow:hidden; flex:1;">
                <div id="stationTrack" class="d-flex" style="transition: transform 0.4s ease;">
                    @foreach ($topStations as $station)
                        {{-- @dd($station)  --}}
                        <div style="flex: 0 0 25%; padding: 0 8px;">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h3 class="card-title fs-6" style="cursor:pointer">
                                        <a href="{{ route('stations.show', $station->id) }}" class="text-dark text-decoration-none">
                                            {{ $station->name }}
                                        </a>
                                    </h3>
                                    <div class="mb-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span
                                                class="{{ $i <= round($station->avg_rating ?? 0) ? 'text-warning' : 'text-muted' }}">★</span>
                                        @endfor
                                        <small
                                            class="text-muted">({{ number_format($station->avg_rating ?? 0, 1) }})</small>
                                    </div>
                                    @forelse($station->reviews as $review)
                                        <p class="small fst-italic text-muted border-start border-danger ps-2 mb-1">
                                            "{{ Str::limit($review->station_comment, 50) }}"
                                        </p>
                                    @empty
                                        <p class="small text-muted">Chưa có bình luận</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <button class="btn btn-primary rounded-circle" onclick="stationNext()">&#8250;</button>

        </div>
    </div>

    @push('scripts')
        <script>
            const track = document.getElementById('stationTrack');
            const TOTAL = track.children.length; // 10
            const SHOW = 4;
            const MAX = TOTAL - SHOW; // 6
            let current = 0;

            // Auto chạy mỗi 3 giây
            let timer = setInterval(autoPlay, 3000);

            function move(n) {
                current = Math.max(0, Math.min(n, MAX));
                track.style.transform = `translateX(-${current * 25}%)`;
            }

            function autoPlay() {
                current < MAX ? move(current + 1) : move(0);
            }

            function stationPrev() {
                move(current - 1);
                resetTimer();
            }

            function stationNext() {
                move(current + 1);
                resetTimer();
            }

            function resetTimer() {
                clearInterval(timer);
                timer = setInterval(autoPlay, 3000);
            }

            function loadStationDetail(id) {
                window.location.href = `/stations?open=${id}`;
            }
        </script>
    @endpush
</x-layout>
