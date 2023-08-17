<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\Specialist;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SpecialistAppointmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Specialist $specialist)
    {
        $appointments = Appointment::where('specialist_id', $specialist->id)->paginate();

        return AppointmentResource::collection($appointments)
            ->response()->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(Specialist $specialist, Appointment $appointment)
    {
        if ($appointment->specialist_id === $specialist->id) {
            return (new AppointmentResource($appointment))
                ->response()->setStatusCode(Response::HTTP_ACCEPTED);
        } else {
            return response()->json(['errors' => ['message' => 'Forbidden']], Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Specialist $specialist, Appointment $appointment)
    {
        if ($appointment->specialist_id === $specialist->id) {
            $appointment->delete();

            return response()->json([], Response::HTTP_NO_CONTENT);
        } else {
            return response()->json(['errors' => ['message' => 'Forbidden']], Response::HTTP_FORBIDDEN);
        }
    }
}
