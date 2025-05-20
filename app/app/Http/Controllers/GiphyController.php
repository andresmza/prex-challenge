<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetGifByIdRequest;
use App\Http\Requests\SearchGifsRequest;
use App\Services\Giphy\GiphyServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Controller to expose GIPHY search endpoints.
 */
class GiphyController extends Controller
{
    /**
     * Constructor.
     *
     * @param GiphyServiceInterface $giphyService Service to interact with the GIPHY API
     */
    public function __construct(
        private GiphyServiceInterface $giphyService
    ) {
    }

    /**
     * Search for GIFs by search term.
     *
     * @param SearchGifsRequest $request Validated request with search parameters
     * @return JsonResponse JSON response with search results
     *
     * @route GET /api/gifs
     */
    public function search(SearchGifsRequest $request): JsonResponse
    {
        $dto = $request->toDTO();
        $result = $this->giphyService->searchGifs($dto);

        return response()->json($result, Response::HTTP_OK);
    }

    /**
     * Get a specific GIF by its ID.
     *
     * @param GetGifByIdRequest $request Validated request
     * @param string $gifId Unique ID of the GIF to retrieve
     * @return JsonResponse JSON response with GIF details
     *
     * @route GET /api/gifs/{gifId}
     */
    public function show(GetGifByIdRequest $request, string $gifId): JsonResponse
    {
        $gif = $this->giphyService->getGifById($gifId);

        if ($gif === null) {
            return response()->json(['error' => 'GIF not found', 'type' => 'not_found', 'status' => Response::HTTP_NOT_FOUND], Response::HTTP_NOT_FOUND);
        }

        return response()->json($gif, Response::HTTP_OK);
    }
}
