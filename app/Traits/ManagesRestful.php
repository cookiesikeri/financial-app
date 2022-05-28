<?php


namespace App\Traits;


use Illuminate\Support\Arr;

trait ManagesRestful
{
    use ManagesEndpoints;

    /**
     * get all resources
     * @param $endpoint
     * @return mixed
     */
    public function getAllResources($endpoint)
    {
        if(in_array($endpoint, $this->paginate)) {
            //operations for pagination
            if(count(request()->query()) > 0) {
                if (!array_key_exists('page', request()->query())) {
                    $results = $this->endpoints[$endpoint]::on('mysql::read')->where(request()->query())->orderBy('created_at', 'desc')->paginate(20);
                }else {
                    $query_without_page = Arr::except(request()->query(), ['page']);
                    $results =$this->endpoints[$endpoint]::on('mysql::read')->where($query_without_page)->orderBy('created_at', 'desc')->paginate(20);
                }

            }else {
                $results = $this->endpoints[$endpoint]::on('mysql::read')->orderBy('created_at', 'desc')->paginate(20);
            }

        }else {
            //operations for non pagination
            if(count(request()->query()) > 0) {
                $results = $this->endpoints[$endpoint]::on('mysql::read')->where(request()->query())->orderBy('created_at', 'desc')->get();
            }else {
                $results = $this->endpoints[$endpoint]::on('mysql::read')->orderBy('created_at', 'desc')->get();
            }
        }

        return $results;
    }

    /**
     * save a single resource
     * @param array $data
     * @param $endpoint
     * @return mixed
     */
    public function saveResource(array $data, $endpoint)
    {
        $response = $this->endpoints[$endpoint]::on('mysql::write')->create($data);
        if ($response) {
            return $response;
        }
        return null;
    }

    /**
     * fetch a single resource
     * @param $endpoint
     * @param $id
     * @return |null
     */
    public function getSingleResource($endpoint, $id)
    {
        $resource = $this->endpoints[$endpoint]::on('mysql::read')->find($id);
        if (empty($resource)) {
            return null;
        }
        return $resource;
    }

    /**
     * update a single resource
     * @param array $data
     * @param $endpoint
     * @param $id
     * @return |null
     */
    public function updateResource(array $data, $endpoint, $id)
    {
        if ($this->endpoints[$endpoint]::on('mysql::read')->where('id', $id)->exists()) {
            $resource = $this->endpoints[$endpoint]::on('mysql::write')->find($id);
            $updated = $resource->fill($data)->save();
            if ($updated) {
                return $this->endpoints[$endpoint]::on('mysql::read')->find($id);
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    /**
     * delete a single resource
     * @param $endpoint
     * @param $id
     * @return |null
     */
    public function deleteResource($endpoint, $id)
    {
        if ($this->endpoints[$endpoint]::on('mysql::read')->where('id', $id)->exists()) {
            $resource = $this->endpoints[$endpoint]::on('mysql::write')->find($id);
            $resource->delete();

            return $resource;
        } else {
            return null;
        }
    }
}
