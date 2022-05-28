<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Traits\ManagesEndpoints;
use App\Traits\ManagesResponse;
use App\Traits\ManagesRestful;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

/**
 * @group General Restful Modules
 *
 * APIs for handling general purpose restful operations.
 * routes are registered as endpoints in ManagesEndpoints trait.
 * the registered endpoints can be queried like /api/general/{endpoint}/... e.g /api/general/monthly-incomes, /api/general/monthly-incomes/create,
 * /api/general/monthly-incomes/1, /api/general/monthly-incomes/1/update, /api/general/monthly-incomes/1/delete
 * where {endpoint} could be states, lgas, monthly-incomes, employment-statuses, educational-qualifications, residential-statuses.
 * Any GET, POST, and DELETE request that does not require processing at the backend can be done through the this module.
 */
class RestfulController extends Controller
{
    use ManagesResponse, ManagesEndpoints, ManagesRestful;

    /**
     * Display a listing of the resource.
     * {endpoint} could be
     * [ 'states', 'lgas', 'monthly-incomes', 'employment-statuses', 'residential-statuses', 'educational-qualifications' ].
     * @queryParam name string. The name of educational qualification. Used with educational-qualifications,
     * @queryParam lga string. The name of LGA. Used with lgas.
     * @queryParam state_id integer. The state ID. Used with lgas.
     * @queryParam state string. The name of state. Used with states.
     * @queryParam range string. The range of income. Used in monthly-incomes.
     * @queryParam min integer. The minimum income within a range. Used with monthly-incomes.
     * @queryParam max integer. The maximum income within a range. Used with monthly-incomes.
     * @queryParam status string. The status name. Used in residential-statuses and employment-statuses.
     * @response {
     * success: true,
     * data: [
     * {
     * "id": 1,
     * "name": "Senior School Certificate",
     * "created_at": "2021-05-25T19:50:24.000000Z",
     * "updated_at": "2021-05-25T19:50:24.000000Z"
     * },
     * {
     * "id": 2,
     * "name": "National Diploma",
     * "created_at": "2021-05-25T19:50:24.000000Z",
     * "updated_at": "2021-05-25T19:50:24.000000Z"
     * },
     * {
     * "id": 3,
     * "name": "National Certificate in Education",
     * "created_at": "2021-05-25T19:50:24.000000Z",
     * "updated_at": "2021-05-25T19:50:24.000000Z"
     * },
     * ]
     * message: "resources retrieved successfully".
     * status: "success"
     * }
     * @param $endpoint
     * @return Response
     */
    public function index($endpoint)
    {
        try {
            if(!array_key_exists($endpoint, $this->endpoints)) {
                return $this->sendError('no endpoint found');
            }
            $results = $this->getAllResources($endpoint);

            return $this->sendResponse($results, 'resources retrieved successfully.');

        } catch (ModelNotFoundException $exception) {
            return $this->sendError('Endpoint not found.', $exception);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * {endpoint} could be
     * [ 'states', 'lgas', 'monthly-incomes', 'employment-statuses', 'residential-statuses', 'educational-qualifications' ].
     * @bodyParam name string required. The name of educational qualification. Used with educational-qualifications,
     * @bodyParam lga string required. The name of LGA. Used with lgas.
     * @bodyParam state_id required integer. The state ID. Used with lgas.
     * @bodyParam state string required. The name of state. Used with states.
     * @bodyParam range string required. The range of income. Used in monthly-incomes.
     * @bodyParam min string required. The minimum income within a range. Used with monthly-incomes.
     * @bodyParam max string required. The maximum income within a range. Used with monthly-incomes.
     * @bodyParam status string required. The status name. Used in residential-statuses and employment-statuses.
     *
     * @response {
     * success: true,
     * data: {
     * "id": 2,
     * "name": "National Diploma",
     * "created_at": "2021-05-25T19:50:24.000000Z",
     * "updated_at": "2021-05-25T19:50:24.000000Z"
     * },
     * message: "resource saved successfully".
     * status: "success"
     * }
     *
     * @param Request $request
     * @param $endpoint
     * @return Response
     */
    public function store(Request $request, $endpoint)
    {
        try {
            if(!array_key_exists($endpoint, $this->endpoints)) {
                return $this->sendError('no endpoint found');
            }

            $rules = $this->rules[$endpoint]['store'];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $error = $validator->errors();
                return $this->sendError('Validation Error.', $error);
            }

            $resource = $this->saveResource($request->all(), $endpoint);
            if ($resource) {
                return $this->sendResponse($resource, 'resource saved successfully');
            }
            return $this->sendError('unable to save resource at the moment');

        } catch (ModelNotFoundException $exception) {
            return $this->sendError('Endpoint not found.', $exception, '404');
        }
    }

    /**
     * Display the specified resource.
     * {endpoint} could be
     * [ 'states', 'lgas', 'monthly-incomes', 'employment-statuses', 'residential-statuses', 'educational-qualifications' ].
     *
     * @urlParam id required integer. The ID of the specified resource.
     * @response {
     * success: true,
     * data: {
     * "id": 2,
     * "name": "National Diploma",
     * "created_at": "2021-05-25T19:50:24.000000Z",
     * "updated_at": "2021-05-25T19:50:24.000000Z"
     * },
     * message: "resource retrieved successfully".
     * status: "success"
     * }
     * @param $endpoint
     * @param int $id
     * @return Response
     */
    public function show($endpoint, $id)
    {
        try {
            if(!array_key_exists($endpoint, $this->endpoints)) {
                return $this->sendError('no endpoint found');
            }
            $response = $this->getSingleResource($endpoint, $id);
            if ($response) {
                return $this->sendResponse($response, 'resource retrieved successfully');
            }
            return $this->sendResponse(null, 'unable to get resource at the moment');

        } catch (ModelNotFoundException $error) {
            return $this->sendError('Endpoint not found.', $error);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * {endpoint} could be
     * [ 'states', 'lgas', 'monthly-incomes', 'employment-statuses', 'residential-statuses', 'educational-qualifications' ].
     * @urlParam id integer required. The ID of the specified resource.
     * @bodyParam name string required. The name of educational qualification. Used with educational-qualifications,
     * @bodyParam lga string required. The name of LGA. Used with lgas.
     * @bodyParam state_id required integer. The state ID. Used with lgas.
     * @bodyParam state string required. The name of state. Used with states.
     * @bodyParam range string required. The range of income. Used in monthly-incomes.
     * @bodyParam min string required. The minimum income within a range. Used with monthly-incomes.
     * @bodyParam max string required. The maximum income within a range. Used with monthly-incomes.
     * @bodyParam status string required. The status name. Used in residential-statuses and employment-statuses.
     *
     * @response {
     * success: true,
     * data: {
     * "id": 2,
     * "name": "National Diploma",
     * "created_at": "2021-05-25T19:50:24.000000Z",
     * "updated_at": "2021-05-25T19:50:24.000000Z"
     * },
     * message: "resource updated successfully".
     * status: "success"
     * }
     *
     * @param \Illuminate\Http\Request $request
     * @param $endpoint
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $endpoint, $id)
    {
        try {
            if(!array_key_exists($endpoint, $this->endpoints)) {
                return $this->sendError('no endpoint found');
            }

            $rules = $this->rules[$endpoint]['update'];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
            $resource = $this->updateResource($request->all(), $endpoint, $id);
            if ($resource) {
                return $this->sendResponse($resource, 'resource updated successfully');
            }
            return $this->sendError('error in updating resource');

        } catch (ModelNotFoundException $error) {
            return $this->sendError('Endpoint not found.', $error);
        }
    }

    /**
     * Remove the specified resource from storage.
     * {endpoint} could be
     * [ 'states', 'lgas', 'monthly-incomes', 'employment-statuses', 'residential-statuses', 'educational-qualifications' ].
     *
     * @urlParam id integer required The ID of the specified resource.
     * @response {
     * success: true,
     * data: {
     *
     * },
     * message: "resource deleted successfully",
     * status: "success",
     * }
     *
     * @param $endpoint
     * @param int $id
     * @return Response
     */
    public function destroy($endpoint, $id)
    {
        try {
            if(!array_key_exists($endpoint, $this->endpoints)) {
                return $this->sendError('no endpoint found');
            }
            $response = $this->deleteResource($endpoint, $id);
            if ($response) {
                return $this->sendResponse($response, 'resource deleted successfully');
            }
            return $this->sendError('unable to delete resource');

        } catch (ModelNotFoundException $error) {
            return $this->sendError('Endpoint not found.', $error);
        }
    }

    /**
     * get the number of resources from a table
     *
     * {endpoint} could be
     * [ 'states', 'lgas', 'monthly-incomes', 'employment-statuses', 'residential-statuses', 'educational-qualifications' ].
     *
     * @response {
     * success: true,
     * data: 450
     * message: "number of resources retrieved successfully",
     * status: "success",
     * }
     * @param $endpoint
     * @return Response
     */
    public function count($endpoint)
    {
        $count = $this->endpoints[$endpoint]::count();
        return $this->sendResponse($count, 'number of resources retrieved successfully');
    }
}
