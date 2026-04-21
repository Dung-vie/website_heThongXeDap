<?php

namespace App\Console\Commands;

use App\Models\TopBiker;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SummarizeTopBikers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:summarize-top-bikers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Xác định tháng cần tính toán (Ví dụ: tính cho tháng hiện tại)
        $month = now()->month;
        $year  = now()->year;

        // 1. Xóa dữ liệu cũ của đúng tháng/năm đó để tránh trùng lặp
        TopBiker::where('month', $month)->where('year', $year)->delete();

        // 2. Query lấy dữ liệu từ bảng rentals
        $results = DB::table('rentals')
            ->join('users', 'rentals.user_id', '=', 'users.id')
            ->selectRaw('
                rentals.user_id,
                SUM(rentals.total_mins) as total_mins_sum,
                COUNT(*) as total_rentals_count,
                users.created_at as user_created_at
            ')
            ->whereYear('rentals.rent_at', $year)
            ->whereMonth('rentals.rent_at', $month)
            ->where('rentals.status', 'return')
            ->groupBy('rentals.user_id', 'users.created_at')
            ->orderByDesc('total_mins_sum')
            ->orderByDesc('total_rentals_count')
            ->orderBy('users.created_at', 'asc')
            ->limit(10)
            ->get();

        // 3. Lưu vào bảng top_bikers
        foreach ($results as $index => $row) {
            TopBiker::create([
                'user_id'       => $row->user_id,
                'month'         => $month,
                'year'          => $year,
                'rank'          => $index + 1,
                'total_mins'    => $row->total_mins_sum ?? 0, // Sửa thành total_mins cho khớp Schema
                'total_rentals' => $row->total_rentals_count,
            ]);
        }

        $this->info("Đã cập nhật Top Biker cho tháng $month/$year thành công!");
    }
}
