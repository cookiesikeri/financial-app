# General Restful Modules

APIs for handling general purpose restful operations.
routes are registered as endpoints in ManagesEndpoints trait.
the registered endpoints can be queried like /api/general/{endpoint}/... e.g /api/general/monthly-incomes, /api/general/monthly-incomes/create,
/api/general/monthly-incomes/1, /api/general/monthly-incomes/1/update, /api/general/monthly-incomes/1/delete
where {endpoint} could be states, lgas, monthly-incomes, employment-statuses, educational-qualifications, residential-statuses.
Any GET, POST, and DELETE request that does not require processing at the backend can be done through the this module.

## Display a listing of the resource.


{endpoint} could be
[ 'states', 'lgas', 'monthly-incomes', 'employment-statuses', 'residential-statuses', 'educational-qualifications' ].

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/general/provident?name=accusantium&lga=consequatur&state_id=minima&state=aut&range=iste&min=voluptatum&max=quasi&status=blanditiis" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/general/provident"
);

let params = {
    "name": "accusantium",
    "lga": "consequatur",
    "state_id": "minima",
    "state": "aut",
    "range": "iste",
    "min": "voluptatum",
    "max": "quasi",
    "status": "blanditiis",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json

{
success: true,
data: [
{
"id": 1,
"name": "Senior School Certificate",
"created_at": "2021-05-25T19:50:24.000000Z",
"updated_at": "2021-05-25T19:50:24.000000Z"
},
{
"id": 2,
"name": "National Diploma",
"created_at": "2021-05-25T19:50:24.000000Z",
"updated_at": "2021-05-25T19:50:24.000000Z"
},
{
"id": 3,
"name": "National Certificate in Education",
"created_at": "2021-05-25T19:50:24.000000Z",
"updated_at": "2021-05-25T19:50:24.000000Z"
},
]
message: "resources retrieved successfully".
status: "success"
}
```
<div id="execution-results-GETapi-general--endpoint-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-general--endpoint-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-general--endpoint-"></code></pre>
</div>
<div id="execution-error-GETapi-general--endpoint-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-general--endpoint-"></code></pre>
</div>
<form id="form-GETapi-general--endpoint-" data-method="GET" data-path="api/general/{endpoint}" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-general--endpoint-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-general--endpoint-" onclick="tryItOut('GETapi-general--endpoint-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-general--endpoint-" onclick="cancelTryOut('GETapi-general--endpoint-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-general--endpoint-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/general/{endpoint}</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>endpoint</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="endpoint" data-endpoint="GETapi-general--endpoint-" data-component="url" required  hidden>
<br>

</p>
<h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
<p>
<b><code>name</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="name" data-endpoint="GETapi-general--endpoint-" data-component="query"  hidden>
<br>
string. The name of educational qualification. Used with educational-qualifications,
</p>
<p>
<b><code>lga</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="lga" data-endpoint="GETapi-general--endpoint-" data-component="query"  hidden>
<br>
string. The name of LGA. Used with lgas.
</p>
<p>
<b><code>state_id</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="state_id" data-endpoint="GETapi-general--endpoint-" data-component="query"  hidden>
<br>
integer. The state ID. Used with lgas.
</p>
<p>
<b><code>state</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="state" data-endpoint="GETapi-general--endpoint-" data-component="query"  hidden>
<br>
string. The name of state. Used with states.
</p>
<p>
<b><code>range</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="range" data-endpoint="GETapi-general--endpoint-" data-component="query"  hidden>
<br>
string. The range of income. Used in monthly-incomes.
</p>
<p>
<b><code>min</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="min" data-endpoint="GETapi-general--endpoint-" data-component="query"  hidden>
<br>
integer. The minimum income within a range. Used with monthly-incomes.
</p>
<p>
<b><code>max</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="max" data-endpoint="GETapi-general--endpoint-" data-component="query"  hidden>
<br>
integer. The maximum income within a range. Used with monthly-incomes.
</p>
<p>
<b><code>status</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="status" data-endpoint="GETapi-general--endpoint-" data-component="query"  hidden>
<br>
string. The status name. Used in residential-statuses and employment-statuses.
</p>
</form>


## Store a newly created resource in storage.


{endpoint} could be
[ 'states', 'lgas', 'monthly-incomes', 'employment-statuses', 'residential-statuses', 'educational-qualifications' ].

> Example request:

```bash
curl -X POST \
    "http://localhost/api/general/dicta/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"name":"dolorem","lga":"eum","state_id":"accusamus","state":"sed","range":"sint","min":"velit","max":"eum","status":"consequuntur"}'

```

```javascript
const url = new URL(
    "http://localhost/api/general/dicta/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "dolorem",
    "lga": "eum",
    "state_id": "accusamus",
    "state": "sed",
    "range": "sint",
    "min": "velit",
    "max": "eum",
    "status": "consequuntur"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json

{
success: true,
data: {
"id": 2,
"name": "National Diploma",
"created_at": "2021-05-25T19:50:24.000000Z",
"updated_at": "2021-05-25T19:50:24.000000Z"
},
message: "resource saved successfully".
status: "success"
}
```
<div id="execution-results-POSTapi-general--endpoint--create" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-general--endpoint--create"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-general--endpoint--create"></code></pre>
</div>
<div id="execution-error-POSTapi-general--endpoint--create" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-general--endpoint--create"></code></pre>
</div>
<form id="form-POSTapi-general--endpoint--create" data-method="POST" data-path="api/general/{endpoint}/create" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-general--endpoint--create', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-general--endpoint--create" onclick="tryItOut('POSTapi-general--endpoint--create');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-general--endpoint--create" onclick="cancelTryOut('POSTapi-general--endpoint--create');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-general--endpoint--create" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/general/{endpoint}/create</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>endpoint</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="endpoint" data-endpoint="POSTapi-general--endpoint--create" data-component="url" required  hidden>
<br>

</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>name</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="name" data-endpoint="POSTapi-general--endpoint--create" data-component="body"  hidden>
<br>
required. The name of educational qualification. Used with educational-qualifications,
</p>
<p>
<b><code>lga</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="lga" data-endpoint="POSTapi-general--endpoint--create" data-component="body"  hidden>
<br>
required. The name of LGA. Used with lgas.
</p>
<p>
<b><code>state_id</code></b>&nbsp;&nbsp;<small>required</small>     <i>optional</i> &nbsp;
<input type="text" name="state_id" data-endpoint="POSTapi-general--endpoint--create" data-component="body"  hidden>
<br>
integer. The state ID. Used with lgas.
</p>
<p>
<b><code>state</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="state" data-endpoint="POSTapi-general--endpoint--create" data-component="body"  hidden>
<br>
required. The name of state. Used with states.
</p>
<p>
<b><code>range</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="range" data-endpoint="POSTapi-general--endpoint--create" data-component="body"  hidden>
<br>
required. The range of income. Used in monthly-incomes.
</p>
<p>
<b><code>min</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="min" data-endpoint="POSTapi-general--endpoint--create" data-component="body"  hidden>
<br>
required. The minimum income within a range. Used with monthly-incomes.
</p>
<p>
<b><code>max</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="max" data-endpoint="POSTapi-general--endpoint--create" data-component="body"  hidden>
<br>
required. The maximum income within a range. Used with monthly-incomes.
</p>
<p>
<b><code>status</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="status" data-endpoint="POSTapi-general--endpoint--create" data-component="body"  hidden>
<br>
required. The status name. Used in residential-statuses and employment-statuses.
</p>

</form>


## Display the specified resource.


{endpoint} could be
[ 'states', 'lgas', 'monthly-incomes', 'employment-statuses', 'residential-statuses', 'educational-qualifications' ].

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/general/sunt/libero" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/general/sunt/libero"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json

{
success: true,
data: {
"id": 2,
"name": "National Diploma",
"created_at": "2021-05-25T19:50:24.000000Z",
"updated_at": "2021-05-25T19:50:24.000000Z"
},
message: "resource retrieved successfully".
status: "success"
}
```
<div id="execution-results-GETapi-general--endpoint---id-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-general--endpoint---id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-general--endpoint---id-"></code></pre>
</div>
<div id="execution-error-GETapi-general--endpoint---id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-general--endpoint---id-"></code></pre>
</div>
<form id="form-GETapi-general--endpoint---id-" data-method="GET" data-path="api/general/{endpoint}/{id}" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-general--endpoint---id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-general--endpoint---id-" onclick="tryItOut('GETapi-general--endpoint---id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-general--endpoint---id-" onclick="cancelTryOut('GETapi-general--endpoint---id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-general--endpoint---id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/general/{endpoint}/{id}</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>endpoint</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="endpoint" data-endpoint="GETapi-general--endpoint---id-" data-component="url" required  hidden>
<br>

</p>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="GETapi-general--endpoint---id-" data-component="url" required  hidden>
<br>
integer. The ID of the specified resource.
</p>
</form>


## get the number of resources from a table


{endpoint} could be
[ 'states', 'lgas', 'monthly-incomes', 'employment-statuses', 'residential-statuses', 'educational-qualifications' ].

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/general/qui/count" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/general/qui/count"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json

{
success: true,
data: 450
message: "number of resources retrieved successfully",
status: "success",
}
```
<div id="execution-results-GETapi-general--endpoint--count" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-general--endpoint--count"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-general--endpoint--count"></code></pre>
</div>
<div id="execution-error-GETapi-general--endpoint--count" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-general--endpoint--count"></code></pre>
</div>
<form id="form-GETapi-general--endpoint--count" data-method="GET" data-path="api/general/{endpoint}/count" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-general--endpoint--count', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-general--endpoint--count" onclick="tryItOut('GETapi-general--endpoint--count');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-general--endpoint--count" onclick="cancelTryOut('GETapi-general--endpoint--count');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-general--endpoint--count" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/general/{endpoint}/count</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>endpoint</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="endpoint" data-endpoint="GETapi-general--endpoint--count" data-component="url" required  hidden>
<br>

</p>
</form>


## Update the specified resource in storage.


{endpoint} could be
[ 'states', 'lgas', 'monthly-incomes', 'employment-statuses', 'residential-statuses', 'educational-qualifications' ].

> Example request:

```bash
curl -X POST \
    "http://localhost/api/general/non/5/update" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"name":"consequuntur","lga":"consequatur","state_id":"dolores","state":"voluptas","range":"sed","min":"porro","max":"impedit","status":"fugit"}'

```

```javascript
const url = new URL(
    "http://localhost/api/general/non/5/update"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "consequuntur",
    "lga": "consequatur",
    "state_id": "dolores",
    "state": "voluptas",
    "range": "sed",
    "min": "porro",
    "max": "impedit",
    "status": "fugit"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json

{
success: true,
data: {
"id": 2,
"name": "National Diploma",
"created_at": "2021-05-25T19:50:24.000000Z",
"updated_at": "2021-05-25T19:50:24.000000Z"
},
message: "resource updated successfully".
status: "success"
}
```
<div id="execution-results-POSTapi-general--endpoint---id--update" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-general--endpoint---id--update"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-general--endpoint---id--update"></code></pre>
</div>
<div id="execution-error-POSTapi-general--endpoint---id--update" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-general--endpoint---id--update"></code></pre>
</div>
<form id="form-POSTapi-general--endpoint---id--update" data-method="POST" data-path="api/general/{endpoint}/{id}/update" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-general--endpoint---id--update', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-general--endpoint---id--update" onclick="tryItOut('POSTapi-general--endpoint---id--update');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-general--endpoint---id--update" onclick="cancelTryOut('POSTapi-general--endpoint---id--update');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-general--endpoint---id--update" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/general/{endpoint}/{id}/update</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>endpoint</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="endpoint" data-endpoint="POSTapi-general--endpoint---id--update" data-component="url" required  hidden>
<br>

</p>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="id" data-endpoint="POSTapi-general--endpoint---id--update" data-component="url"  hidden>
<br>
required. The ID of the specified resource.
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>name</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="name" data-endpoint="POSTapi-general--endpoint---id--update" data-component="body"  hidden>
<br>
required. The name of educational qualification. Used with educational-qualifications,
</p>
<p>
<b><code>lga</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="lga" data-endpoint="POSTapi-general--endpoint---id--update" data-component="body"  hidden>
<br>
required. The name of LGA. Used with lgas.
</p>
<p>
<b><code>state_id</code></b>&nbsp;&nbsp;<small>required</small>     <i>optional</i> &nbsp;
<input type="text" name="state_id" data-endpoint="POSTapi-general--endpoint---id--update" data-component="body"  hidden>
<br>
integer. The state ID. Used with lgas.
</p>
<p>
<b><code>state</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="state" data-endpoint="POSTapi-general--endpoint---id--update" data-component="body"  hidden>
<br>
required. The name of state. Used with states.
</p>
<p>
<b><code>range</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="range" data-endpoint="POSTapi-general--endpoint---id--update" data-component="body"  hidden>
<br>
required. The range of income. Used in monthly-incomes.
</p>
<p>
<b><code>min</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="min" data-endpoint="POSTapi-general--endpoint---id--update" data-component="body"  hidden>
<br>
required. The minimum income within a range. Used with monthly-incomes.
</p>
<p>
<b><code>max</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="max" data-endpoint="POSTapi-general--endpoint---id--update" data-component="body"  hidden>
<br>
required. The maximum income within a range. Used with monthly-incomes.
</p>
<p>
<b><code>status</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="status" data-endpoint="POSTapi-general--endpoint---id--update" data-component="body"  hidden>
<br>
required. The status name. Used in residential-statuses and employment-statuses.
</p>

</form>


## Remove the specified resource from storage.


{endpoint} could be
[ 'states', 'lgas', 'monthly-incomes', 'employment-statuses', 'residential-statuses', 'educational-qualifications' ].

> Example request:

```bash
curl -X DELETE \
    "http://localhost/api/general/porro/14/delete" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/general/porro/14/delete"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json

{
success: true,
data: {

},
message: "resource deleted successfully",
status: "success",
}
```
<div id="execution-results-DELETEapi-general--endpoint---id--delete" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-general--endpoint---id--delete"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-general--endpoint---id--delete"></code></pre>
</div>
<div id="execution-error-DELETEapi-general--endpoint---id--delete" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-general--endpoint---id--delete"></code></pre>
</div>
<form id="form-DELETEapi-general--endpoint---id--delete" data-method="DELETE" data-path="api/general/{endpoint}/{id}/delete" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-general--endpoint---id--delete', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-general--endpoint---id--delete" onclick="tryItOut('DELETEapi-general--endpoint---id--delete');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-general--endpoint---id--delete" onclick="cancelTryOut('DELETEapi-general--endpoint---id--delete');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-general--endpoint---id--delete" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/general/{endpoint}/{id}/delete</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>endpoint</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="endpoint" data-endpoint="DELETEapi-general--endpoint---id--delete" data-component="url" required  hidden>
<br>

</p>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="DELETEapi-general--endpoint---id--delete" data-component="url" required  hidden>
<br>
The ID of the specified resource.
</p>
</form>



