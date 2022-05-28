# Loans

APIs for handling loan transactions

## api/loans/{id}/user




> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/loans/corporis/user" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/loans/corporis/user"
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
    "success": true,
    "data": [],
    "message": "loans retrieved successfully",
    "status": "success"
}
```
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


## Create a new loan.




> Example request:

```bash
curl -X POST \
    "http://localhost/api/loans/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"amount":6,"loan_account_id":"magnam"}'

```

```javascript
const url = new URL(
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
}).then(response => response.json());
```


> Example response (200):

```json

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
}
```
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


## Display the specified loan.




> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/loans/20" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/loans/20"
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
}
```
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


## calculate the loan amount that can be issued to a borrower




> Example request:

```bash
curl -X POST \
    "http://localhost/api/loans/calculate" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"amount":11,"loan_account_id":"culpa"}'

```

```javascript
const url = new URL(
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
}).then(response => response.json());
```


> Example response (200):

```json

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
}
```
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


## repay a specified loan with wallet to wallet transaction




> Example request:

```bash
curl -X POST \
    "http://localhost/api/loans/16/repay" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"amount":3,"account_number":"laboriosam"}'

```

```javascript
const url = new URL(
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
}).then(response => response.json());
```


> Example response (200):

```json

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
}
```
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


## Remove the specified loan from storage.




> Example request:

```bash
curl -X DELETE \
    "http://localhost/api/loans/10/delete" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/loans/10/delete"
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
message: "loan details deleted successfully",
status: "success",
}
```
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
</form>



