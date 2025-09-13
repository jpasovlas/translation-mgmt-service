<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTranslationRequest;
use App\Http\Requests\UpdateTranslationRequest;
use App\Models\Translation;
use App\Services\TranslationService;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    public function __construct(
        protected TranslationService $service
    ) {}

    /**
     * @OA\Get(
     *  path="/translations",
     *  summary="List all translations",
     *  tags={"Translations"},
     *  security={{"bearerAuth":{}}},
     * 
     *  @OA\Parameter(
     *      name="locale",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *          type="string"
     *      )
     *  ),
     *  @OA\Parameter(
     *      name="key",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *          type="string"
     *      )
     *  ),
     *  @OA\Parameter(
     *      name="content",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *          type="string"
     *      )
     *  ),
     *  @OA\Parameter(
     *      name="tags",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *          type="string"
     *      )
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="Successful operation"
     *  ),
     *  @OA\Response(
     *      response=401,
     *      description="Unauthenticated"
     *  )
     * )
     * 
     * @param Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $filters = $request->only(['locale','key','content','tags']);

        return response()->json($this->service->list($filters));
    }

    /**
     * @OA\Get(
     *  path="/translations/{id}",
     *  summary="Show a translation",
     *  tags={"Translations"},
     *  security={{"bearerAuth":{}}},
     * 
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *          type="integer"
     *      )
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="Successful operation"
     *  ),
     *  @OA\Response(
     *      response=401,
     *      description="Unauthenticated"
     *  )
     * )
     * 
     * @param int $id
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->service->get($id));
    }

    /**
     * @OA\Post(
     *  path="/translations",
     *  summary="Create a new translation",
     *  tags={"Translations"},
     *  security={{"bearerAuth":{}}},
     * 
     *  @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(
     *              property="locale",
     *              type="string",
     *              example={"en", "fr", "es"}
     *          ),
     *          @OA\Property(
     *              property="key",
     *              type="string"
     *          ),
     *          @OA\Property(
     *              property="content",
     *              type="string"
     *          ),
     *          @OA\Property(
     *              property="tags",
     *              type="string",
     *              example={"mobile", "web", "desktop"}
     *          )
     *      )
     *  ),
     *  @OA\Response(
     *      response=201,
     *      description="Translation created successfully"
     *  ),
     *  @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *  ),
     *  @OA\Response(
     *      response=401,
     *      description="Unauthenticated"
     *  )
     * )
     * 
     * @param StoreTranslationRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTranslationRequest $request): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            $this->service->createOrUpdate($request->validated()),
            201
        );
    }

    /**
     * @OA\Put(
     *  path="/translations/{id}",
     *  summary="Update a translation",
     *  tags={"Translations"},
     *  security={{"bearerAuth":{}}},
     * 
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(type="integer")
     *  ),
     *  @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(
     *              property="locale",
     *              type="string",
     *              enum={"en", "fr", "es"},
     *              example="en"
     *          ),
     *          @OA\Property(
     *              property="key",
     *              type="string",
     *              example="home.welcome"
     *          ),
     *          @OA\Property(
     *              property="content",
     *              type="string",
     *              example="Hello World"
     *          ),
     *          @OA\Property(
     *              property="tags",
     *              type="array",
     *              @OA\Items(type="string"),
     *              example={"mobile", "web", "desktop"}
     *          )
     *      )
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="Translation updated successfully"
     *  ),
     *  @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *  ),
     *  @OA\Response(
     *      response=401,
     *      description="Unauthenticated"
     *  )
     * )
     * 
     * @param UpdateTranslationRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
    */
    public function update(UpdateTranslationRequest $request, Translation $translation): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $data['id'] = $request->id;

        return response()->json(
            $this->service->update($translation, $data)
        );
    }

    /**
     * @OA\Delete(
     *  path="/translations/{id}",
     *  summary="Delete a translation",
     *  tags={"Translations"},
     *  security={{"bearerAuth":{}}},
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *          type="integer"
     *      )
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="Translation deleted successfully"
     *  ),
     *  @OA\Response(
     *      response=401,
     *      description="Unauthenticated"
     *  )
     * )
     * 
     * @param Request $request
     * @param Translation $translation
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Translation $translation): \Illuminate\Http\JsonResponse
    {
        $response = $this->service->delete($request->id, $translation);

        return response()->json(
            $response ? ['message' => 'deleted'] : ['message' => 'failed to delete id: ' . $request->id]
        );
    }
}
