<?php

namespace App\Http\Controllers;

use App\Http\Resources\LocaleResource;
use App\Models\Locale;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LocaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function index()
    {
        $locales = Locale::get();
        return LocaleResource::collection($locales);
    }
}
