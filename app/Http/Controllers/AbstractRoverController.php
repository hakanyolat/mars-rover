<?php

namespace App\Http\Controllers;

use App\Services\AbstractRoverService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class AbstractRoverController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v2/rovers",
     *     tags={"rover"},
     *     @OA\Response(
     *         response="200",
     *         description="Returns all rovers",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     type="array",
     *                     @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              description="The rover id",
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
     *                              property="x",
     *                              type="integer",
     *                              description="Rover x coordinate",
     *                              example="50",
     *                          ),
     *                          @OA\Property(
     *                              property="y",
     *                              type="integer",
     *                              description="Rover y coordinate",
     *                              example="50",
     *                          ),
     *                          @OA\Property(
     *                              property="plateau_id",
     *                              type="integer",
     *                              description="Plateau id's of rover",
     *                              example="1",
     *                          ),
     *                          @OA\Property(
     *                              property="direction",
     *                              type="string",
     *                              description="Rover direction",
     *                              example="N",
     *                          ),
     *                          @OA\Property(
     *                              property="state",
     *                              type="string",
     *                              description="Rover state",
     *                              example="READY",
     *                          ),
     *                          @OA\Property(
     *                              property="queue",
     *                              type="string",
     *                              description="Rover command queue",
     *                              example="RLM",
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
            return response()->json($this->getRoverService()->getAll(), Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v2/rovers/{id}",
     *     tags={"rover"},
     *     @OA\Response(
     *         response="200",
     *         description="Returns rover",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              description="The rover id",
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
     *                              property="x",
     *                              type="integer",
     *                              description="Rover x coordinate",
     *                              example="50",
     *                          ),
     *                          @OA\Property(
     *                              property="y",
     *                              type="integer",
     *                              description="Rover y coordinate",
     *                              example="50",
     *                          ),
     *                          @OA\Property(
     *                              property="plateau_id",
     *                              type="integer",
     *                              description="Plateau id's of rover",
     *                              example="1",
     *                          ),
     *                          @OA\Property(
     *                              property="direction",
     *                              type="string",
     *                              description="Rover direction",
     *                              example="N",
     *                          ),
     *                          @OA\Property(
     *                              property="state",
     *                              type="string",
     *                              description="Rover state",
     *                              example="READY",
     *                          ),
     *                          @OA\Property(
     *                              property="queue",
     *                              type="string",
     *                              description="Rover command queue",
     *                              example="RLM",
     *                          ),
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
            $rover = $this->getRoverService()->get($id);
            return response()->json($rover, 200);
        } catch (NotFoundHttpException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v2/rovers",
     *     tags={"rover"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="x",
     *                      type="integer",
     *                      description="The x coordinate of the rover",
     *                      example="50"
     *                  ),
     *                  @OA\Property(
     *                      property="y",
     *                      type="integer",
     *                      description="The y coordinate of the rover",
     *                      example="50"
     *                  ),
     *                  @OA\Property(
     *                      property="plateau_id",
     *                      type="integer",
     *                      description="The plateau_id of the rover",
     *                      example="50"
     *                  ),
     *                  @OA\Property(
     *                      property="direction",
     *                      type="string",
     *                      description="The direction of the rover",
     *                      example="N"
     *                  ),
     *           )
     *       )
     *   ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns rover",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              description="The rover id",
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
     *                              property="x",
     *                              type="integer",
     *                              description="Rover x coordinate",
     *                              example="50",
     *                          ),
     *                          @OA\Property(
     *                              property="y",
     *                              type="integer",
     *                              description="Rover y coordinate",
     *                              example="50",
     *                          ),
     *                          @OA\Property(
     *                              property="plateau_id",
     *                              type="integer",
     *                              description="Plateau id's of rover",
     *                              example="1",
     *                          ),
     *                          @OA\Property(
     *                              property="direction",
     *                              type="string",
     *                              description="Rover direction",
     *                              example="N",
     *                          ),
     *                          @OA\Property(
     *                              property="state",
     *                              type="string",
     *                              description="Rover state",
     *                              example="READY",
     *                          ),
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
            $data = $request->only(['x', 'y', 'direction', 'plateau_id']);
            $plateau = $this->getRoverService()->create($data);
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
     * @OA\Get(
     *     path="/api/v2/rovers/{id}/state",
     *     tags={"rover"},
     *     @OA\Response(
     *         response="200",
     *         description="Returns rover's state",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                          @OA\Property(
     *                              property="state",
     *                              type="string",
     *                              description="Rover state",
     *                              example="READY",
     *                          ),
     *                          @OA\Property(
     *                              property="queue",
     *                              type="string",
     *                              description="Rover command queue",
     *                              example="RLM",
     *                          ),
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
    public function state(int $id) {
        try {
            $rover = $this->getRoverService()->get($id);
            return response()->json(['state' => $rover['state'], 'queue' => $rover['queue']], Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @OA\Put(
     *     path="/api/v2/rovers/{id}",
     *     tags={"rover"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="x",
     *                      type="integer",
     *                      description="The x coordinate of the rover",
     *                      example="50"
     *                  ),
     *                  @OA\Property(
     *                      property="y",
     *                      type="integer",
     *                      description="The y coordinate of the rover",
     *                      example="50"
     *                  ),
     *                  @OA\Property(
     *                      property="plateau_id",
     *                      type="integer",
     *                      description="The plateau_id of the rover",
     *                      example="50"
     *                  ),
     *                  @OA\Property(
     *                      property="direction",
     *                      type="string",
     *                      description="The direction of the rover",
     *                      example="N"
     *                  ),
     *           )
     *       )
     *   ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns rover",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              description="The rover id",
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
     *                              property="x",
     *                              type="integer",
     *                              description="Rover x coordinate",
     *                              example="50",
     *                          ),
     *                          @OA\Property(
     *                              property="y",
     *                              type="integer",
     *                              description="Rover y coordinate",
     *                              example="50",
     *                          ),
     *                          @OA\Property(
     *                              property="plateau_id",
     *                              type="integer",
     *                              description="Plateau id's of rover",
     *                              example="1",
     *                          ),
     *                          @OA\Property(
     *                              property="direction",
     *                              type="string",
     *                              description="Rover direction",
     *                              example="N",
     *                          ),
     *                          @OA\Property(
     *                              property="state",
     *                              type="string",
     *                              description="Rover state",
     *                              example="READY",
     *                          ),
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
            $data = $request->only(['x', 'y', 'direction', 'plateau_id']);
            return response()->json($this->getRoverService()->update($id, $data), Response::HTTP_OK);
        }catch (NotFoundHttpException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (BadRequestException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v2/rovers/{id}/stop",
     *     tags={"rover"},
     *     @OA\Response(
     *         response="200",
     *         description="Stops all commands of rover in queue",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              description="The rover id",
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
     *                              property="x",
     *                              type="integer",
     *                              description="Rover x coordinate",
     *                              example="50",
     *                          ),
     *                          @OA\Property(
     *                              property="y",
     *                              type="integer",
     *                              description="Rover y coordinate",
     *                              example="50",
     *                          ),
     *                          @OA\Property(
     *                              property="plateau_id",
     *                              type="integer",
     *                              description="Plateau id's of rover",
     *                              example="1",
     *                          ),
     *                          @OA\Property(
     *                              property="direction",
     *                              type="string",
     *                              description="Rover direction",
     *                              example="N",
     *                          ),
     *                          @OA\Property(
     *                              property="state",
     *                              type="string",
     *                              description="Rover state",
     *                              example="READY",
     *                          ),
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
    public function stop(int $id) {
        try {
            return response()->json($this->getRoverService()->stop($id), Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v2/rovers/{id}",
     *     tags={"rover"},
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
            $this->getRoverService()->delete($id);
            return response()->json(['success' => true], Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v2/rovers/{id}/execute",
     *     tags={"rover"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="commands",
     *                      type="string",
     *                      description="Commands to be executed",
     *                      example="R+L-<M-+>"
     *                  ),
     *           )
     *       )
     *   ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns rover",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              description="The rover id",
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
     *                              property="x",
     *                              type="integer",
     *                              description="Rover x coordinate",
     *                              example="50",
     *                          ),
     *                          @OA\Property(
     *                              property="y",
     *                              type="integer",
     *                              description="Rover y coordinate",
     *                              example="50",
     *                          ),
     *                          @OA\Property(
     *                              property="plateau_id",
     *                              type="integer",
     *                              description="Plateau id's of rover",
     *                              example="1",
     *                          ),
     *                          @OA\Property(
     *                              property="direction",
     *                              type="string",
     *                              description="Rover direction",
     *                              example="N",
     *                          ),
     *                          @OA\Property(
     *                              property="state",
     *                              type="string",
     *                              description="Rover state",
     *                              example="READY",
     *                          ),
     *                          @OA\Property(
     *                              property="queue",
     *                              type="string",
     *                              description="Rover command queue",
     *                              example="RLM",
     *                          ),
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
    public function execute(Request $request, int $id) {
        try {
            $data = $request->only(['commands']);
            return response()->json($this->roverService->executeCommands($id, $data), Response::HTTP_OK);
        }catch (NotFoundHttpException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (BadRequestException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    abstract public function getRoverService(): AbstractRoverService;
}
