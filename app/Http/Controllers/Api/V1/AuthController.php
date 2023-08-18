<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterClientRequest;
use App\Http\Requests\RegisterSpecialistRequest;
use App\Http\Resources\AuthUserResource;
use App\Models\Client;
use App\Models\Specialist;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('index');
    }


    /**
     * @return Response
     */
    public function index(): Response
    {
        $user = Auth::user();

        return (new AuthUserResource($user))
            ->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }

    /**
     * @param RegisterClientRequest $registerUserRequest
     * @return Response
     */
    public function registerAsClient(RegisterClientRequest $registerUserRequest): Response
    {
        $payload = $registerUserRequest->validated();

        $user = User::create($payload);
        $client = Client::create([
            ...$payload,
            'user_id' => $user->id
        ]);

        return (new AuthUserResource($user))
            ->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @param RegisterSpecialistRequest $registerUserRequest
     * @return Response
     */
    public function registerAsSpecialist(RegisterSpecialistRequest $registerUserRequest): Response
    {
        $payload = $registerUserRequest->validated();

        $user = User::create($payload);
        $specialist = Specialist::create([
            ...$payload,
            'user_id' => $user->id
        ]);

        return (new AuthUserResource($user))
            ->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @param LoginUserRequest $loginUserRequest
     * @return Response
     */
    public function login(LoginUserRequest $loginUserRequest): Response
    {
        $payload = $loginUserRequest->validated();

        if (Auth::attempt($payload)) {
            $user = Auth::user();

            return (new AuthUserResource($user))
                ->response()->setStatusCode(Response::HTTP_ACCEPTED);
        }

        return response()->json(['errors' => 'Cannot login with these params'], Response::HTTP_UNAUTHORIZED);
    }
}
