<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostService
{
    /**
     * Save uploaded PDF file to storage and return its path.
     *
     * @return string
     */
    public function saveUploadedPdfToStorage(): string
    {
        // process uploaded file
        $fileNameWithExt = request()->file('pdf_file')->getClientOriginalName();
        $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
        $extension = request()->file('pdf_file')->getClientOriginalExtension();
        $fileNameToStore = Str::slug($fileName, '_') . '_' . time() . uniqid('', true) . '.' . $extension;

        // save to storage
        request()->file('pdf_file')->storeAs(Post::PDF_STORAGE_PATH, $fileNameToStore);

        // return path to file
        return $fileNameToStore;
    }

    /**
     * Delete pdf file from storage
     *
     * @param string $fileName
     */
    public function deletePdfFromStorage(Post $post): void
    {
        try {
            Storage::delete(Post::PDF_STORAGE_PATH . '/' . $post->pdfPost->pdf_path);
        } catch (\Exception $e) {
            Log::error('Error deleting file: ' . $e->getMessage());
        }
    }

    /**
     * Save Post's HTML snippet to storage.
     *
     * @param Post $post
     * @return void
     */
    public function saveHtmlSnippetToStorage(Post $post): void
    {
        $path = $post->getHtmlStoragePath();

        // save to storage
        file_put_contents(storage_path('app/') . $path, request()->input('html_snippet'));
    }
}
