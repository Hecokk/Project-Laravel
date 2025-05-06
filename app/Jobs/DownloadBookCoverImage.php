<?php

namespace App\Jobs;

use App\Models\Book;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class DownloadBookCoverImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * ID buku yang gambarnya akan diunduh
     */
    protected $bookId;

    /**
     * URL gambar yang akan diunduh
     */
    protected $imageUrl;

    /**
     * Jumlah maksimal percobaan
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(int $bookId, string $imageUrl)
    {
        $this->bookId = $bookId;
        $this->imageUrl = $imageUrl;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Pastikan URL valid
        if (empty($this->imageUrl)) {
            Log::warning("URL gambar kosong untuk buku ID: {$this->bookId}");
            return;
        }

        try {
            // Ambil data buku
            $book = Book::find($this->bookId);
            if (!$book) {
                Log::warning("Buku dengan ID {$this->bookId} tidak ditemukan");
                return;
            }

            // Unduh gambar
            $response = Http::get($this->imageUrl);
            if (!$response->successful()) {
                Log::error("Gagal mengunduh gambar: {$response->status()} - {$this->imageUrl}");
                return;
            }

            // Inisialisasi image manager
            $manager = new ImageManager(new Driver());
            $image = $manager->read($response->body());

            // Generate nama file unik
            $filenameBase = 'covers/' . Str::slug($book->title) . '-' . Str::random(10);
            $extension = pathinfo(parse_url($this->imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';

            // Buat versi gambar dengan berbagai ukuran
            $sizes = [
                'thumbnail' => [200, 300],  // width, height
                'medium' => [400, 600],
                'large' => [600, 900],
            ];

            $paths = [];
            foreach ($sizes as $size => [$width, $height]) {
                $filename = "{$filenameBase}-{$size}.{$extension}";
                $resizedImage = $image->resize($width, $height, function ($constraint) {
                    // Mempertahankan rasio aspek dan mencegah pembesaran jika gambar lebih kecil
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                // Menyimpan gambar ke storage dengan kualitas 80%
                Storage::disk('public')->put($filename, $resizedImage->encodeByExtension($extension, 80));
                $paths[$size] = Storage::url($filename);
            }

            // Update path gambar di database
            $book->cover_image_path = $paths['medium']; // Default untuk tampilan
            $book->save();

            // Tambahkan meta data gambar jika diperlukan (dapat diimplementasikan nanti)

            Log::info("Berhasil mengunduh dan menyimpan sampul buku: {$book->title}");
        } catch (\Exception $e) {
            Log::error("Pengunduhan gambar sampul buku gagal: " . $e->getMessage());
            throw $e;
        }
    }
}
