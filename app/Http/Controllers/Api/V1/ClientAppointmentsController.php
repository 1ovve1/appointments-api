<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\Client;
use Symfony\Component\HttpFoundation\Response;

class ClientAppointmentsController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Client $client)
    {
        $appointments = Appointment::where('client_id', $client->id)->paginate();

        return AppointmentResource::collection($appointments)
            ->response()->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppointmentRequest $request, Client $client)
    {
        $payload = $request->validated();

        $payload['client_id'] = $client->id;

        $appointment = Appointment::create($payload);

        return (new AppointmentResource($appointment))
            ->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client, Appointment $appointment)
    {
        if ($appointment->client_id === $client->id) {
            return (new AppointmentResource($appointment))
                ->response()->setStatusCode(Response::HTTP_ACCEPTED);
        } else {
            return response()->json(['errors' => ['message' => 'Forbidden']], Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client, Appointment $appointment)
    {
        if ($appointment->client_id === $client->id) {
            $appointment->delete();

            return response()->json([], Response::HTTP_NO_CONTENT);
        } else {
            return response()->json(['errors' => ['message' => 'Forbidden']], Response::HTTP_FORBIDDEN);
        }
    }
}
