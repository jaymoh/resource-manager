<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'post_type',
    ];

    public const availableRelationships = [
        'pdfPost',
        'linkPost',
        'htmlPost',
    ];

    public const POST_TYPE_PDF = 'storePdf';
    public const POST_TYPE_LINK = 'storeLink';
    public const POST_TYPE_HTML = 'storeHtml';

    public const PDF_STORAGE_PATH = 'pdfs';
    public const HTML_STORAGE_PATH = 'htmls';

    /** Relationships */

    public function pdfPost(): HasOne
    {
        return $this->hasOne(PdfPost::class);
    }

    public function linkPost(): HasOne
    {
        return $this->hasOne(LinkPost::class);
    }

    public function htmlPost(): HasOne
    {
        return $this->hasOne(HtmlPost::class);
    }

    /** Helper Functions */

    public function getHtmlStoragePath(): string
    {
        return self::HTML_STORAGE_PATH . '/' . $this->id . '.html';
    }

    public function getPdfStoragePath(): string
    {
        return self::PDF_STORAGE_PATH . '/' . $this->pdfPost-> pdf_path;
    }
}
