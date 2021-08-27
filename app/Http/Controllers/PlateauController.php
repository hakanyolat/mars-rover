<?php

namespace App\Http\Controllers;

use App\Services\PlateauService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PlateauController extends Controller
{
    private PlateauService $plateauService;

    /**
     * @param PlateauService $plateauService
     */
    public function __construct(PlateauService $plateauService)
    {
        $this->plateauService = $plateauService;
    }

    /**
     * @OA\Get(
     *     path="/api/v2/plateaus",
     *     tags={"plateau"},
     *     @OA\Response(
     *         response="200",
     *         description="Returns all plateaus",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     type="array",
     *                     @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              description="The plateau id",
     *                              example="1",
     *                          ),
     *                          @OA\Property(
     *                              property="created_at",
     *                              type="string",
     *                              description="Created at",
     *                              example="2021-08-26T18:41:26.000000Z",
     *                          ),
     *                          @OA\Property(
     *                              property="updated_at",
     *                              type="string",
     *                              description="Updated at",
     *                              example="2021-08-26T18:41:26.000000Z",
     *                          ),
     *                          @OA\Property(
     *                              property="width",
     *                              type="integer",
     *                              description="Plateau width",
     *                              example="50",
     *                          ),
     *                          @OA\Property(
     *                              property="height",
     *                              type="integer",
     *                              description="Plateau height",
     *                              example="50",
     *                          ),
     *                     )
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Returns error",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                      @OA\Property(
     *                          property="error",
     *                          type="string",
     *                          description="The error description"
     *                      ),
     *                 )
     *             )
     *         }
     *     )
     * )
     */
    public function index() {
        try {
            return response()->json($this->plateauService->getAll(), Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v2/plateaus/{id}",
     *     tags={"plateau"},
     *     @OA\Response(
     *         response="200",
     *         description="Returns plateau",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                      @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          description="The plateau id",
     *                          example="1",
     *                      ),
     *                      @OA\Property(
     *                          property="created_at",
     *                          type="string",
     *                          description="Created at",
     *                          example="2021-08-26T18:41:26.000000Z",
     *                      ),
     *                      @OA\Property(
     *                          property="updated_at",
     *                          type="string",
     *                          description="Updated at",
     *                          example="2021-08-26T18:41:26.000000Z",
     *                      ),
     *                      @OA\Property(
     *                          property="width",
     *                          type="integer",
     *                          description="Plateau width",
     *                          example="50",
     *                      ),
     *                      @OA\Property(
     *                          property="height",
     *                          type="integer",
     *                          description="Plateau height",
     *                          example="50",
     *                      ),
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Resource not found",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                      @OA\Property(
     *                          property="error",
     *                          type="string",
     *                          description="The error description"
     *                      ),
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Returns error",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                      @OA\Property(
     *                          property="error",
     *                          type="string",
     *                          description="The error description"
     *                      ),
     *                 )
     *             )
     *         }
     *     )
     * )
     */
    public function show(int $id) {
        try {
            $plateau = $this->plateauService->get($id);
            return response()->json($plateau, 200);
        } catch (NotFoundHttpException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v2/plateaus",
     *     tags={"plateau"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="x",
     *                      type="integer",
     *                      description="The plateau width",
     *                      example="50"
     *                  ),
     *                  @OA\Property(
     *                      property="y",
     *                      type="integer",
     *                      description="The plateau height",
     *                      example="50"
     *                  ),
     *           )
     *       )
     *   ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns plateau",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                      @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          description="The plateau id",
     *                          example="1",
     *                      ),
     *                      @OA\Property(
     *                          property="created_at",
     *                          type="string",
     *                          description="Created at",
     *                          example="2021-08-26T18:41:26.000000Z",
     *                      ),
     *                      @OA\Property(
     *                          property="updated_at",
     *                          type="string",
     *                          description="Updated at",
     *                          example="2021-08-26T18:41:26.000000Z",
     *                      ),
     *                      @OA\Property(
     *                          property="width",
     *                          type="integer",
     *                          description="Plateau width",
     *                          example="50",
     *                      ),
     *                      @OA\Property(
     *                          property="height",
     *                          type="integer",
     *                          description="Plateau height",
     *                          example="50",
     *                      ),
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid request body",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                      @OA\Property(
     *                          property="error",
     *                          type="string",
     *                          description="The error description"
     *                      ),
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Resource not found",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                      @OA\Property(
     *                          property="error",
     *                          type="string",
     *                          description="The error description"
     *                      ),
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Returns error",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                      @OA\Property(
     *                          property="error",
     *                          type="string",
     *                          description="The error description"
     *                      ),
     *                 )
     *             )
     *         }
     *     )
     * )
     */
    public function store(Request $request) {
        try {
            $data = $request->only(['x', 'y']);
            $plateau = $this->plateauService->create($data);
            return response()->json($plateau, Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (BadRequestException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v2/plateaus/{id}",
     *     tags={"plateau"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="x",
     *                      type="integer",
     *                      description="The plateau width",
     *                      example="50"
     *                  ),
     *                  @OA\Property(
     *                      property="y",
     *                      type="integer",
     *                      description="The plateau height",
     *                      example="50"
     *                  ),
     *           )
     *       )
     *   ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns plateau",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                      @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          description="The plateau id",
     *                          example="1",
     *                      ),
     *                      @OA\Property(
     *                          property="created_at",
     *                          type="string",
     *                          description="Created at",
     *                          example="2021-08-26T18:41:26.000000Z",
     *                      ),
     *                      @OA\Property(
     *                          property="updated_at",
     *                          type="string",
     *                          description="Updated at",
     *                          example="2021-08-26T18:41:26.000000Z",
     *                      ),
     *                      @OA\Property(
     *                          property="width",
     *                          type="integer",
     *                          description="Plateau width",
     *                          example="50",
     *                      ),
     *                      @OA\Property(
     *                          property="height",
     *                          type="integer",
     *                          description="Plateau height",
     *                          example="50",
     *                      ),
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid request body",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                      @OA\Property(
     *                          property="error",
     *                          type="string",
     *                          description="The error description"
     *                      ),
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Resource not found",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                      @OA\Property(
     *                          property="error",
     *                          type="string",
     *                          description="The error description"
     *                      ),
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Returns error",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                      @OA\Property(
     *                          property="error",
     *                          type="string",
     *                          description="The error description"
     *                      ),
     *                 )
     *             )
     *         }
     *     )
     * )
     */
    public function update(Request $request, int $id) {
        try {
            $data = $request->only(['x', 'y']);
            return response()->json($this->plateauService->update($id, $data), Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (BadRequestException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v2/plateaus/{id}",
     *     tags={"plateau"},
     *     @OA\Response(
     *         response="200",
     *         description="Deletes resource",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                      @OA\Property(
     *                          property="success",
     *                          type="boolean",
     *                          description="The result",
     *                          example="true",
     *                      ),
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Resource not found",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                      @OA\Property(
     *                          property="error",
     *                          type="string",
     *                          description="The error description"
     *                      ),
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Returns error",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                      @OA\Property(
     *                          property="error",
     *                          type="string",
     *                          description="The error description"
     *                      ),
     *                 )
     *             )
     *         }
     *     )
     * )
     */
    public function destroy(Request $request, int $id) {
        try {
            $this->plateauService->delete($id);
            return response()->json(['success' => true], Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
