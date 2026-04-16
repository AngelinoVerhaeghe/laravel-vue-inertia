<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable(['name', 'disk', 'path'])]
class BlogImage extends Model
{
    use HasFactory;

    public function url(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }
}
