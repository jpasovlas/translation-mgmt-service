<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{

    /**
     * Initialize response
     */
    protected $response = [
        'status' => false,
        'message' => ''
    ];

    /**
     * @OA\Post(
     *   path="/login",
     *   summary="Login user",
     *   tags={"Authentication"},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", example="john@example.com"),
     *       @OA\Property(property="password", type="string", example="password123")
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Successful login",
     *     @OA\JsonContent(
     *       @OA\Property(property="token", type="string", example="your-access-token")
     *     )
     *   ),
     *   @OA\Response(response=401, description="Invalid credentials")
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            $this->response['message'] = 'Invalid credentials';

            return response()->json($this->response, 401);
        }

        $user  = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;

        $this->response = [
            'status' => true,
            'data'   => [
                'user'  => $user,
                'token' => $token,
            ],
           'message' => 'Login successful'
        ];

        return response()->json($this->response);
    }

    /**
     * @OA\Post(
     *   path="/logout",
     *   summary="Logout user",
     *   tags={"Authentication"},
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="Successful logout"
     *   )
     * )
     * 
     * @param Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request) //: \Illuminate\Http\JsonResponse
    {
        // Delete only the current access token
        $request->user()->tokens()->delete();
        $this->response['message'] = 'Logged out successfully';

        return response()->json($this->response);
    }
}
