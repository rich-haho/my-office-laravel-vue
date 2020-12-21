<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetTagRequest;
use App\Http\Resources\TagResource;
use Illuminate\Http\Request;
use App\Models\Tag;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TagController extends Controller
{
    /**
     * Get all product tags.
     * @param GetTagRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(GetTagRequest $request)
    {

        $tags = Tag::orderBy('name')->get();
        return TagResource::collection($tags);
    }
}
