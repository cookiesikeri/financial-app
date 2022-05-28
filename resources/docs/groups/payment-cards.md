# Payment Cards

APIs for handling payment cards

## Display the specified payment card details.




> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/payment-cards/2" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/payment-cards/2"
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
}
```
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


## Check for payment card validity.




> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/payment-cards/17/validate" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/payment-cards/17/validate"
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
}
```
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


## Remove the specified payment card from storage.




> Example request:

```bash
curl -X DELETE \
    "http://localhost/api/payment-cards/14/delete" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/payment-cards/14/delete"
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
"message": "payment card deleted successfully",
"status": "success",
}
```
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


## Display a specified bank detail




> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/cards/banks/7" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/cards/banks/7"
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
}
```
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


## Display listing of available banks




> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/cards/banks" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/cards/banks"
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
}
```
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
</form>



