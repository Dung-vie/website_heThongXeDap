<x-layout>
    <div class="container top-biker">
        <h2 class="blink-text py-5">Top Biker</h2>
        <div class="place"></div>
    </div>

    <div class="container let-bikego">
        <h2 class="blink-text py-5">Let's Bikego</h2>
        <div class="place row justify-content-center align-items-center g-2 text-center">
            <div class="col-md-6 ">
                <h4 class="card-title">Số xe hiện có</h4>
                <p class="card-text fw-bold fs-4"> {{ $totalBikes }} xe</p>
            </div>
            <div class="col-md-6">
                <h4 class="card-title">Số trạm hiện có</h4>
                <p class="card-text fw-bold fs-4"> {{ $totalStations }} trạm </p>
            </div>
        </div>

        <div class="container">
            <h2 class="blink-text py-5">Top Stations</h2>
            <div class="place top-station">
                <div id="carouselId" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" role="listbox">
                        <div class="carousel-item active">
                            <div class="row">
                                <div class="col-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h3 class="card-title">Title</h3>
                                            <p class="card-text">Text</p>
                                        </div>
                                    </div>
                                </div>
                                 <div class="col-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h3 class="card-title">Title</h3>
                                            <p class="card-text">Text</p>
                                        </div>
                                    </div>
                                </div>
                                 <div class="col-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h3 class="card-title">Title</h3>
                                            <p class="card-text">Text</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h3 class="card-title">Title</h3>
                                            <p class="card-text">Text</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="carousel-item ">
                            <div class="row">
                                <div class="col-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h3 class="card-title">Title</h3>
                                            <p class="card-text">Text</p>
                                        </div>
                                    </div>
                                </div>
                                 <div class="col-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h3 class="card-title">Title</h3>
                                            <p class="card-text">Text</p>
                                        </div>
                                    </div>
                                </div>
                                 <div class="col-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h3 class="card-title">Title</h3>
                                            <p class="card-text">Text</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h3 class="card-title">Title</h3>
                                            <p class="card-text">Text</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselId"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselId"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>


            </div>
        </div>

</x-layout>
