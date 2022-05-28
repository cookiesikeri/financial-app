<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Laravel Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=PT+Sans&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="{{ asset("vendor/scribe/css/style.css") }}" media="screen" />
        <link rel="stylesheet" href="{{ asset("vendor/scribe/css/print.css") }}" media="print" />
        <script src="{{ asset("vendor/scribe/js/all.js") }}"></script>

        <link rel="stylesheet" href="{{ asset("vendor/scribe/css/highlight-darcula.css") }}" media="" />
        <script src="{{ asset("vendor/scribe/js/highlight.pack.js") }}"></script>
    <script>hljs.initHighlightingOnLoad();</script>

</head>

<body class="" data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">
<a href="#" id="nav-button">
      <span>
        NAV
            <img src="{{ asset("vendor/scribe/images/navbar.png") }}" alt="-image" class=""/>
      </span>
</a>
<div class="tocify-wrapper">
                <div class="lang-selector">
                            <a href="#" data-language-name="bash">bash</a>
                            <a href="#" data-language-name="javascript">javascript</a>
                    </div>
        <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>
    <ul class="search-results"></ul>

    <ul id="toc">
    </ul>

            <ul class="toc-footer" id="toc-footer">
                            <li><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li><a href="{{ route("scribe.openapi") }}">View OpenAPI (Swagger) spec</a></li>
                            <li><a href='http://github.com/knuckleswtf/scribe'>Documentation powered by Scribe ✍</a></li>
                    </ul>
            <ul class="toc-footer" id="last-updated">
            <li>Last updated: July 12 2021</li>
        </ul>
</div>
<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1>Introduction</h1>
<p>This documentation aims to provide all the information you need to work with our API.</p>
<aside>As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).</aside>
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>
<script>
    var baseUrl = "http://localhost";
</script>
<script src="{{ asset("vendor/scribe/js/tryitout-2.7.9.js") }}"></script>
<blockquote>
<p>Base URL</p>
</blockquote>
<pre><code class="language-yaml">http://localhost</code></pre><h1>Authenticating requests</h1>
<p>This API is not authenticated.</p><h1>General Restful Modules</h1>
<p>APIs for handling general purpose restful operations.
routes are registered as endpoints in ManagesEndpoints trait.
the registered endpoints can be queried like /api/general/{endpoint}/... e.g /api/general/monthly-incomes, /api/general/monthly-incomes/create,
/api/general/monthly-incomes/1, /api/general/monthly-incomes/1/update, /api/general/monthly-incomes/1/delete
where {endpoint} could be states, lgas, monthly-incomes, employment-statuses, educational-qualifications, residential-statuses.
Any GET, POST, and DELETE request that does not require processing at the backend can be done through the this module.</p>
<h2>Display a listing of the resource.</h2>
<p>{endpoint} could be
[ 'states', 'lgas', 'monthly-incomes', 'employment-statuses', 'residential-statuses', 'educational-qualifications' ].</p>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://localhost/api/general/provident?name=accusantium&amp;lga=consequatur&amp;state_id=minima&amp;state=aut&amp;range=iste&amp;min=voluptatum&amp;max=quasi&amp;status=blanditiis" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
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
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">
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
}</code></pre>
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
<h2>Store a newly created resource in storage.</h2>
<p>{endpoint} could be
[ 'states', 'lgas', 'monthly-incomes', 'employment-statuses', 'residential-statuses', 'educational-qualifications' ].</p>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://localhost/api/general/dicta/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"name":"dolorem","lga":"eum","state_id":"accusamus","state":"sed","range":"sint","min":"velit","max":"eum","status":"consequuntur"}'
</code></pre>
<pre><code class="language-javascript">const url = new URL(
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
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">
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
}</code></pre>
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
<h2>Display the specified resource.</h2>
<p>{endpoint} could be
[ 'states', 'lgas', 'monthly-incomes', 'employment-statuses', 'residential-statuses', 'educational-qualifications' ].</p>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://localhost/api/general/sunt/libero" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/general/sunt/libero"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">
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
}</code></pre>
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
<h2>get the number of resources from a table</h2>
<p>{endpoint} could be
[ 'states', 'lgas', 'monthly-incomes', 'employment-statuses', 'residential-statuses', 'educational-qualifications' ].</p>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://localhost/api/general/qui/count" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/general/qui/count"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">
{
success: true,
data: 450
message: "number of resources retrieved successfully",
status: "success",
}</code></pre>
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
<h2>Update the specified resource in storage.</h2>
<p>{endpoint} could be
[ 'states', 'lgas', 'monthly-incomes', 'employment-statuses', 'residential-statuses', 'educational-qualifications' ].</p>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://localhost/api/general/non/5/update" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"name":"consequuntur","lga":"consequatur","state_id":"dolores","state":"voluptas","range":"sed","min":"porro","max":"impedit","status":"fugit"}'
</code></pre>
<pre><code class="language-javascript">const url = new URL(
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
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">
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
}</code></pre>
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
<h2>Remove the specified resource from storage.</h2>
<p>{endpoint} could be
[ 'states', 'lgas', 'monthly-incomes', 'employment-statuses', 'residential-statuses', 'educational-qualifications' ].</p>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X DELETE \
    "http://localhost/api/general/porro/14/delete" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/general/porro/14/delete"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">
{
success: true,
data: {

},
message: "resource deleted successfully",
status: "success",
}</code></pre>
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
</form><h1>KYC</h1>
<p>APIs for handling a central KYC</p>
<h2>Store a newly created kyc in storage.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://localhost/api/users/kycs/create" \
    -H "Content-Type: multipart/form-data" \
    -H "Accept: application/json" \
    -F "user_id=14" \
    -F "first_name=et" \
    -F "middle_name=veniam" \
    -F "last_name=aliquid" \
    -F "sex=earum" \
    -F "dob=labore" \
    -F "next_of_kin=animi" \
    -F "next_of_kin_contact=et" \
    -F "guarantor=vitae" \
    -F "guarantor_contact=sunt" \
    -F "id_card_number=voluptatem" \
    -F "card_file=optio" \
    -F "id_card_type_id=quo" \
    -F "state_id=17" \
    -F "lga_id=10" \
    -F "mother_maiden_name=veniam" \
    -F "address=suscipit" \
    -F "home_address=corporis" \
    -F "country_of_residence_id=2" \
    -F "country_of_origin_id=17" \
    -F "city=atque" \
    -F "profile_pic=@C:\Users\USER\AppData\Local\Temp\php7E1F.tmp"     -F "address_file=@C:\Users\USER\AppData\Local\Temp\php7EDC.tmp"     -F "passport_photo=@C:\Users\USER\AppData\Local\Temp\php7EDD.tmp" </code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/users/kycs/create"
);

let headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('user_id', '14');
body.append('first_name', 'et');
body.append('middle_name', 'veniam');
body.append('last_name', 'aliquid');
body.append('sex', 'earum');
body.append('dob', 'labore');
body.append('next_of_kin', 'animi');
body.append('next_of_kin_contact', 'et');
body.append('guarantor', 'vitae');
body.append('guarantor_contact', 'sunt');
body.append('id_card_number', 'voluptatem');
body.append('card_file', 'optio');
body.append('id_card_type_id', 'quo');
body.append('state_id', '17');
body.append('lga_id', '10');
body.append('mother_maiden_name', 'veniam');
body.append('address', 'suscipit');
body.append('home_address', 'corporis');
body.append('country_of_residence_id', '2');
body.append('country_of_origin_id', '17');
body.append('city', 'atque');
body.append('profile_pic', document.querySelector('input[name="profile_pic"]').files[0]);
body.append('address_file', document.querySelector('input[name="address_file"]').files[0]);
body.append('passport_photo', document.querySelector('input[name="passport_photo"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">
{
"success": true,
"data": {
"id": "7495768f-22e4-4f7b-bf27-c6a52f895ab7",
"user_id": "2248e4fc-97b0-48e7-8ae3-b660e11cc499",
"first_name": null,
"last_name": null,
"middle_name": null,
"address": null,
"passport_url": "https://slait-aws3-storage.s3.amazonaws.com/transave/users/passports/70f67aw4589gi.jpg",
"home_address": null,
"proof_of_address_url": null,
"id_card_number": null,
"id_card_type_id": 1,
"id_card_url": "https://slait-aws3-storage.s3.amazonaws.com/transave/users/id-cards/60c86de2598fe.jpg",
"next_of_kin": null,
"next_of_kin_contact": null,
"mother_maiden_name": null,
"guarantor": null,
"guarantor_contact": null,
"country_of_residence_id": 161,
"country_of_origin_id": 154,
"state_id": 9,
"lga_id": 164,
"city": null,
"created_at": "2021-06-15T09:07:48.000000Z",
"updated_at": "2021-06-15T09:07:48.000000Z",
},
"message": "user information retrieved successfully",
"status": "success"
}</code></pre>
<div id="execution-results-POSTapi-users-kycs-create" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-users-kycs-create"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-users-kycs-create"></code></pre>
</div>
<div id="execution-error-POSTapi-users-kycs-create" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-users-kycs-create"></code></pre>
</div>
<form id="form-POSTapi-users-kycs-create" data-method="POST" data-path="api/users/kycs/create" data-authed="0" data-hasfiles="3" data-headers='{"Content-Type":"multipart\/form-data","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-users-kycs-create', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-users-kycs-create" onclick="tryItOut('POSTapi-users-kycs-create');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-users-kycs-create" onclick="cancelTryOut('POSTapi-users-kycs-create');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-users-kycs-create" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/users/kycs/create</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>user_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="user_id" data-endpoint="POSTapi-users-kycs-create" data-component="body" required  hidden>
<br>
The ID of the user from users table.
</p>
<p>
<b><code>first_name</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="first_name" data-endpoint="POSTapi-users-kycs-create" data-component="body"  hidden>
<br>
The first name of the user.
</p>
<p>
<b><code>middle_name</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="middle_name" data-endpoint="POSTapi-users-kycs-create" data-component="body"  hidden>
<br>
The middle name of the user.
</p>
<p>
<b><code>last_name</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="last_name" data-endpoint="POSTapi-users-kycs-create" data-component="body"  hidden>
<br>
The last name of the user.
</p>
<p>
<b><code>sex</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="sex" data-endpoint="POSTapi-users-kycs-create" data-component="body"  hidden>
<br>
The sex status of the user. To be saved in users table
</p>
<p>
<b><code>dob</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="dob" data-endpoint="POSTapi-users-kycs-create" data-component="body"  hidden>
<br>
The date of birth of the user. To be saved in users table
</p>
<p>
<b><code>profile_pic</code></b>&nbsp;&nbsp;<small>file</small>     <i>optional</i> &nbsp;
<input type="file" name="profile_pic" data-endpoint="POSTapi-users-kycs-create" data-component="body"  hidden>
<br>
The profile picture of the user. To be saved in users table
</p>
<p>
<b><code>next_of_kin</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="next_of_kin" data-endpoint="POSTapi-users-kycs-create" data-component="body"  hidden>
<br>
The name of the next of kin.
</p>
<p>
<b><code>next_of_kin_contact</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="next_of_kin_contact" data-endpoint="POSTapi-users-kycs-create" data-component="body"  hidden>
<br>
The phone number of the next of kin.
</p>
<p>
<b><code>guarantor</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="guarantor" data-endpoint="POSTapi-users-kycs-create" data-component="body"  hidden>
<br>
The guarantor's name of the kyc.
</p>
<p>
<b><code>guarantor_contact</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="guarantor_contact" data-endpoint="POSTapi-users-kycs-create" data-component="body"  hidden>
<br>
The guarantor's phone number of the kyc.
</p>
<p>
<b><code>id_card_number</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="id_card_number" data-endpoint="POSTapi-users-kycs-create" data-component="body"  hidden>
<br>
The ID card number of the kyc.
</p>
<p>
<b><code>card_file</code></b>&nbsp;&nbsp;<small>file.</small>     <i>optional</i> &nbsp;
<input type="text" name="card_file" data-endpoint="POSTapi-users-kycs-create" data-component="body"  hidden>
<br>
The ID card file to be uploaded of the kyc.
</p>
<p>
<b><code>id_card_type_id</code></b>&nbsp;&nbsp;<small>integer.</small>     <i>optional</i> &nbsp;
<input type="text" name="id_card_type_id" data-endpoint="POSTapi-users-kycs-create" data-component="body"  hidden>
<br>
The ID card type to be selected from id cards type table.
</p>
<p>
<b><code>state_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="state_id" data-endpoint="POSTapi-users-kycs-create" data-component="body"  hidden>
<br>
The state ID of the loan account holder from states table.
</p>
<p>
<b><code>lga_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="lga_id" data-endpoint="POSTapi-users-kycs-create" data-component="body"  hidden>
<br>
The local govt area ID of the loan account holder from lgas table.
</p>
<p>
<b><code>mother_maiden_name</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="mother_maiden_name" data-endpoint="POSTapi-users-kycs-create" data-component="body"  hidden>
<br>
The maiden name of the kyc's mother i.e her father's name.
</p>
<p>
<b><code>address</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="address" data-endpoint="POSTapi-users-kycs-create" data-component="body"  hidden>
<br>
The address of the loan account holder.
</p>
<p>
<b><code>home_address</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="home_address" data-endpoint="POSTapi-users-kycs-create" data-component="body"  hidden>
<br>
The home address of the kyc.
</p>
<p>
<b><code>address_file</code></b>&nbsp;&nbsp;<small>file</small>     <i>optional</i> &nbsp;
<input type="file" name="address_file" data-endpoint="POSTapi-users-kycs-create" data-component="body"  hidden>
<br>
The evidence of address file to upload of the loan account holder
</p>
<p>
<b><code>passport_photo</code></b>&nbsp;&nbsp;<small>file</small>     <i>optional</i> &nbsp;
<input type="file" name="passport_photo" data-endpoint="POSTapi-users-kycs-create" data-component="body"  hidden>
<br>
The passport photo file to be uploaded of the kyc.
</p>
<p>
<b><code>country_of_residence_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="country_of_residence_id" data-endpoint="POSTapi-users-kycs-create" data-component="body"  hidden>
<br>
The country of residence of the kyc to be selected from countries table.
</p>
<p>
<b><code>country_of_origin_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="country_of_origin_id" data-endpoint="POSTapi-users-kycs-create" data-component="body"  hidden>
<br>
The country of origin of the kyc to be selected from countries table.
</p>
<p>
<b><code>city</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="city" data-endpoint="POSTapi-users-kycs-create" data-component="body"  hidden>
<br>
The name of the city of the kyc.
</p>

</form>
<h2>Display the specified kyc.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://localhost/api/users/kycs/2" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/users/kycs/2"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "success": true,
    "data": {
        "id": "7495768f-22e4-4f7b-bf27-c6a52f895ab7",
        "user_id": "2248e4fc-97b0-48e7-8ae3-b660e11cc499",
        "first_name": null,
        "last_name": null,
        "middle_name": null,
        "address": null,
        "home_address": null,
        "proof_of_address_url": null,
        "id_card_number": null,
        "id_card_url": "https:\/\/slait-aws3-storage.s3.amazonaws.com\/transave\/users\/id-cards\/60c86de2598fe.jpg",
        "next_of_kin": null,
        "next_of_kin_contact": null,
        "mother_maiden_name": null,
        "guarantor": null,
        "guarantor_contact": null,
        "country_of_residence_id": null,
        "country_of_origin_id": null,
        "state_id": null,
        "lga_id": null,
        "city": null,
        "created_at": "2021-06-15T09:07:48.000000Z",
        "updated_at": "2021-06-15T09:07:48.000000Z",
        "user": {
            "id": "2248e4fc-97b0-48e7-8ae3-b660e11cc499",
            "name": "Lubem Tser",
            "email": "enginlubem@ymail.com",
            "role_id": null,
            "email_verified_at": null,
            "gLocatorID": null,
            "verified": null,
            "verified_otp": null,
            "image": "https:\/\/slait-aws3-storage.s3.amazonaws.com\/transave\/users\/profiles\/60c870ad472ea.jpg",
            "dob": null,
            "sex": null,
            "withdrawal_limit": 100000,
            "shutdown_level": 0,
            "account_type_id": 1,
            "created_at": "2021-05-25T19:19:28.000000Z",
            "updated_at": "2021-06-15T09:19:41.000000Z",
            "image_url": "https:\/\/slait-aws3-storage.s3.amazonaws.com\/transave\/users\/profiles\/60c870ad472ea.jpg"
        },
        "state": null,
        "lga": null,
        "origin": null,
        "residence": null
    },
    "message": "user information retrieved successfully",
    "status": "success"
}</code></pre>
<div id="execution-results-GETapi-users-kycs--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-users-kycs--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-users-kycs--id-"></code></pre>
</div>
<div id="execution-error-GETapi-users-kycs--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-users-kycs--id-"></code></pre>
</div>
<form id="form-GETapi-users-kycs--id-" data-method="GET" data-path="api/users/kycs/{id}" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-users-kycs--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-users-kycs--id-" onclick="tryItOut('GETapi-users-kycs--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-users-kycs--id-" onclick="cancelTryOut('GETapi-users-kycs--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-users-kycs--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/users/kycs/{id}</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="GETapi-users-kycs--id-" data-component="url" required  hidden>
<br>
The id of the kyc or User id.
</p>
</form>
<h2>Check the status of a specified kyc.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://localhost/api/users/kycs/10/check" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/users/kycs/10/check"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">
{
"success": true,
"data": {
"state": true
},
"message": "completed",
"status": "success",
}</code></pre>
<div id="execution-results-GETapi-users-kycs--id--check" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-users-kycs--id--check"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-users-kycs--id--check"></code></pre>
</div>
<div id="execution-error-GETapi-users-kycs--id--check" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-users-kycs--id--check"></code></pre>
</div>
<form id="form-GETapi-users-kycs--id--check" data-method="GET" data-path="api/users/kycs/{id}/check" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-users-kycs--id--check', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-users-kycs--id--check" onclick="tryItOut('GETapi-users-kycs--id--check');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-users-kycs--id--check" onclick="cancelTryOut('GETapi-users-kycs--id--check');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-users-kycs--id--check" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/users/kycs/{id}/check</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="GETapi-users-kycs--id--check" data-component="url" required  hidden>
<br>
The ID of the kyc or User Id.
</p>
</form>
<h2>Show the percentage of filled entries in a kyc</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://localhost/api/users/kycs/3/percentage" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/users/kycs/3/percentage"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "success": true,
    "data": 27.77777777777778,
    "message": "percentage completed returned successfully",
    "status": "success"
}</code></pre>
<div id="execution-results-GETapi-users-kycs--id--percentage" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-users-kycs--id--percentage"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-users-kycs--id--percentage"></code></pre>
</div>
<div id="execution-error-GETapi-users-kycs--id--percentage" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-users-kycs--id--percentage"></code></pre>
</div>
<form id="form-GETapi-users-kycs--id--percentage" data-method="GET" data-path="api/users/kycs/{id}/percentage" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-users-kycs--id--percentage', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-users-kycs--id--percentage" onclick="tryItOut('GETapi-users-kycs--id--percentage');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-users-kycs--id--percentage" onclick="cancelTryOut('GETapi-users-kycs--id--percentage');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-users-kycs--id--percentage" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/users/kycs/{id}/percentage</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="GETapi-users-kycs--id--percentage" data-component="url" required  hidden>
<br>
The ID of the kyc or User Id.
</p>
</form>
<h2>Update the specified kyc in storage.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://localhost/api/users/kycs/20/update" \
    -H "Content-Type: multipart/form-data" \
    -H "Accept: application/json" \
    -F "user_id=7" \
    -F "first_name=nisi" \
    -F "middle_name=neque" \
    -F "last_name=et" \
    -F "sex=consequatur" \
    -F "dob=ipsam" \
    -F "next_of_kin=amet" \
    -F "next_of_kin_contact=porro" \
    -F "guarantor=similique" \
    -F "guarantor_contact=tenetur" \
    -F "id_card_number=aspernatur" \
    -F "card_file=quidem" \
    -F "id_card_type_id=aspernatur" \
    -F "state_id=20" \
    -F "lga_id=18" \
    -F "mother_maiden_name=et" \
    -F "address=et" \
    -F "home_address=est" \
    -F "country_of_residence_id=16" \
    -F "country_of_origin_id=16" \
    -F "city=rerum" \
    -F "profile_pic=@C:\Users\USER\AppData\Local\Temp\php7EFD.tmp"     -F "address_file=@C:\Users\USER\AppData\Local\Temp\php7EFE.tmp"     -F "passport_photo=@C:\Users\USER\AppData\Local\Temp\php7EFF.tmp" </code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/users/kycs/20/update"
);

let headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('user_id', '7');
body.append('first_name', 'nisi');
body.append('middle_name', 'neque');
body.append('last_name', 'et');
body.append('sex', 'consequatur');
body.append('dob', 'ipsam');
body.append('next_of_kin', 'amet');
body.append('next_of_kin_contact', 'porro');
body.append('guarantor', 'similique');
body.append('guarantor_contact', 'tenetur');
body.append('id_card_number', 'aspernatur');
body.append('card_file', 'quidem');
body.append('id_card_type_id', 'aspernatur');
body.append('state_id', '20');
body.append('lga_id', '18');
body.append('mother_maiden_name', 'et');
body.append('address', 'et');
body.append('home_address', 'est');
body.append('country_of_residence_id', '16');
body.append('country_of_origin_id', '16');
body.append('city', 'rerum');
body.append('profile_pic', document.querySelector('input[name="profile_pic"]').files[0]);
body.append('address_file', document.querySelector('input[name="address_file"]').files[0]);
body.append('passport_photo', document.querySelector('input[name="passport_photo"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">
{
"success": true,
"data": {
"id": "7495768f-22e4-4f7b-bf27-c6a52f895ab7",
"user_id": "2248e4fc-97b0-48e7-8ae3-b660e11cc499",
"first_name": null,
"last_name": null,
"middle_name": null,
"address": null,
"passport_url": "https://slait-aws3-storage.s3.amazonaws.com/transave/users/passports/70f67aw4589gi.jpg",
"home_address": null,
"proof_of_address_url": null,
"id_card_number": null,
"id_card_type_id": 1,
"id_card_url": "https://slait-aws3-storage.s3.amazonaws.com/transave/users/id-cards/60c86de2598fe.jpg",
"next_of_kin": null,
"next_of_kin_contact": null,
"mother_maiden_name": null,
"guarantor": null,
"guarantor_contact": null,
"country_of_residence_id": 161,
"country_of_origin_id": 154,
"state_id": 9,
"lga_id": 164,
"city": null,
"created_at": "2021-06-15T09:07:48.000000Z",
"updated_at": "2021-06-15T09:07:48.000000Z",
},
"message": "user information retrieved successfully",
"status": "success"
}</code></pre>
<div id="execution-results-POSTapi-users-kycs--id--update" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-users-kycs--id--update"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-users-kycs--id--update"></code></pre>
</div>
<div id="execution-error-POSTapi-users-kycs--id--update" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-users-kycs--id--update"></code></pre>
</div>
<form id="form-POSTapi-users-kycs--id--update" data-method="POST" data-path="api/users/kycs/{id}/update" data-authed="0" data-hasfiles="3" data-headers='{"Content-Type":"multipart\/form-data","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-users-kycs--id--update', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-users-kycs--id--update" onclick="tryItOut('POSTapi-users-kycs--id--update');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-users-kycs--id--update" onclick="cancelTryOut('POSTapi-users-kycs--id--update');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-users-kycs--id--update" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/users/kycs/{id}/update</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="POSTapi-users-kycs--id--update" data-component="url" required  hidden>
<br>
The ID of the kyc.
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>user_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="user_id" data-endpoint="POSTapi-users-kycs--id--update" data-component="body" required  hidden>
<br>
The ID of the user from users table.
</p>
<p>
<b><code>first_name</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="first_name" data-endpoint="POSTapi-users-kycs--id--update" data-component="body"  hidden>
<br>
The first name of the user.
</p>
<p>
<b><code>middle_name</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="middle_name" data-endpoint="POSTapi-users-kycs--id--update" data-component="body"  hidden>
<br>
The middle name of the user.
</p>
<p>
<b><code>last_name</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="last_name" data-endpoint="POSTapi-users-kycs--id--update" data-component="body"  hidden>
<br>
The last name of the user.
</p>
<p>
<b><code>sex</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="sex" data-endpoint="POSTapi-users-kycs--id--update" data-component="body"  hidden>
<br>
The sex status of the user. To be saved in users table
</p>
<p>
<b><code>dob</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="dob" data-endpoint="POSTapi-users-kycs--id--update" data-component="body"  hidden>
<br>
The date of birth of the user. To be saved in users table
</p>
<p>
<b><code>profile_pic</code></b>&nbsp;&nbsp;<small>file</small>     <i>optional</i> &nbsp;
<input type="file" name="profile_pic" data-endpoint="POSTapi-users-kycs--id--update" data-component="body"  hidden>
<br>
The profile picture of the user. To be saved in users table
</p>
<p>
<b><code>next_of_kin</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="next_of_kin" data-endpoint="POSTapi-users-kycs--id--update" data-component="body"  hidden>
<br>
The name of the next of kin.
</p>
<p>
<b><code>next_of_kin_contact</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="next_of_kin_contact" data-endpoint="POSTapi-users-kycs--id--update" data-component="body"  hidden>
<br>
The phone number of the next of kin.
</p>
<p>
<b><code>guarantor</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="guarantor" data-endpoint="POSTapi-users-kycs--id--update" data-component="body"  hidden>
<br>
The guarantor's name of the kyc.
</p>
<p>
<b><code>guarantor_contact</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="guarantor_contact" data-endpoint="POSTapi-users-kycs--id--update" data-component="body"  hidden>
<br>
The guarantor's phone number of the kyc.
</p>
<p>
<b><code>id_card_number</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="id_card_number" data-endpoint="POSTapi-users-kycs--id--update" data-component="body"  hidden>
<br>
The ID card number of the kyc.
</p>
<p>
<b><code>card_file</code></b>&nbsp;&nbsp;<small>file.</small>     <i>optional</i> &nbsp;
<input type="text" name="card_file" data-endpoint="POSTapi-users-kycs--id--update" data-component="body"  hidden>
<br>
The ID card file to be uploaded of the kyc.
</p>
<p>
<b><code>id_card_type_id</code></b>&nbsp;&nbsp;<small>integer.</small>     <i>optional</i> &nbsp;
<input type="text" name="id_card_type_id" data-endpoint="POSTapi-users-kycs--id--update" data-component="body"  hidden>
<br>
The ID card type to be selected from id cards type table.
</p>
<p>
<b><code>state_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="state_id" data-endpoint="POSTapi-users-kycs--id--update" data-component="body"  hidden>
<br>
The state ID of the loan account holder from states table.
</p>
<p>
<b><code>lga_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="lga_id" data-endpoint="POSTapi-users-kycs--id--update" data-component="body"  hidden>
<br>
The local govt area ID of the loan account holder from lgas table.
</p>
<p>
<b><code>mother_maiden_name</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="mother_maiden_name" data-endpoint="POSTapi-users-kycs--id--update" data-component="body"  hidden>
<br>
The maiden name of the kyc's mother i.e her father's name.
</p>
<p>
<b><code>address</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="address" data-endpoint="POSTapi-users-kycs--id--update" data-component="body"  hidden>
<br>
The address of the loan account holder.
</p>
<p>
<b><code>home_address</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="home_address" data-endpoint="POSTapi-users-kycs--id--update" data-component="body"  hidden>
<br>
The home address of the kyc.
</p>
<p>
<b><code>address_file</code></b>&nbsp;&nbsp;<small>file</small>     <i>optional</i> &nbsp;
<input type="file" name="address_file" data-endpoint="POSTapi-users-kycs--id--update" data-component="body"  hidden>
<br>
The evidence of address file to upload of the loan account holder
</p>
<p>
<b><code>passport_photo</code></b>&nbsp;&nbsp;<small>file</small>     <i>optional</i> &nbsp;
<input type="file" name="passport_photo" data-endpoint="POSTapi-users-kycs--id--update" data-component="body"  hidden>
<br>
The passport photo file to be uploaded of the kyc.
</p>
<p>
<b><code>country_of_residence_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="country_of_residence_id" data-endpoint="POSTapi-users-kycs--id--update" data-component="body"  hidden>
<br>
The country of residence of the kyc to be selected from countries table.
</p>
<p>
<b><code>country_of_origin_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="country_of_origin_id" data-endpoint="POSTapi-users-kycs--id--update" data-component="body"  hidden>
<br>
The country of origin of the kyc to be selected from countries table.
</p>
<p>
<b><code>city</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="city" data-endpoint="POSTapi-users-kycs--id--update" data-component="body"  hidden>
<br>
The name of the city of the kyc.
</p>

</form>
<h2>Send a request to admin for editing a kyc.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://localhost/api/users/kycs/4/edit" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"content":"dolores"}'
</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/users/kycs/4/edit"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "content": "dolores"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "success": true,
    "data": null,
    "message": "your message has been sent successfully",
    "status": "success"
}</code></pre>
<div id="execution-results-POSTapi-users-kycs--id--edit" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-users-kycs--id--edit"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-users-kycs--id--edit"></code></pre>
</div>
<div id="execution-error-POSTapi-users-kycs--id--edit" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-users-kycs--id--edit"></code></pre>
</div>
<form id="form-POSTapi-users-kycs--id--edit" data-method="POST" data-path="api/users/kycs/{id}/edit" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-users-kycs--id--edit', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-users-kycs--id--edit" onclick="tryItOut('POSTapi-users-kycs--id--edit');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-users-kycs--id--edit" onclick="cancelTryOut('POSTapi-users-kycs--id--edit');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-users-kycs--id--edit" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/users/kycs/{id}/edit</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="POSTapi-users-kycs--id--edit" data-component="url" required  hidden>
<br>
The ID of the kyc or User Id.
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>content</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="content" data-endpoint="POSTapi-users-kycs--id--edit" data-component="body"  hidden>
<br>
The content of the message request.
</p>

</form>
<h2>Remove the specified Kyc from storage.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X DELETE \
    "http://localhost/api/users/kycs/8/delete" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/users/kycs/8/delete"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">
{
"success": true,
"data": {

},
"message": "user details deleted successfully",
"status": "success",
}</code></pre>
<div id="execution-results-DELETEapi-users-kycs--id--delete" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-users-kycs--id--delete"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-users-kycs--id--delete"></code></pre>
</div>
<div id="execution-error-DELETEapi-users-kycs--id--delete" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-users-kycs--id--delete"></code></pre>
</div>
<form id="form-DELETEapi-users-kycs--id--delete" data-method="DELETE" data-path="api/users/kycs/{id}/delete" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-users-kycs--id--delete', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-users-kycs--id--delete" onclick="tryItOut('DELETEapi-users-kycs--id--delete');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-users-kycs--id--delete" onclick="cancelTryOut('DELETEapi-users-kycs--id--delete');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-users-kycs--id--delete" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/users/kycs/{id}/delete</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="DELETEapi-users-kycs--id--delete" data-component="url" required  hidden>
<br>
The ID of the kyc.
</p>
</form><h1>Loan Account</h1>
<p>APIs for handling loan accounts</p>
<h2>Create a new loan account.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://localhost/api/loan-accounts/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"user_id":5,"first_name":"et","middle_name":"sunt","last_name":"eveniet","educational_qualification_id":"velit","marital_status":"praesentium","number_of_children":"aliquam","next_of_kin":"aliquid","next_of_kin_phone":"voluptatem","emergency_contact_name":"perspiciatis","emergency_contact_number":"voluptate","other_information":"est","state_id":8,"lga_id":11,"address":"sunt","residential_status_id":"qui","employment_status_id":"nesciunt","company":"iste","job_title":"iusto","employment_date":"quasi","monthly_income_id":11}'
</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/loan-accounts/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "user_id": 5,
    "first_name": "et",
    "middle_name": "sunt",
    "last_name": "eveniet",
    "educational_qualification_id": "velit",
    "marital_status": "praesentium",
    "number_of_children": "aliquam",
    "next_of_kin": "aliquid",
    "next_of_kin_phone": "voluptatem",
    "emergency_contact_name": "perspiciatis",
    "emergency_contact_number": "voluptate",
    "other_information": "est",
    "state_id": 8,
    "lga_id": 11,
    "address": "sunt",
    "residential_status_id": "qui",
    "employment_status_id": "nesciunt",
    "company": "iste",
    "job_title": "iusto",
    "employment_date": "quasi",
    "monthly_income_id": 11
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">
{
"success": true,
"data": {
"id": "ffade430-2f2e-470e-b6a6-07f01a5f6fbd",
"user_id": "2248e4fc-97b0-48e7-8ae3-b660e11cc499",
"first_name": "Lubem",
"middle_name": null,
"last_name": "Tser",
"educational_qualification_id": null,
"marital_status": 1,
"number_of_children": null,
"next_of_kin": null,
"next_of_kin_phone": null,
"emergency_contact_name": null,
"emergency_contact_number": null,
"other_information": null,
"state_id": 7,
"lga_id": 116,
"city": null,
"address": "Gboko South",
"residential_status_id": null,
"employment_status_id": null,
"company": null,
"job_title": null,
"employment_date": null,
"monthly_income_id": 4,
"created_at": "2021-05-25T19:43:51.000000Z",
"updated_at": "2021-05-25T19:43:51.000000Z",
"email": "enginlubem@ymail.com",
"bvn": null,
"phone": "08034567890",
"sex": null
},
"message": "loan account created successfully",
"status": "success",
}</code></pre>
<div id="execution-results-POSTapi-loan-accounts-create" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-loan-accounts-create"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-loan-accounts-create"></code></pre>
</div>
<div id="execution-error-POSTapi-loan-accounts-create" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-loan-accounts-create"></code></pre>
</div>
<form id="form-POSTapi-loan-accounts-create" data-method="POST" data-path="api/loan-accounts/create" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-loan-accounts-create', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-loan-accounts-create" onclick="tryItOut('POSTapi-loan-accounts-create');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-loan-accounts-create" onclick="cancelTryOut('POSTapi-loan-accounts-create');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-loan-accounts-create" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/loan-accounts/create</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>user_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="user_id" data-endpoint="POSTapi-loan-accounts-create" data-component="body"  hidden>
<br>
required. The ID of the user from users table.
</p>
<p>
<b><code>first_name</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="first_name" data-endpoint="POSTapi-loan-accounts-create" data-component="body"  hidden>
<br>
required. The first name of the loan account user.
</p>
<p>
<b><code>middle_name</code></b>&nbsp;&nbsp;<small>string.</small>     <i>optional</i> &nbsp;
<input type="text" name="middle_name" data-endpoint="POSTapi-loan-accounts-create" data-component="body"  hidden>
<br>
The middle name of the loan account user.
</p>
<p>
<b><code>last_name</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="last_name" data-endpoint="POSTapi-loan-accounts-create" data-component="body"  hidden>
<br>
required. The last name of the loan account user.
</p>
<p>
<b><code>educational_qualification_id</code></b>&nbsp;&nbsp;<small>integer.</small>     <i>optional</i> &nbsp;
<input type="text" name="educational_qualification_id" data-endpoint="POSTapi-loan-accounts-create" data-component="body"  hidden>
<br>
The education qualification ID from educational_qualifications_table
</p>
<p>
<b><code>marital_status</code></b>&nbsp;&nbsp;<small>integer.</small>     <i>optional</i> &nbsp;
<input type="text" name="marital_status" data-endpoint="POSTapi-loan-accounts-create" data-component="body"  hidden>
<br>
Marital status of the applicant. Default to 1.
</p>
<p>
<b><code>number_of_children</code></b>&nbsp;&nbsp;<small>integer.</small>     <i>optional</i> &nbsp;
<input type="text" name="number_of_children" data-endpoint="POSTapi-loan-accounts-create" data-component="body"  hidden>
<br>
The number of children or wards of the applicant.
</p>
<p>
<b><code>next_of_kin</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="next_of_kin" data-endpoint="POSTapi-loan-accounts-create" data-component="body"  hidden>
<br>
required. The name of the next of kin.
</p>
<p>
<b><code>next_of_kin_phone</code></b>&nbsp;&nbsp;<small>string.</small>     <i>optional</i> &nbsp;
<input type="text" name="next_of_kin_phone" data-endpoint="POSTapi-loan-accounts-create" data-component="body"  hidden>
<br>
The phone number of the next of kin.
</p>
<p>
<b><code>emergency_contact_name</code></b>&nbsp;&nbsp;<small>string.</small>     <i>optional</i> &nbsp;
<input type="text" name="emergency_contact_name" data-endpoint="POSTapi-loan-accounts-create" data-component="body"  hidden>
<br>
The emergency contact name of the loan account holder.
</p>
<p>
<b><code>emergency_contact_number</code></b>&nbsp;&nbsp;<small>string.</small>     <i>optional</i> &nbsp;
<input type="text" name="emergency_contact_number" data-endpoint="POSTapi-loan-accounts-create" data-component="body"  hidden>
<br>
The emergency contact number of the loan account holder.
</p>
<p>
<b><code>other_information</code></b>&nbsp;&nbsp;<small>string.</small>     <i>optional</i> &nbsp;
<input type="text" name="other_information" data-endpoint="POSTapi-loan-accounts-create" data-component="body"  hidden>
<br>
The name of the city of the loan account holder.
</p>
<p>
<b><code>state_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="state_id" data-endpoint="POSTapi-loan-accounts-create" data-component="body"  hidden>
<br>
required. The state ID of the loan account holder from states table.
</p>
<p>
<b><code>lga_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="lga_id" data-endpoint="POSTapi-loan-accounts-create" data-component="body"  hidden>
<br>
required. The local govt area ID of the loan account holder from lgas table.
</p>
<p>
<b><code>address</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="address" data-endpoint="POSTapi-loan-accounts-create" data-component="body"  hidden>
<br>
required. The address of the loan account holder
</p>
<p>
<b><code>residential_status_id</code></b>&nbsp;&nbsp;<small>string.</small>     <i>optional</i> &nbsp;
<input type="text" name="residential_status_id" data-endpoint="POSTapi-loan-accounts-create" data-component="body"  hidden>
<br>
The residential status ID from residential_statuses table.
</p>
<p>
<b><code>employment_status_id</code></b>&nbsp;&nbsp;<small>string.</small>     <i>optional</i> &nbsp;
<input type="text" name="employment_status_id" data-endpoint="POSTapi-loan-accounts-create" data-component="body"  hidden>
<br>
The employment status ID from employment_statuses table.
</p>
<p>
<b><code>company</code></b>&nbsp;&nbsp;<small>string.</small>     <i>optional</i> &nbsp;
<input type="text" name="company" data-endpoint="POSTapi-loan-accounts-create" data-component="body"  hidden>
<br>
The name of the applicant's company or establishment.
</p>
<p>
<b><code>job_title</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="job_title" data-endpoint="POSTapi-loan-accounts-create" data-component="body"  hidden>
<br>
required. The job title or job name of the applicant.
</p>
<p>
<b><code>employment_date</code></b>&nbsp;&nbsp;<small>string.</small>     <i>optional</i> &nbsp;
<input type="text" name="employment_date" data-endpoint="POSTapi-loan-accounts-create" data-component="body"  hidden>
<br>
The date of employment.
</p>
<p>
<b><code>monthly_income_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="monthly_income_id" data-endpoint="POSTapi-loan-accounts-create" data-component="body"  hidden>
<br>
required. The ID of the income range from monthly_incomes table.
</p>

</form>
<h2>Display the specified loan account.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://localhost/api/loan-accounts/9" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/loan-accounts/9"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">
{
"success": true,
"data": {
"id": "ffade430-2f2e-470e-b6a6-07f01a5f6fbd",
"user_id": "2248e4fc-97b0-48e7-8ae3-b660e11cc499",
"first_name": "Lubem",
"middle_name": null,
"last_name": "Tser",
"educational_qualification_id": null,
"marital_status": 1,
"number_of_children": null,
"next_of_kin": null,
"next_of_kin_phone": null,
"emergency_contact_name": null,
"emergency_contact_number": null,
"other_information": null,
"state_id": 7,
"lga_id": 116,
"city": null,
"address": "Gboko South",
"residential_status_id": null,
"employment_status_id": null,
"company": null,
"job_title": null,
"employment_date": null,
"monthly_income_id": 4,
"created_at": "2021-05-25T19:43:51.000000Z",
"updated_at": "2021-05-25T19:43:51.000000Z",
"email": "enginlubem@ymail.com",
"bvn": null,
"phone": "08034567890",
"sex": null
},
"message": "loan account retrieved successfully",
"status": "success",
}</code></pre>
<div id="execution-results-GETapi-loan-accounts--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-loan-accounts--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-loan-accounts--id-"></code></pre>
</div>
<div id="execution-error-GETapi-loan-accounts--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-loan-accounts--id-"></code></pre>
</div>
<form id="form-GETapi-loan-accounts--id-" data-method="GET" data-path="api/loan-accounts/{id}" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-loan-accounts--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-loan-accounts--id-" onclick="tryItOut('GETapi-loan-accounts--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-loan-accounts--id-" onclick="cancelTryOut('GETapi-loan-accounts--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-loan-accounts--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/loan-accounts/{id}</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="GETapi-loan-accounts--id-" data-component="url" required  hidden>
<br>
The ID of the loan account.
</p>
</form>
<h2>Get all loans that are pending</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://localhost/api/loan-accounts/6/pending-loans" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/loan-accounts/6/pending-loans"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">
{
"success": true,
"data": [
{
"id": "20bb32bb-04ae-4458-9661-b0a2329d4bc9",
"loan_account_id": "ffade430-2f2e-470e-b6a6-07f01a5f6fbd",
"amount": 40000,
"balance": 140000,
"originating_fee": 60000,
"interest": 40000,
"is_approved": 1,
"expiry_date": "2021-06-04 20:14:44",
"created_at": "2021-05-25T20:14:44.000000Z",
"updated_at": "2021-05-25T20:14:44.000000Z"
},
{
"id": "1fe54ef0-c4eb-4ad6-b26a-2ccca32f71ca",
"loan_account_id": "ffade430-2f2e-470e-b6a6-07f01a5f6fbd",
"amount": 20000,
"balance": 520000,
"originating_fee": 300000,
"interest": 200000,
"is_approved": 1,
"expiry_date": "2021-06-04 19:50:24",
"created_at": "2021-05-25T19:50:24.000000Z",
"updated_at": "2021-05-25T19:50:24.000000Z"
},
"status": "success",
"message": "pending loan payments retrieved successfully"
}</code></pre>
<div id="execution-results-GETapi-loan-accounts--id--pending-loans" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-loan-accounts--id--pending-loans"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-loan-accounts--id--pending-loans"></code></pre>
</div>
<div id="execution-error-GETapi-loan-accounts--id--pending-loans" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-loan-accounts--id--pending-loans"></code></pre>
</div>
<form id="form-GETapi-loan-accounts--id--pending-loans" data-method="GET" data-path="api/loan-accounts/{id}/pending-loans" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-loan-accounts--id--pending-loans', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-loan-accounts--id--pending-loans" onclick="tryItOut('GETapi-loan-accounts--id--pending-loans');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-loan-accounts--id--pending-loans" onclick="cancelTryOut('GETapi-loan-accounts--id--pending-loans');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-loan-accounts--id--pending-loans" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/loan-accounts/{id}/pending-loans</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="GETapi-loan-accounts--id--pending-loans" data-component="url" required  hidden>
<br>
int. The loan account ID of the loan account holder.
</p>
</form>
<h2>Update the specified loan account in storage.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://localhost/api/loan-accounts/3/update" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"user_id":10,"first_name":"qui","middle_name":"et","last_name":"quo","educational_qualification_id":"nostrum","marital_status":"aperiam","number_of_children":"ullam","next_of_kin":"vel","next_of_kin_phone":"nemo","emergency_contact_name":"corporis","emergency_contact_number":"esse","other_information":"aut","state_id":15,"lga_id":19,"address":"eum","residential_status_id":"repellat","employment_status_id":"est","company":"exercitationem","job_title":"praesentium","employment_date":"ab","monthly_income_id":7}'
</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/loan-accounts/3/update"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "user_id": 10,
    "first_name": "qui",
    "middle_name": "et",
    "last_name": "quo",
    "educational_qualification_id": "nostrum",
    "marital_status": "aperiam",
    "number_of_children": "ullam",
    "next_of_kin": "vel",
    "next_of_kin_phone": "nemo",
    "emergency_contact_name": "corporis",
    "emergency_contact_number": "esse",
    "other_information": "aut",
    "state_id": 15,
    "lga_id": 19,
    "address": "eum",
    "residential_status_id": "repellat",
    "employment_status_id": "est",
    "company": "exercitationem",
    "job_title": "praesentium",
    "employment_date": "ab",
    "monthly_income_id": 7
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">
{
"success": true,
"data": {
"id": "ffade430-2f2e-470e-b6a6-07f01a5f6fbd",
"user_id": "2248e4fc-97b0-48e7-8ae3-b660e11cc499",
"first_name": "Lubem",
"middle_name": null,
"last_name": "Tser",
"educational_qualification_id": null,
"marital_status": 1,
"number_of_children": null,
"next_of_kin": null,
"next_of_kin_phone": null,
"emergency_contact_name": null,
"emergency_contact_number": null,
"other_information": null,
"state_id": 7,
"lga_id": 116,
"city": null,
"address": "Gboko South",
"residential_status_id": null,
"employment_status_id": null,
"company": null,
"job_title": null,
"employment_date": null,
"monthly_income_id": 4,
"created_at": "2021-05-25T19:43:51.000000Z",
"updated_at": "2021-05-25T19:43:51.000000Z",
"email": "enginlubem@ymail.com",
"bvn": null,
"phone": "08034567890",
"sex": null
},
"message": "loan account updated successfully",
"status": "success",
}</code></pre>
<div id="execution-results-POSTapi-loan-accounts--id--update" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-loan-accounts--id--update"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-loan-accounts--id--update"></code></pre>
</div>
<div id="execution-error-POSTapi-loan-accounts--id--update" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-loan-accounts--id--update"></code></pre>
</div>
<form id="form-POSTapi-loan-accounts--id--update" data-method="POST" data-path="api/loan-accounts/{id}/update" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-loan-accounts--id--update', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-loan-accounts--id--update" onclick="tryItOut('POSTapi-loan-accounts--id--update');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-loan-accounts--id--update" onclick="cancelTryOut('POSTapi-loan-accounts--id--update');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-loan-accounts--id--update" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/loan-accounts/{id}/update</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="id" data-endpoint="POSTapi-loan-accounts--id--update" data-component="url"  hidden>
<br>
required. The ID of the loan account.
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>user_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="user_id" data-endpoint="POSTapi-loan-accounts--id--update" data-component="body"  hidden>
<br>
required. The ID of the user from users table.
</p>
<p>
<b><code>first_name</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="first_name" data-endpoint="POSTapi-loan-accounts--id--update" data-component="body"  hidden>
<br>
required. The first name of the loan account user.
</p>
<p>
<b><code>middle_name</code></b>&nbsp;&nbsp;<small>string.</small>     <i>optional</i> &nbsp;
<input type="text" name="middle_name" data-endpoint="POSTapi-loan-accounts--id--update" data-component="body"  hidden>
<br>
The middle name of the loan account user.
</p>
<p>
<b><code>last_name</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="last_name" data-endpoint="POSTapi-loan-accounts--id--update" data-component="body"  hidden>
<br>
required. The last name of the loan account user.
</p>
<p>
<b><code>educational_qualification_id</code></b>&nbsp;&nbsp;<small>integer.</small>     <i>optional</i> &nbsp;
<input type="text" name="educational_qualification_id" data-endpoint="POSTapi-loan-accounts--id--update" data-component="body"  hidden>
<br>
The education qualification ID from educational_qualifications_table
</p>
<p>
<b><code>marital_status</code></b>&nbsp;&nbsp;<small>integer.</small>     <i>optional</i> &nbsp;
<input type="text" name="marital_status" data-endpoint="POSTapi-loan-accounts--id--update" data-component="body"  hidden>
<br>
Marital status of the applicant. Default to 1.
</p>
<p>
<b><code>number_of_children</code></b>&nbsp;&nbsp;<small>integer.</small>     <i>optional</i> &nbsp;
<input type="text" name="number_of_children" data-endpoint="POSTapi-loan-accounts--id--update" data-component="body"  hidden>
<br>
The number of children or wards of the applicant.
</p>
<p>
<b><code>next_of_kin</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="next_of_kin" data-endpoint="POSTapi-loan-accounts--id--update" data-component="body"  hidden>
<br>
required. The name of the next of kin.
</p>
<p>
<b><code>next_of_kin_phone</code></b>&nbsp;&nbsp;<small>string.</small>     <i>optional</i> &nbsp;
<input type="text" name="next_of_kin_phone" data-endpoint="POSTapi-loan-accounts--id--update" data-component="body"  hidden>
<br>
The phone number of the next of kin.
</p>
<p>
<b><code>emergency_contact_name</code></b>&nbsp;&nbsp;<small>string.</small>     <i>optional</i> &nbsp;
<input type="text" name="emergency_contact_name" data-endpoint="POSTapi-loan-accounts--id--update" data-component="body"  hidden>
<br>
The emergency contact name of the loan account holder.
</p>
<p>
<b><code>emergency_contact_number</code></b>&nbsp;&nbsp;<small>string.</small>     <i>optional</i> &nbsp;
<input type="text" name="emergency_contact_number" data-endpoint="POSTapi-loan-accounts--id--update" data-component="body"  hidden>
<br>
The emergency contact number of the loan account holder.
</p>
<p>
<b><code>other_information</code></b>&nbsp;&nbsp;<small>string.</small>     <i>optional</i> &nbsp;
<input type="text" name="other_information" data-endpoint="POSTapi-loan-accounts--id--update" data-component="body"  hidden>
<br>
The name of the city of the loan account holder.
</p>
<p>
<b><code>state_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="state_id" data-endpoint="POSTapi-loan-accounts--id--update" data-component="body"  hidden>
<br>
required. The state ID of the loan account holder from states table.
</p>
<p>
<b><code>lga_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="lga_id" data-endpoint="POSTapi-loan-accounts--id--update" data-component="body"  hidden>
<br>
required. The local govt area ID of the loan account holder from lgas table.
</p>
<p>
<b><code>address</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="address" data-endpoint="POSTapi-loan-accounts--id--update" data-component="body"  hidden>
<br>
required. The address of the loan account holder
</p>
<p>
<b><code>residential_status_id</code></b>&nbsp;&nbsp;<small>string.</small>     <i>optional</i> &nbsp;
<input type="text" name="residential_status_id" data-endpoint="POSTapi-loan-accounts--id--update" data-component="body"  hidden>
<br>
The residential status ID from residential_statuses table.
</p>
<p>
<b><code>employment_status_id</code></b>&nbsp;&nbsp;<small>string.</small>     <i>optional</i> &nbsp;
<input type="text" name="employment_status_id" data-endpoint="POSTapi-loan-accounts--id--update" data-component="body"  hidden>
<br>
The employment status ID from employment_statuses table.
</p>
<p>
<b><code>company</code></b>&nbsp;&nbsp;<small>string.</small>     <i>optional</i> &nbsp;
<input type="text" name="company" data-endpoint="POSTapi-loan-accounts--id--update" data-component="body"  hidden>
<br>
The name of the applicant's company or establishment.
</p>
<p>
<b><code>job_title</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="job_title" data-endpoint="POSTapi-loan-accounts--id--update" data-component="body"  hidden>
<br>
required. The job title or job name of the applicant.
</p>
<p>
<b><code>employment_date</code></b>&nbsp;&nbsp;<small>string.</small>     <i>optional</i> &nbsp;
<input type="text" name="employment_date" data-endpoint="POSTapi-loan-accounts--id--update" data-component="body"  hidden>
<br>
The date of employment.
</p>
<p>
<b><code>monthly_income_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="monthly_income_id" data-endpoint="POSTapi-loan-accounts--id--update" data-component="body"  hidden>
<br>
required. The ID of the income range from monthly_incomes table.
</p>

</form>
<h2>Remove the specified loan account from storage.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X DELETE \
    "http://localhost/api/loan-accounts/7/delete" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/loan-accounts/7/delete"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">
{
"success": true,
"data": {

},
"message": "loan account deleted successfully",
"status": "success",
}</code></pre>
<div id="execution-results-DELETEapi-loan-accounts--id--delete" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-loan-accounts--id--delete"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-loan-accounts--id--delete"></code></pre>
</div>
<div id="execution-error-DELETEapi-loan-accounts--id--delete" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-loan-accounts--id--delete"></code></pre>
</div>
<form id="form-DELETEapi-loan-accounts--id--delete" data-method="DELETE" data-path="api/loan-accounts/{id}/delete" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-loan-accounts--id--delete', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-loan-accounts--id--delete" onclick="tryItOut('DELETEapi-loan-accounts--id--delete');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-loan-accounts--id--delete" onclick="cancelTryOut('DELETEapi-loan-accounts--id--delete');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-loan-accounts--id--delete" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/loan-accounts/{id}/delete</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="DELETEapi-loan-accounts--id--delete" data-component="url" required  hidden>
<br>
The ID of the loan account.
</p>
</form>
<h2>Create a new loan account.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://localhost/api/payment-cards/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"user_id":17,"first_name":"non","middle_name":"dolores","last_name":"debitis","educational_qualification_id":"est","marital_status":"non","number_of_children":"ea","next_of_kin":"aut","next_of_kin_phone":"qui","emergency_contact_name":"quo","emergency_contact_number":"est","other_information":"eum","state_id":1,"lga_id":13,"address":"qui","residential_status_id":"inventore","employment_status_id":"inventore","company":"impedit","job_title":"sed","employment_date":"veritatis","monthly_income_id":1}'
</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/payment-cards/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "user_id": 17,
    "first_name": "non",
    "middle_name": "dolores",
    "last_name": "debitis",
    "educational_qualification_id": "est",
    "marital_status": "non",
    "number_of_children": "ea",
    "next_of_kin": "aut",
    "next_of_kin_phone": "qui",
    "emergency_contact_name": "quo",
    "emergency_contact_number": "est",
    "other_information": "eum",
    "state_id": 1,
    "lga_id": 13,
    "address": "qui",
    "residential_status_id": "inventore",
    "employment_status_id": "inventore",
    "company": "impedit",
    "job_title": "sed",
    "employment_date": "veritatis",
    "monthly_income_id": 1
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">
{
"success": true,
"data": {
"id": "ffade430-2f2e-470e-b6a6-07f01a5f6fbd",
"user_id": "2248e4fc-97b0-48e7-8ae3-b660e11cc499",
"first_name": "Lubem",
"middle_name": null,
"last_name": "Tser",
"educational_qualification_id": null,
"marital_status": 1,
"number_of_children": null,
"next_of_kin": null,
"next_of_kin_phone": null,
"emergency_contact_name": null,
"emergency_contact_number": null,
"other_information": null,
"state_id": 7,
"lga_id": 116,
"city": null,
"address": "Gboko South",
"residential_status_id": null,
"employment_status_id": null,
"company": null,
"job_title": null,
"employment_date": null,
"monthly_income_id": 4,
"created_at": "2021-05-25T19:43:51.000000Z",
"updated_at": "2021-05-25T19:43:51.000000Z",
"email": "enginlubem@ymail.com",
"bvn": null,
"phone": "08034567890",
"sex": null
},
"message": "loan account created successfully",
"status": "success",
}</code></pre>
<div id="execution-results-POSTapi-payment-cards-create" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-payment-cards-create"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-payment-cards-create"></code></pre>
</div>
<div id="execution-error-POSTapi-payment-cards-create" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-payment-cards-create"></code></pre>
</div>
<form id="form-POSTapi-payment-cards-create" data-method="POST" data-path="api/payment-cards/create" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-payment-cards-create', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-payment-cards-create" onclick="tryItOut('POSTapi-payment-cards-create');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-payment-cards-create" onclick="cancelTryOut('POSTapi-payment-cards-create');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-payment-cards-create" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/payment-cards/create</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>user_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="user_id" data-endpoint="POSTapi-payment-cards-create" data-component="body"  hidden>
<br>
required. The ID of the user from users table.
</p>
<p>
<b><code>first_name</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="first_name" data-endpoint="POSTapi-payment-cards-create" data-component="body"  hidden>
<br>
required. The first name of the loan account user.
</p>
<p>
<b><code>middle_name</code></b>&nbsp;&nbsp;<small>string.</small>     <i>optional</i> &nbsp;
<input type="text" name="middle_name" data-endpoint="POSTapi-payment-cards-create" data-component="body"  hidden>
<br>
The middle name of the loan account user.
</p>
<p>
<b><code>last_name</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="last_name" data-endpoint="POSTapi-payment-cards-create" data-component="body"  hidden>
<br>
required. The last name of the loan account user.
</p>
<p>
<b><code>educational_qualification_id</code></b>&nbsp;&nbsp;<small>integer.</small>     <i>optional</i> &nbsp;
<input type="text" name="educational_qualification_id" data-endpoint="POSTapi-payment-cards-create" data-component="body"  hidden>
<br>
The education qualification ID from educational_qualifications_table
</p>
<p>
<b><code>marital_status</code></b>&nbsp;&nbsp;<small>integer.</small>     <i>optional</i> &nbsp;
<input type="text" name="marital_status" data-endpoint="POSTapi-payment-cards-create" data-component="body"  hidden>
<br>
Marital status of the applicant. Default to 1.
</p>
<p>
<b><code>number_of_children</code></b>&nbsp;&nbsp;<small>integer.</small>     <i>optional</i> &nbsp;
<input type="text" name="number_of_children" data-endpoint="POSTapi-payment-cards-create" data-component="body"  hidden>
<br>
The number of children or wards of the applicant.
</p>
<p>
<b><code>next_of_kin</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="next_of_kin" data-endpoint="POSTapi-payment-cards-create" data-component="body"  hidden>
<br>
required. The name of the next of kin.
</p>
<p>
<b><code>next_of_kin_phone</code></b>&nbsp;&nbsp;<small>string.</small>     <i>optional</i> &nbsp;
<input type="text" name="next_of_kin_phone" data-endpoint="POSTapi-payment-cards-create" data-component="body"  hidden>
<br>
The phone number of the next of kin.
</p>
<p>
<b><code>emergency_contact_name</code></b>&nbsp;&nbsp;<small>string.</small>     <i>optional</i> &nbsp;
<input type="text" name="emergency_contact_name" data-endpoint="POSTapi-payment-cards-create" data-component="body"  hidden>
<br>
The emergency contact name of the loan account holder.
</p>
<p>
<b><code>emergency_contact_number</code></b>&nbsp;&nbsp;<small>string.</small>     <i>optional</i> &nbsp;
<input type="text" name="emergency_contact_number" data-endpoint="POSTapi-payment-cards-create" data-component="body"  hidden>
<br>
The emergency contact number of the loan account holder.
</p>
<p>
<b><code>other_information</code></b>&nbsp;&nbsp;<small>string.</small>     <i>optional</i> &nbsp;
<input type="text" name="other_information" data-endpoint="POSTapi-payment-cards-create" data-component="body"  hidden>
<br>
The name of the city of the loan account holder.
</p>
<p>
<b><code>state_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="state_id" data-endpoint="POSTapi-payment-cards-create" data-component="body"  hidden>
<br>
required. The state ID of the loan account holder from states table.
</p>
<p>
<b><code>lga_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="lga_id" data-endpoint="POSTapi-payment-cards-create" data-component="body"  hidden>
<br>
required. The local govt area ID of the loan account holder from lgas table.
</p>
<p>
<b><code>address</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="address" data-endpoint="POSTapi-payment-cards-create" data-component="body"  hidden>
<br>
required. The address of the loan account holder
</p>
<p>
<b><code>residential_status_id</code></b>&nbsp;&nbsp;<small>string.</small>     <i>optional</i> &nbsp;
<input type="text" name="residential_status_id" data-endpoint="POSTapi-payment-cards-create" data-component="body"  hidden>
<br>
The residential status ID from residential_statuses table.
</p>
<p>
<b><code>employment_status_id</code></b>&nbsp;&nbsp;<small>string.</small>     <i>optional</i> &nbsp;
<input type="text" name="employment_status_id" data-endpoint="POSTapi-payment-cards-create" data-component="body"  hidden>
<br>
The employment status ID from employment_statuses table.
</p>
<p>
<b><code>company</code></b>&nbsp;&nbsp;<small>string.</small>     <i>optional</i> &nbsp;
<input type="text" name="company" data-endpoint="POSTapi-payment-cards-create" data-component="body"  hidden>
<br>
The name of the applicant's company or establishment.
</p>
<p>
<b><code>job_title</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="job_title" data-endpoint="POSTapi-payment-cards-create" data-component="body"  hidden>
<br>
required. The job title or job name of the applicant.
</p>
<p>
<b><code>employment_date</code></b>&nbsp;&nbsp;<small>string.</small>     <i>optional</i> &nbsp;
<input type="text" name="employment_date" data-endpoint="POSTapi-payment-cards-create" data-component="body"  hidden>
<br>
The date of employment.
</p>
<p>
<b><code>monthly_income_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="monthly_income_id" data-endpoint="POSTapi-payment-cards-create" data-component="body"  hidden>
<br>
required. The ID of the income range from monthly_incomes table.
</p>

</form><h1>Loans</h1>
<p>APIs for handling loan transactions</p>
<h2>api/loans/{id}/user</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://localhost/api/loans/corporis/user" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/loans/corporis/user"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "success": true,
    "data": [],
    "message": "loans retrieved successfully",
    "status": "success"
}</code></pre>
<div id="execution-results-GETapi-loans--id--user" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-loans--id--user"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-loans--id--user"></code></pre>
</div>
<div id="execution-error-GETapi-loans--id--user" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-loans--id--user"></code></pre>
</div>
<form id="form-GETapi-loans--id--user" data-method="GET" data-path="api/loans/{id}/user" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-loans--id--user', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-loans--id--user" onclick="tryItOut('GETapi-loans--id--user');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-loans--id--user" onclick="cancelTryOut('GETapi-loans--id--user');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-loans--id--user" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/loans/{id}/user</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="GETapi-loans--id--user" data-component="url" required  hidden>
<br>

</p>
</form>
<h2>Create a new loan.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://localhost/api/loans/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"amount":6,"loan_account_id":"magnam"}'
</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/loans/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "amount": 6,
    "loan_account_id": "magnam"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">
{
success: true,
data: {
"id": "20bb32bb-04ae-4458-9661-b0a2329d4bc9",
"loan_account_id": "ffade430-2f2e-470e-b6a6-07f01a5f6fbd",
"amount": 40000,
"balance": 50000,
"originating_fee": 6000,
"interest": 4000,
"is_approved": 1,
"expiry_date": "2021-06-04 20:14:44",
"created_at": "2021-05-25T20:14:44.000000Z",
"updated_at": "2021-05-25T20:14:44.000000Z"
},
message: "new loan created successfully",
}</code></pre>
<div id="execution-results-POSTapi-loans-create" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-loans-create"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-loans-create"></code></pre>
</div>
<div id="execution-error-POSTapi-loans-create" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-loans-create"></code></pre>
</div>
<form id="form-POSTapi-loans-create" data-method="POST" data-path="api/loans/create" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-loans-create', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-loans-create" onclick="tryItOut('POSTapi-loans-create');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-loans-create" onclick="cancelTryOut('POSTapi-loans-create');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-loans-create" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/loans/create</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>amount</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="amount" data-endpoint="POSTapi-loans-create" data-component="body"  hidden>
<br>
required. The amount of money requested.
</p>
<p>
<b><code>loan_account_id</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="loan_account_id" data-endpoint="POSTapi-loans-create" data-component="body"  hidden>
<br>
required. The loan account id of the borrower.
</p>

</form>
<h2>Display the specified loan.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://localhost/api/loans/20" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/loans/20"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">
{
success: true,
data: {
"id": "20bb32bb-04ae-4458-9661-b0a2329d4bc9",
"loan_account_id": "ffade430-2f2e-470e-b6a6-07f01a5f6fbd",
"amount": 40000,
"balance": 50000,
"originating_fee": 6000,
"interest": 4000,
"is_approved": 1,
"expiry_date": "2021-06-04 20:14:44",
"created_at": "2021-05-25T20:14:44.000000Z",
"updated_at": "2021-05-25T20:14:44.000000Z"
},
message: "loan details retrieved successfully",
status: "success",
}</code></pre>
<div id="execution-results-GETapi-loans--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-loans--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-loans--id-"></code></pre>
</div>
<div id="execution-error-GETapi-loans--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-loans--id-"></code></pre>
</div>
<form id="form-GETapi-loans--id-" data-method="GET" data-path="api/loans/{id}" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-loans--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-loans--id-" onclick="tryItOut('GETapi-loans--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-loans--id-" onclick="cancelTryOut('GETapi-loans--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-loans--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/loans/{id}</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="GETapi-loans--id-" data-component="url" required  hidden>
<br>
The ID of the loan.
</p>
</form>
<h2>calculate the loan amount that can be issued to a borrower</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://localhost/api/loans/calculate" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"amount":11,"loan_account_id":"culpa"}'
</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/loans/calculate"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "amount": 11,
    "loan_account_id": "culpa"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">
{
success: true,
data: {
"amount": 40000,
"balance": 50000,
"originating_fee": 6000,
"interest": 4000,
"duration": 10,
"expiry_date": "2021-06-04 20:14:44",
},
message: "allowed loan details returned successfully",
status: "success",
}</code></pre>
<div id="execution-results-POSTapi-loans-calculate" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-loans-calculate"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-loans-calculate"></code></pre>
</div>
<div id="execution-error-POSTapi-loans-calculate" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-loans-calculate"></code></pre>
</div>
<form id="form-POSTapi-loans-calculate" data-method="POST" data-path="api/loans/calculate" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-loans-calculate', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-loans-calculate" onclick="tryItOut('POSTapi-loans-calculate');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-loans-calculate" onclick="cancelTryOut('POSTapi-loans-calculate');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-loans-calculate" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/loans/calculate</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>amount</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="amount" data-endpoint="POSTapi-loans-calculate" data-component="body"  hidden>
<br>
required. The amount of money requested.
</p>
<p>
<b><code>loan_account_id</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="loan_account_id" data-endpoint="POSTapi-loans-calculate" data-component="body"  hidden>
<br>
required. The loan account id of the borrower.
</p>

</form>
<h2>repay a specified loan with wallet to wallet transaction</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://localhost/api/loans/16/repay" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"amount":3,"account_number":"laboriosam"}'
</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/loans/16/repay"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "amount": 3,
    "account_number": "laboriosam"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">
{
success: true,
data: {
"wallet_id": 45346997765,
"amount": 50000,
"type": "Credit",
"sender_account_number": 656788888999,
"sender_name": "Joe Brown",
"receiver_name": "James Palmer",
"receiver_account_number": 567234900665,
"description": "loan repayment",
"transfer": true"
},
message: "loan repayment successful",
status: "success",
}</code></pre>
<div id="execution-results-POSTapi-loans--id--repay" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-loans--id--repay"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-loans--id--repay"></code></pre>
</div>
<div id="execution-error-POSTapi-loans--id--repay" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-loans--id--repay"></code></pre>
</div>
<form id="form-POSTapi-loans--id--repay" data-method="POST" data-path="api/loans/{id}/repay" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-loans--id--repay', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-loans--id--repay" onclick="tryItOut('POSTapi-loans--id--repay');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-loans--id--repay" onclick="cancelTryOut('POSTapi-loans--id--repay');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-loans--id--repay" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/loans/{id}/repay</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="POSTapi-loans--id--repay" data-component="url" required  hidden>
<br>
The ID of the loan.
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>amount</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="amount" data-endpoint="POSTapi-loans--id--repay" data-component="body"  hidden>
<br>
required. The amount of money repayed.
</p>
<p>
<b><code>account_number</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="account_number" data-endpoint="POSTapi-loans--id--repay" data-component="body"  hidden>
<br>
required. The repayment account number.
</p>

</form>
<h2>Remove the specified loan from storage.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X DELETE \
    "http://localhost/api/loans/10/delete" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/loans/10/delete"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">
{
success: true,
data: {

},
message: "loan details deleted successfully",
status: "success",
}</code></pre>
<div id="execution-results-DELETEapi-loans--id--delete" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-loans--id--delete"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-loans--id--delete"></code></pre>
</div>
<div id="execution-error-DELETEapi-loans--id--delete" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-loans--id--delete"></code></pre>
</div>
<form id="form-DELETEapi-loans--id--delete" data-method="DELETE" data-path="api/loans/{id}/delete" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-loans--id--delete', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-loans--id--delete" onclick="tryItOut('DELETEapi-loans--id--delete');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-loans--id--delete" onclick="cancelTryOut('DELETEapi-loans--id--delete');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-loans--id--delete" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/loans/{id}/delete</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="DELETEapi-loans--id--delete" data-component="url" required  hidden>
<br>
The ID of the loan.
</p>
</form><h1>Payment Cards</h1>
<p>APIs for handling payment cards</p>
<h2>Display the specified payment card details.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://localhost/api/payment-cards/2" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/payment-cards/2"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">
{
"success": true,
"data": {
"id": "ffade430-2f2e-470e-b6a6-07f01a5f6fbd",
"user_id": "2248e4fc-97b0-48e7-8ae3-b660e11cc499",
"loan_account_id": "ffade430-2f2e-470e-b6a6-07f01a5f6fbd",
"bank_id": 078,
"account_number": 3454567899,
"account_name": Joseph Gyam,
"card_number": 54564554567894,
"expiry_date": 2021-06-11 06:45,
"cvv": 007,
"created_at": "2021-05-25T19:43:51.000000Z",
"updated_at": "2021-05-25T19:43:51.000000Z",
},
"message": "card details retrieved successfully",
"status": "success",
}</code></pre>
<div id="execution-results-GETapi-payment-cards--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-payment-cards--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-payment-cards--id-"></code></pre>
</div>
<div id="execution-error-GETapi-payment-cards--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-payment-cards--id-"></code></pre>
</div>
<form id="form-GETapi-payment-cards--id-" data-method="GET" data-path="api/payment-cards/{id}" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-payment-cards--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-payment-cards--id-" onclick="tryItOut('GETapi-payment-cards--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-payment-cards--id-" onclick="cancelTryOut('GETapi-payment-cards--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-payment-cards--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/payment-cards/{id}</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="GETapi-payment-cards--id-" data-component="url" required  hidden>
<br>
The ID of the payment card.
</p>
</form>
<h2>Check for payment card validity.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://localhost/api/payment-cards/17/validate" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/payment-cards/17/validate"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">
{
"success": true,
"data": {
"bin": "519345",
"brand": "mastercard",
"sub_brand": "",
"country_code": "NG",
"country_name": "Nigeria",
"card_type": "DEBIT",
"bank": "Stanbic IBTC Bank",
"linked_bank_id": 23,
},
"message": "card details retrieved successfully",
"status": "success"
}</code></pre>
<div id="execution-results-GETapi-payment-cards--bin--validate" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-payment-cards--bin--validate"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-payment-cards--bin--validate"></code></pre>
</div>
<div id="execution-error-GETapi-payment-cards--bin--validate" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-payment-cards--bin--validate"></code></pre>
</div>
<form id="form-GETapi-payment-cards--bin--validate" data-method="GET" data-path="api/payment-cards/{bin}/validate" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-payment-cards--bin--validate', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-payment-cards--bin--validate" onclick="tryItOut('GETapi-payment-cards--bin--validate');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-payment-cards--bin--validate" onclick="cancelTryOut('GETapi-payment-cards--bin--validate');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-payment-cards--bin--validate" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/payment-cards/{bin}/validate</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>bin</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="bin" data-endpoint="GETapi-payment-cards--bin--validate" data-component="url"  hidden>
<br>
required. The first 6 digits on a debit card.
</p>
</form>
<h2>Remove the specified payment card from storage.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X DELETE \
    "http://localhost/api/payment-cards/14/delete" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/payment-cards/14/delete"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">
{
"success": true,
"data": {

},
"message": "payment card deleted successfully",
"status": "success",
}</code></pre>
<div id="execution-results-DELETEapi-payment-cards--id--delete" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-payment-cards--id--delete"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-payment-cards--id--delete"></code></pre>
</div>
<div id="execution-error-DELETEapi-payment-cards--id--delete" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-payment-cards--id--delete"></code></pre>
</div>
<form id="form-DELETEapi-payment-cards--id--delete" data-method="DELETE" data-path="api/payment-cards/{id}/delete" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-payment-cards--id--delete', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-payment-cards--id--delete" onclick="tryItOut('DELETEapi-payment-cards--id--delete');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-payment-cards--id--delete" onclick="cancelTryOut('DELETEapi-payment-cards--id--delete');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-payment-cards--id--delete" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/payment-cards/{id}/delete</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="DELETEapi-payment-cards--id--delete" data-component="url" required  hidden>
<br>
The ID of the payment card.
</p>
</form>
<h2>Display a specified bank detail</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://localhost/api/cards/banks/7" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/cards/banks/7"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "success": true,
    "data": {
        "name": "Stanbic IBTC Bank",
        "slug": "stanbic-ibtc-bank",
        "code": "221",
        "longcode": "221159522",
        "gateway": null,
        "pay_with_bank": false,
        "active": true,
        "is_deleted": null,
        "country": "Nigeria",
        "currency": "NGN",
        "type": "nuban",
        "id": 14,
        "createdAt": "2016-07-14T10:04:29.000Z",
        "updatedAt": "2020-02-18T20:24:17.000Z"
    },
    "message": "bank retrieved successfully",
    "status": "success"
}</code></pre>
<div id="execution-results-GETapi-cards-banks--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-cards-banks--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-cards-banks--id-"></code></pre>
</div>
<div id="execution-error-GETapi-cards-banks--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-cards-banks--id-"></code></pre>
</div>
<form id="form-GETapi-cards-banks--id-" data-method="GET" data-path="api/cards/banks/{id}" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-cards-banks--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-cards-banks--id-" onclick="tryItOut('GETapi-cards-banks--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-cards-banks--id-" onclick="cancelTryOut('GETapi-cards-banks--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-cards-banks--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/cards/banks/{id}</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="id" data-endpoint="GETapi-cards-banks--id-" data-component="url"  hidden>
<br>
required. The ID of the bank.
</p>
</form>
<h2>Display listing of available banks</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://localhost/api/cards/banks" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/cards/banks"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">
{
"success": true,
"data": [
{
"name": "Abbey Mortgage Bank",
"slug": "abbey-mortgage-bank",
"code": "801",
"longcode": "",
"gateway": null,
"pay_with_bank": false,
"active": true,
"is_deleted": false,
"country": "Nigeria",
"currency": "NGN",
"type": "nuban",
"id": 174,
"createdAt": "2020-12-07T16:19:09.000Z",
"updatedAt": "2020-12-07T16:19:19.000Z"
},
{
"name": "Access Bank",
"slug": "access-bank",
"code": "044",
"longcode": "044150149",
"gateway": "emandate",
"pay_with_bank": false,
"active": true,
"is_deleted": null,
"country": "Nigeria",
"currency": "NGN",
"type": "nuban",
"id": 1,
"createdAt": "2016-07-14T10:04:29.000Z",
"updatedAt": "2020-02-18T08:06:44.000Z"
},
{
"name": "Access Bank (Diamond)",
"slug": "access-bank-diamond",
"code": "063",
"longcode": "063150162",
"gateway": "emandate",
"pay_with_bank": false,
"active": true,
"is_deleted": null,
"country": "Nigeria",
"currency": "NGN",
"type": "nuban",
"id": 3,
"createdAt": "2016-07-14T10:04:29.000Z",
"updatedAt": "2020-02-18T08:06:48.000Z"
},
{
"name": "ALAT by WEMA",
"slug": "alat-by-wema",
"code": "035A",
"longcode": "035150103",
"gateway": "emandate",
"pay_with_bank": true,
"active": true,
"is_deleted": null,
"country": "Nigeria",
"currency": "NGN",
"type": "nuban",
"id": 27,
"createdAt": "2017-11-15T12:21:31.000Z",
"updatedAt": "2021-02-18T14:55:34.000Z"
},
{
"name": "ASO Savings and Loans",
"slug": "asosavings",
"code": "401",
"longcode": "",
"gateway": null,
"pay_with_bank": false,
"active": true,
"is_deleted": null,
"country": "Nigeria",

"currency": "NGN",
"type": "nuban",
"id": 63,
"createdAt": "2018-09-23T05:52:38.000Z",
"updatedAt": "2019-01-30T09:38:57.000Z"
},
{
"name": "Bowen Microfinance Bank",
"slug": "bowen-microfinance-bank",
"code": "50931",
"longcode": "",
"gateway": null,
"pay_with_bank": false,
"active": true,
"is_deleted": false,
"country": "Nigeria",
"currency": "NGN",
"type": "nuban",
"id": 108,
"createdAt": "2020-02-11T15:38:57.000Z",
"updatedAt": "2020-02-11T15:38:57.000Z"
},
{
"name": "CEMCS Microfinance Bank",
"slug": "cemcs-microfinance-bank",
"code": "50823",
"longcode": "",
"gateway": null,
"pay_with_bank": false,
"active": true,
"is_deleted": false,
"country": "Nigeria",
"currency": "NGN",
"type": "nuban",
"id": 74,
"createdAt": "2020-03-23T15:06:13.000Z",
"updatedAt": "2020-03-23T15:06:28.000Z"
},
{
"name": "Citibank Nigeria",
"slug": "citibank-nigeria",
"code": "023",
"longcode": "023150005",
"gateway": null,
"pay_with_bank": false,
"active": true,
"is_deleted": null,
"country": "Nigeria",
"currency": "NGN",
"type": "nuban",
"id": 2,
"createdAt": "2016-07-14T10:04:29.000Z",
"updatedAt": "2020-02-18T20:24:02.000Z"
},
],
"message": "list of available banks retrieved successfully",
"status": "success"
}</code></pre>
<div id="execution-results-GETapi-cards-banks" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-cards-banks"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-cards-banks"></code></pre>
</div>
<div id="execution-error-GETapi-cards-banks" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-cards-banks"></code></pre>
</div>
<form id="form-GETapi-cards-banks" data-method="GET" data-path="api/cards/banks" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-cards-banks', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-cards-banks" onclick="tryItOut('GETapi-cards-banks');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-cards-banks" onclick="cancelTryOut('GETapi-cards-banks');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-cards-banks" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/cards/banks</code></b>
</p>
</form><h1>User activities</h1>
<p>APIs for handling a user activities</p>
<h2>api/accounts/requests/count</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://localhost/api/accounts/requests/count" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/accounts/requests/count"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "success": true,
    "data": {
        "upgrade": 2,
        "edit": 1,
        "shutdown": 1
    },
    "message": "request retrieved successfully",
    "status": "success"
}</code></pre>
<div id="execution-results-GETapi-accounts-requests-count" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-accounts-requests-count"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-accounts-requests-count"></code></pre>
</div>
<div id="execution-error-GETapi-accounts-requests-count" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-accounts-requests-count"></code></pre>
</div>
<form id="form-GETapi-accounts-requests-count" data-method="GET" data-path="api/accounts/requests/count" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-accounts-requests-count', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-accounts-requests-count" onclick="tryItOut('GETapi-accounts-requests-count');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-accounts-requests-count" onclick="cancelTryOut('GETapi-accounts-requests-count');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-accounts-requests-count" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/accounts/requests/count</code></b>
</p>
</form>
<h2>api/accounts/requests/{id}/show</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://localhost/api/accounts/requests/consequuntur/show" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/accounts/requests/consequuntur/show"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (500):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Trying to get property 'user_id' of non-object",
    "exception": "ErrorException",
    "file": "C:\\laragon\\www\\transave-finance-backend\\app\\Http\\Controllers\\Apis\\AccountRequestController.php",
    "line": 71,
    "trace": [
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\app\\Http\\Controllers\\Apis\\AccountRequestController.php",
            "line": 71,
            "function": "handleError",
            "class": "Illuminate\\Foundation\\Bootstrap\\HandleExceptions",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Controller.php",
            "line": 54,
            "function": "show",
            "class": "App\\Http\\Controllers\\Apis\\AccountRequestController",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\ControllerDispatcher.php",
            "line": 45,
            "function": "callAction",
            "class": "Illuminate\\Routing\\Controller",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Route.php",
            "line": 254,
            "function": "dispatch",
            "class": "Illuminate\\Routing\\ControllerDispatcher",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Route.php",
            "line": 197,
            "function": "runController",
            "class": "Illuminate\\Routing\\Route",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
            "line": 695,
            "function": "run",
            "class": "Illuminate\\Routing\\Route",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 128,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Router",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\app\\Http\\Middleware\\DevManager.php",
            "line": 23,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 167,
            "function": "handle",
            "class": "App\\Http\\Middleware\\DevManager",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Middleware\\SubstituteBindings.php",
            "line": 50,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 167,
            "function": "handle",
            "class": "Illuminate\\Routing\\Middleware\\SubstituteBindings",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Middleware\\ThrottleRequests.php",
            "line": 127,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Middleware\\ThrottleRequests.php",
            "line": 63,
            "function": "handleRequest",
            "class": "Illuminate\\Routing\\Middleware\\ThrottleRequests",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 167,
            "function": "handle",
            "class": "Illuminate\\Routing\\Middleware\\ThrottleRequests",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\sanctum\\src\\Http\\Middleware\\EnsureFrontendRequestsAreStateful.php",
            "line": 33,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 128,
            "function": "Laravel\\Sanctum\\Http\\Middleware\\{closure}",
            "class": "Laravel\\Sanctum\\Http\\Middleware\\EnsureFrontendRequestsAreStateful",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 103,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\sanctum\\src\\Http\\Middleware\\EnsureFrontendRequestsAreStateful.php",
            "line": 34,
            "function": "then",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 167,
            "function": "handle",
            "class": "Laravel\\Sanctum\\Http\\Middleware\\EnsureFrontendRequestsAreStateful",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 103,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
            "line": 697,
            "function": "then",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
            "line": 672,
            "function": "runRouteWithinStack",
            "class": "Illuminate\\Routing\\Router",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
            "line": 636,
            "function": "runRoute",
            "class": "Illuminate\\Routing\\Router",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
            "line": 625,
            "function": "dispatchToRoute",
            "class": "Illuminate\\Routing\\Router",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Kernel.php",
            "line": 166,
            "function": "dispatch",
            "class": "Illuminate\\Routing\\Router",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 128,
            "function": "Illuminate\\Foundation\\Http\\{closure}",
            "class": "Illuminate\\Foundation\\Http\\Kernel",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php",
            "line": 21,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\ConvertEmptyStringsToNull.php",
            "line": 31,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 167,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Http\\Middleware\\ConvertEmptyStringsToNull",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php",
            "line": 21,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TrimStrings.php",
            "line": 40,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 167,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Http\\Middleware\\TrimStrings",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\ValidatePostSize.php",
            "line": 27,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 167,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Http\\Middleware\\ValidatePostSize",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance.php",
            "line": 86,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 167,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\fruitcake\\laravel-cors\\src\\HandleCors.php",
            "line": 52,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 167,
            "function": "handle",
            "class": "Fruitcake\\Cors\\HandleCors",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\fideloper\\proxy\\src\\TrustProxies.php",
            "line": 57,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 167,
            "function": "handle",
            "class": "Fideloper\\Proxy\\TrustProxies",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 103,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Kernel.php",
            "line": 141,
            "function": "then",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Kernel.php",
            "line": 110,
            "function": "sendRequestThroughRouter",
            "class": "Illuminate\\Foundation\\Http\\Kernel",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\knuckleswtf\\scribe\\src\\Extracting\\Strategies\\Responses\\ResponseCalls.php",
            "line": 324,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Http\\Kernel",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\knuckleswtf\\scribe\\src\\Extracting\\Strategies\\Responses\\ResponseCalls.php",
            "line": 305,
            "function": "callLaravelOrLumenRoute",
            "class": "Knuckles\\Scribe\\Extracting\\Strategies\\Responses\\ResponseCalls",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\knuckleswtf\\scribe\\src\\Extracting\\Strategies\\Responses\\ResponseCalls.php",
            "line": 76,
            "function": "makeApiCall",
            "class": "Knuckles\\Scribe\\Extracting\\Strategies\\Responses\\ResponseCalls",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\knuckleswtf\\scribe\\src\\Extracting\\Strategies\\Responses\\ResponseCalls.php",
            "line": 51,
            "function": "makeResponseCall",
            "class": "Knuckles\\Scribe\\Extracting\\Strategies\\Responses\\ResponseCalls",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\knuckleswtf\\scribe\\src\\Extracting\\Strategies\\Responses\\ResponseCalls.php",
            "line": 41,
            "function": "makeResponseCallIfEnabledAndNoSuccessResponses",
            "class": "Knuckles\\Scribe\\Extracting\\Strategies\\Responses\\ResponseCalls",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\knuckleswtf\\scribe\\src\\Extracting\\Generator.php",
            "line": 236,
            "function": "__invoke",
            "class": "Knuckles\\Scribe\\Extracting\\Strategies\\Responses\\ResponseCalls",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\knuckleswtf\\scribe\\src\\Extracting\\Generator.php",
            "line": 172,
            "function": "iterateThroughStrategies",
            "class": "Knuckles\\Scribe\\Extracting\\Generator",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\knuckleswtf\\scribe\\src\\Extracting\\Generator.php",
            "line": 127,
            "function": "fetchResponses",
            "class": "Knuckles\\Scribe\\Extracting\\Generator",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\knuckleswtf\\scribe\\src\\Commands\\GenerateDocumentation.php",
            "line": 119,
            "function": "processRoute",
            "class": "Knuckles\\Scribe\\Extracting\\Generator",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\knuckleswtf\\scribe\\src\\Commands\\GenerateDocumentation.php",
            "line": 73,
            "function": "processRoutes",
            "class": "Knuckles\\Scribe\\Commands\\GenerateDocumentation",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php",
            "line": 36,
            "function": "handle",
            "class": "Knuckles\\Scribe\\Commands\\GenerateDocumentation",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php",
            "line": 40,
            "function": "Illuminate\\Container\\{closure}",
            "class": "Illuminate\\Container\\BoundMethod",
            "type": "::"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php",
            "line": 93,
            "function": "unwrapIfClosure",
            "class": "Illuminate\\Container\\Util",
            "type": "::"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php",
            "line": 37,
            "function": "callBoundMethod",
            "class": "Illuminate\\Container\\BoundMethod",
            "type": "::"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php",
            "line": 651,
            "function": "call",
            "class": "Illuminate\\Container\\BoundMethod",
            "type": "::"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php",
            "line": 136,
            "function": "call",
            "class": "Illuminate\\Container\\Container",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\symfony\\console\\Command\\Command.php",
            "line": 299,
            "function": "execute",
            "class": "Illuminate\\Console\\Command",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php",
            "line": 121,
            "function": "run",
            "class": "Symfony\\Component\\Console\\Command\\Command",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\symfony\\console\\Application.php",
            "line": 978,
            "function": "run",
            "class": "Illuminate\\Console\\Command",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\symfony\\console\\Application.php",
            "line": 295,
            "function": "doRunCommand",
            "class": "Symfony\\Component\\Console\\Application",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\symfony\\console\\Application.php",
            "line": 167,
            "function": "doRun",
            "class": "Symfony\\Component\\Console\\Application",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php",
            "line": 92,
            "function": "run",
            "class": "Symfony\\Component\\Console\\Application",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php",
            "line": 129,
            "function": "run",
            "class": "Illuminate\\Console\\Application",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\artisan",
            "line": 37,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Console\\Kernel",
            "type": "-&gt;"
        }
    ]
}</code></pre>
<div id="execution-results-GETapi-accounts-requests--id--show" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-accounts-requests--id--show"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-accounts-requests--id--show"></code></pre>
</div>
<div id="execution-error-GETapi-accounts-requests--id--show" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-accounts-requests--id--show"></code></pre>
</div>
<form id="form-GETapi-accounts-requests--id--show" data-method="GET" data-path="api/accounts/requests/{id}/show" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-accounts-requests--id--show', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-accounts-requests--id--show" onclick="tryItOut('GETapi-accounts-requests--id--show');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-accounts-requests--id--show" onclick="cancelTryOut('GETapi-accounts-requests--id--show');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-accounts-requests--id--show" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/accounts/requests/{id}/show</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="GETapi-accounts-requests--id--show" data-component="url" required  hidden>
<br>

</p>
</form>
<h2>api/accounts/requests/{id}/upgrade</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://localhost/api/accounts/requests/excepturi/upgrade" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/accounts/requests/excepturi/upgrade"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (500):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Trying to get property 'user_id' of non-object",
    "exception": "ErrorException",
    "file": "C:\\laragon\\www\\transave-finance-backend\\app\\Traits\\ManagesUsers.php",
    "line": 155,
    "trace": [
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\app\\Traits\\ManagesUsers.php",
            "line": 155,
            "function": "handleError",
            "class": "Illuminate\\Foundation\\Bootstrap\\HandleExceptions",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\app\\Http\\Controllers\\Apis\\AccountRequestController.php",
            "line": 61,
            "function": "upgradeUserAccount",
            "class": "App\\Http\\Controllers\\Apis\\AccountRequestController",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Controller.php",
            "line": 54,
            "function": "upgrade",
            "class": "App\\Http\\Controllers\\Apis\\AccountRequestController",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\ControllerDispatcher.php",
            "line": 45,
            "function": "callAction",
            "class": "Illuminate\\Routing\\Controller",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Route.php",
            "line": 254,
            "function": "dispatch",
            "class": "Illuminate\\Routing\\ControllerDispatcher",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Route.php",
            "line": 197,
            "function": "runController",
            "class": "Illuminate\\Routing\\Route",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
            "line": 695,
            "function": "run",
            "class": "Illuminate\\Routing\\Route",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 128,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Router",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\app\\Http\\Middleware\\DevManager.php",
            "line": 23,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 167,
            "function": "handle",
            "class": "App\\Http\\Middleware\\DevManager",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Middleware\\SubstituteBindings.php",
            "line": 50,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 167,
            "function": "handle",
            "class": "Illuminate\\Routing\\Middleware\\SubstituteBindings",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Middleware\\ThrottleRequests.php",
            "line": 127,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Middleware\\ThrottleRequests.php",
            "line": 63,
            "function": "handleRequest",
            "class": "Illuminate\\Routing\\Middleware\\ThrottleRequests",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 167,
            "function": "handle",
            "class": "Illuminate\\Routing\\Middleware\\ThrottleRequests",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\sanctum\\src\\Http\\Middleware\\EnsureFrontendRequestsAreStateful.php",
            "line": 33,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 128,
            "function": "Laravel\\Sanctum\\Http\\Middleware\\{closure}",
            "class": "Laravel\\Sanctum\\Http\\Middleware\\EnsureFrontendRequestsAreStateful",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 103,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\sanctum\\src\\Http\\Middleware\\EnsureFrontendRequestsAreStateful.php",
            "line": 34,
            "function": "then",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 167,
            "function": "handle",
            "class": "Laravel\\Sanctum\\Http\\Middleware\\EnsureFrontendRequestsAreStateful",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 103,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
            "line": 697,
            "function": "then",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
            "line": 672,
            "function": "runRouteWithinStack",
            "class": "Illuminate\\Routing\\Router",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
            "line": 636,
            "function": "runRoute",
            "class": "Illuminate\\Routing\\Router",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
            "line": 625,
            "function": "dispatchToRoute",
            "class": "Illuminate\\Routing\\Router",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Kernel.php",
            "line": 166,
            "function": "dispatch",
            "class": "Illuminate\\Routing\\Router",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 128,
            "function": "Illuminate\\Foundation\\Http\\{closure}",
            "class": "Illuminate\\Foundation\\Http\\Kernel",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php",
            "line": 21,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\ConvertEmptyStringsToNull.php",
            "line": 31,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 167,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Http\\Middleware\\ConvertEmptyStringsToNull",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php",
            "line": 21,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TrimStrings.php",
            "line": 40,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 167,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Http\\Middleware\\TrimStrings",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\ValidatePostSize.php",
            "line": 27,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 167,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Http\\Middleware\\ValidatePostSize",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance.php",
            "line": 86,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 167,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\fruitcake\\laravel-cors\\src\\HandleCors.php",
            "line": 52,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 167,
            "function": "handle",
            "class": "Fruitcake\\Cors\\HandleCors",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\fideloper\\proxy\\src\\TrustProxies.php",
            "line": 57,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 167,
            "function": "handle",
            "class": "Fideloper\\Proxy\\TrustProxies",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
            "line": 103,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Kernel.php",
            "line": 141,
            "function": "then",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Kernel.php",
            "line": 110,
            "function": "sendRequestThroughRouter",
            "class": "Illuminate\\Foundation\\Http\\Kernel",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\knuckleswtf\\scribe\\src\\Extracting\\Strategies\\Responses\\ResponseCalls.php",
            "line": 324,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Http\\Kernel",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\knuckleswtf\\scribe\\src\\Extracting\\Strategies\\Responses\\ResponseCalls.php",
            "line": 305,
            "function": "callLaravelOrLumenRoute",
            "class": "Knuckles\\Scribe\\Extracting\\Strategies\\Responses\\ResponseCalls",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\knuckleswtf\\scribe\\src\\Extracting\\Strategies\\Responses\\ResponseCalls.php",
            "line": 76,
            "function": "makeApiCall",
            "class": "Knuckles\\Scribe\\Extracting\\Strategies\\Responses\\ResponseCalls",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\knuckleswtf\\scribe\\src\\Extracting\\Strategies\\Responses\\ResponseCalls.php",
            "line": 51,
            "function": "makeResponseCall",
            "class": "Knuckles\\Scribe\\Extracting\\Strategies\\Responses\\ResponseCalls",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\knuckleswtf\\scribe\\src\\Extracting\\Strategies\\Responses\\ResponseCalls.php",
            "line": 41,
            "function": "makeResponseCallIfEnabledAndNoSuccessResponses",
            "class": "Knuckles\\Scribe\\Extracting\\Strategies\\Responses\\ResponseCalls",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\knuckleswtf\\scribe\\src\\Extracting\\Generator.php",
            "line": 236,
            "function": "__invoke",
            "class": "Knuckles\\Scribe\\Extracting\\Strategies\\Responses\\ResponseCalls",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\knuckleswtf\\scribe\\src\\Extracting\\Generator.php",
            "line": 172,
            "function": "iterateThroughStrategies",
            "class": "Knuckles\\Scribe\\Extracting\\Generator",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\knuckleswtf\\scribe\\src\\Extracting\\Generator.php",
            "line": 127,
            "function": "fetchResponses",
            "class": "Knuckles\\Scribe\\Extracting\\Generator",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\knuckleswtf\\scribe\\src\\Commands\\GenerateDocumentation.php",
            "line": 119,
            "function": "processRoute",
            "class": "Knuckles\\Scribe\\Extracting\\Generator",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\knuckleswtf\\scribe\\src\\Commands\\GenerateDocumentation.php",
            "line": 73,
            "function": "processRoutes",
            "class": "Knuckles\\Scribe\\Commands\\GenerateDocumentation",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php",
            "line": 36,
            "function": "handle",
            "class": "Knuckles\\Scribe\\Commands\\GenerateDocumentation",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php",
            "line": 40,
            "function": "Illuminate\\Container\\{closure}",
            "class": "Illuminate\\Container\\BoundMethod",
            "type": "::"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php",
            "line": 93,
            "function": "unwrapIfClosure",
            "class": "Illuminate\\Container\\Util",
            "type": "::"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php",
            "line": 37,
            "function": "callBoundMethod",
            "class": "Illuminate\\Container\\BoundMethod",
            "type": "::"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php",
            "line": 651,
            "function": "call",
            "class": "Illuminate\\Container\\BoundMethod",
            "type": "::"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php",
            "line": 136,
            "function": "call",
            "class": "Illuminate\\Container\\Container",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\symfony\\console\\Command\\Command.php",
            "line": 299,
            "function": "execute",
            "class": "Illuminate\\Console\\Command",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php",
            "line": 121,
            "function": "run",
            "class": "Symfony\\Component\\Console\\Command\\Command",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\symfony\\console\\Application.php",
            "line": 978,
            "function": "run",
            "class": "Illuminate\\Console\\Command",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\symfony\\console\\Application.php",
            "line": 295,
            "function": "doRunCommand",
            "class": "Symfony\\Component\\Console\\Application",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\symfony\\console\\Application.php",
            "line": 167,
            "function": "doRun",
            "class": "Symfony\\Component\\Console\\Application",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php",
            "line": 92,
            "function": "run",
            "class": "Symfony\\Component\\Console\\Application",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php",
            "line": 129,
            "function": "run",
            "class": "Illuminate\\Console\\Application",
            "type": "-&gt;"
        },
        {
            "file": "C:\\laragon\\www\\transave-finance-backend\\artisan",
            "line": 37,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Console\\Kernel",
            "type": "-&gt;"
        }
    ]
}</code></pre>
<div id="execution-results-GETapi-accounts-requests--id--upgrade" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-accounts-requests--id--upgrade"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-accounts-requests--id--upgrade"></code></pre>
</div>
<div id="execution-error-GETapi-accounts-requests--id--upgrade" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-accounts-requests--id--upgrade"></code></pre>
</div>
<form id="form-GETapi-accounts-requests--id--upgrade" data-method="GET" data-path="api/accounts/requests/{id}/upgrade" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-accounts-requests--id--upgrade', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-accounts-requests--id--upgrade" onclick="tryItOut('GETapi-accounts-requests--id--upgrade');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-accounts-requests--id--upgrade" onclick="cancelTryOut('GETapi-accounts-requests--id--upgrade');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-accounts-requests--id--upgrade" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/accounts/requests/{id}/upgrade</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="GETapi-accounts-requests--id--upgrade" data-component="url" required  hidden>
<br>

