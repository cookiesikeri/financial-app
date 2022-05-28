# Loan Account

APIs for handling loan accounts

## Create a new loan account.




> Example request:

```bash
curl -X POST \
    "http://localhost/api/loan-accounts/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"user_id":5,"first_name":"et","middle_name":"sunt","last_name":"eveniet","educational_qualification_id":"velit","marital_status":"praesentium","number_of_children":"aliquam","next_of_kin":"aliquid","next_of_kin_phone":"voluptatem","emergency_contact_name":"perspiciatis","emergency_contact_number":"voluptate","other_information":"est","state_id":8,"lga_id":11,"address":"sunt","residential_status_id":"qui","employment_status_id":"nesciunt","company":"iste","job_title":"iusto","employment_date":"quasi","monthly_income_id":11}'

```

```javascript
const url = new URL(
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
}).then(response => response.json());
```


> Example response (200):

```json

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
}
```
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


## Display the specified loan account.




> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/loan-accounts/9" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/loan-accounts/9"
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
}
```
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


## Get all loans that are pending




> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/loan-accounts/6/pending-loans" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/loan-accounts/6/pending-loans"
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
}
```
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


## Update the specified loan account in storage.




> Example request:

```bash
curl -X POST \
    "http://localhost/api/loan-accounts/3/update" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"user_id":10,"first_name":"qui","middle_name":"et","last_name":"quo","educational_qualification_id":"nostrum","marital_status":"aperiam","number_of_children":"ullam","next_of_kin":"vel","next_of_kin_phone":"nemo","emergency_contact_name":"corporis","emergency_contact_number":"esse","other_information":"aut","state_id":15,"lga_id":19,"address":"eum","residential_status_id":"repellat","employment_status_id":"est","company":"exercitationem","job_title":"praesentium","employment_date":"ab","monthly_income_id":7}'

```

```javascript
const url = new URL(
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
}).then(response => response.json());
```


> Example response (200):

```json

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
}
```
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


## Remove the specified loan account from storage.




> Example request:

```bash
curl -X DELETE \
    "http://localhost/api/loan-accounts/7/delete" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/loan-accounts/7/delete"
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
"success": true,
"data": {

},
"message": "loan account deleted successfully",
"status": "success",
}
```
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


## Create a new loan account.




> Example request:

```bash
curl -X POST \
    "http://localhost/api/payment-cards/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"user_id":17,"first_name":"non","middle_name":"dolores","last_name":"debitis","educational_qualification_id":"est","marital_status":"non","number_of_children":"ea","next_of_kin":"aut","next_of_kin_phone":"qui","emergency_contact_name":"quo","emergency_contact_number":"est","other_information":"eum","state_id":1,"lga_id":13,"address":"qui","residential_status_id":"inventore","employment_status_id":"inventore","company":"impedit","job_title":"sed","employment_date":"veritatis","monthly_income_id":1}'

```

```javascript
const url = new URL(
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
}).then(response => response.json());
```


> Example response (200):

```json

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
}
```
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

</form>



