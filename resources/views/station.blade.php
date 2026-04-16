<x-layout>
    <div class="container py-4">
        <h4 class="fw-bold mb-4">
            <i class="bi bi-geo-alt-fill text-primary"></i> Trạm xe
        </h4>

        {{-- Dropdown chọn phường --}}
        <div class="row justify-content-center mb-4">
            <div class="col-md-6">
                <select id="ward-select" class="form-select form-select-lg shadow-sm">
                    <option disabled selected>Chọn phường</option>
                </select>
            </div>
        </div>

        {{-- Danh sách trạm (AJAX render) --}}
        <div id="station-list"></div>
    </div>
</x-layout>


<script>
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

// Khi chọn phường → AJAX load trạm
document.getElementById('ward-select').addEventListener('change', function () {
    const wardCode = this.value;
    const list     = document.getElementById('station-list');

    if (!wardCode) { list.innerHTML = ''; return; }

    // Hiện spinner
    list.innerHTML = `
        <div class="text-center py-3">
            <div class="spinner-border text-primary"></div>
        </div>`;

    fetch(`/api/stations?ward_code=${wardCode}`)
        .then(r => r.json())
        .then(stations => renderStations(stations));
});

// Render danh sách trạm dạng accordion
function renderStations(stations) {
    const el = document.getElementById('station-list');

    if (!stations.length) {
        el.innerHTML = '<div class="alert alert-info">Không có trạm xe ở phường này.</div>';
        return;
    }

    el.innerHTML = stations.map(s => {
        // Badge trạng thái trạm
        const stationBadge = s.status === 'active'
            ? '<span class="badge bg-success"> Hoạt động</span>'
            : '<span class="badge bg-warning text-dark"> Bảo trì</span>';

        // Màu số xe: >= 50% tổng chỗ → xanh, ngược lại → đỏ
        const bikeColor = s.current_bikes >= s.total_slots * 0.5
            ? 'text-success' : 'text-danger';

        // Màu chỗ trống: >= 50% tổng chỗ → xanh, ngược lại → đỏ
        const slotColor = s.empty_slots >= s.total_slots * 0.5
            ? 'text-success' : 'text-danger';

        return `
        <div class="card mb-2 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center"
                 style="cursor:pointer"
                 onclick="toggleDetail(${s.id}, this)">
                <div>
                    ${stationBadge}
                    <strong class="ms-2">${s.name}</strong>
                    <span class="ms-2">
                        (<span class="${bikeColor}"> ${s.current_bikes} xe</span>
                        –
                        <span class="${slotColor}">${s.empty_slots} chỗ trống</span>)
                    </span>
                </div>
                <i class="bi bi-chevron-down toggle-icon"></i>
            </div>
            <div id="detail-${s.id}" class="card-body" style="display:none"></div>
        </div>`;
    }).join('');
}

// Lưu state trang bình luận từng trạm
const pageState = {};

function toggleDetail(id, header) {
    const detail = document.getElementById('detail-' + id);
    const icon   = header.querySelector('.toggle-icon');

    if (detail.style.display === 'none') {
        detail.style.display = 'block';
        icon.className = 'bi bi-chevron-up toggle-icon';
        // Chỉ load lần đầu
        if (!pageState[id]) loadDetail(id, 1);
    } else {
        detail.style.display = 'none';
        icon.className = 'bi bi-chevron-down toggle-icon';
    }
}

function loadDetail(id, page) {
    fetch(`/api/stations/${id}?page=${page}`)
        .then(r => r.json())
        .then(data => {
            const el = document.getElementById('detail-' + id);

            // Chỉ render khung HTML lần đầu (page 1)
            if (page === 1) {
                const emptyColor = data.empty_slots >= data.total_slots * 0.5
                    ? 'text-success' : 'text-danger';
                const bikeColor  = data.current_bikes >= data.total_slots * 0.5
                    ? 'text-success' : 'text-danger';

                el.innerHTML = `
                    <p> <strong>Địa chỉ:</strong> ${data.address}</p>
                    <div class="row mb-3 text-center">
                        <div class="col">
                            Tổng chỗ: <strong>${data.total_slots}</strong>
                        </div>
                        <div class="col ${emptyColor}">
                            Chỗ trống: <strong>${data.empty_slots}</strong>
                        </div>
                        <div class="col ${bikeColor}">
                            Xe hiện có: <strong>${data.current_bikes}</strong>
                        </div>
                    </div>
                    <hr>
                    <h6> Đánh giá gần đây</h6>
                    <div id="reviews-${id}"></div>
                    <button id="load-more-${id}"
                        class="btn btn-sm btn-outline-secondary mt-2"
                        onclick="loadDetail(${id}, ${data.next_page})"
                        ${data.has_more ? '' : 'style="display:none"'}>
                        Tải thêm
                    </button>`;
            }

            // Append reviews vào div
            const reviewsEl = document.getElementById('reviews-' + id);

            if (!data.reviews.length && page === 1) {
                reviewsEl.innerHTML = '<p class="text-muted">Chưa có đánh giá.</p>';
            }

            data.reviews.forEach(r => {
                const div = document.createElement('div');
                div.className = 'border-top pt-2 mt-2';
                div.innerHTML = `
                    <strong>${r.user_name}</strong>
                    ${''.repeat(r.station_rating)}${'☆'.repeat(5 - r.station_rating)}
                    <p class="mb-0 text-muted small">
                        ${r.station_comment || '(Không có bình luận)'}
                    </p>`;
                reviewsEl.appendChild(div);
            });

            // Cập nhật nút tải thêm
            const btn = document.getElementById('load-more-' + id);
            if (btn) {
                if (!data.has_more) {
                    btn.style.display = 'none';
                } else {
                    btn.setAttribute('onclick', `loadDetail(${id}, ${data.next_page})`);
                }
            }

            pageState[id] = page;
        });
}
</script>
