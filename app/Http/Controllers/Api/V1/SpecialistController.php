<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Specialist\StoreSpecialistRequest;
use App\Http\Requests\Specialist\UpdateSpecialistRequest;
use App\Http\Resources\SpecialistResource;
use App\Models\Specialist;
use Symfony\Component\HttpFoundation\Response;

class SpecialistController extends Controller
{
    public function __construct() {
        $this->authorizeResource(Specialist::class, 'specialist');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $specialists = Specialist::with('user')->paginate();

        return SpecialistResource::collection($specialists)
            ->response()->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSpecialistRequest $request)
    {
        $payload = $request->validated();

        $specialist = Specialist::create([
            ...$payload,
            'user_id' => auth()->user()->id
        ]);

        return (new SpecialistResource($specialist))
            ->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Specialist $specialist)
    {
        return (new SpecialistResource($specialist))
            ->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSpecialistRequest $request, Specialist $specialist)
    {
        $payload = $request->validated();

        $specialist->update($payload);

        return (new SpecialistResource($specialist))
            ->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Specialist $specialist)
    {
        $specialist->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