</p>
</form>
<h2>api/accounts/requests/{id}/delete</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X DELETE \
    "http://localhost/api/accounts/requests/in/delete" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/accounts/requests/in/delete"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
<div id="execution-results-DELETEapi-accounts-requests--id--delete" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-accounts-requests--id--delete"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-accounts-requests--id--delete"></code></pre>
</div>
<div id="execution-error-DELETEapi-accounts-requests--id--delete" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-accounts-requests--id--delete"></code></pre>
</div>
<form id="form-DELETEapi-accounts-requests--id--delete" data-method="DELETE" data-path="api/accounts/requests/{id}/delete" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-accounts-requests--id--delete', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-accounts-requests--id--delete" onclick="tryItOut('DELETEapi-accounts-requests--id--delete');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-accounts-requests--id--delete" onclick="cancelTryOut('DELETEapi-accounts-requests--id--delete');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-accounts-requests--id--delete" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/accounts/requests/{id}/delete</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="DELETEapi-accounts-requests--id--delete" data-component="url" required  hidden>
<br>

</p>
</form>
<h2>Send a request to admin for shutting down account. An account shutdown by admin means it is</h2>
<p>completely prevented from login and transaction</p>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://localhost/api/accounts/requests/11/shutdown" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"content":"quisquam"}'
</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/accounts/requests/11/shutdown"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "content": "quisquam"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "success": true,
    "data": null,
    "message": "shutdown request has been sent successfully",
    "status": "success"
}</code></pre>
<div id="execution-results-POSTapi-accounts-requests--id--shutdown" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-accounts-requests--id--shutdown"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-accounts-requests--id--shutdown"></code></pre>
</div>
<div id="execution-error-POSTapi-accounts-requests--id--shutdown" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-accounts-requests--id--shutdown"></code></pre>
</div>
<form id="form-POSTapi-accounts-requests--id--shutdown" data-method="POST" data-path="api/accounts/requests/{id}/shutdown" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-accounts-requests--id--shutdown', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-accounts-requests--id--shutdown" onclick="tryItOut('POSTapi-accounts-requests--id--shutdown');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-accounts-requests--id--shutdown" onclick="cancelTryOut('POSTapi-accounts-requests--id--shutdown');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-accounts-requests--id--shutdown" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/accounts/requests/{id}/shutdown</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="POSTapi-accounts-requests--id--shutdown" data-component="url" required  hidden>
<br>
The ID of the user.
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>content</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="content" data-endpoint="POSTapi-accounts-requests--id--shutdown" data-component="body"  hidden>
<br>
The content of the message request.
</p>

</form>
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                    <a href="#" data-language-name="bash">bash</a>
                                    <a href="#" data-language-name="javascript">javascript</a>
                            </div>
            </div>
</div>
<script>
    $(function () {
        var languages = ["bash","javascript"];
        setupLanguages(languages);
    });
</script>
</body>
</html>