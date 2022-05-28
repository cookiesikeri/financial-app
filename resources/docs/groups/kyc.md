# KYC

APIs for handling a central KYC

## Store a newly created kyc in storage.




> Example request:

```bash
curl -X POST \
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
    -F "profile_pic=@C:\Users\USER\AppData\Local\Temp\php7E1F.tmp"     -F "address_file=@C:\Users\USER\AppData\Local\Temp\php7EDC.tmp"     -F "passport_photo=@C:\Users\USER\AppData\Local\Temp\php7EDD.tmp" 
```

```javascript
const url = new URL(
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
}).then(response => response.json());
```


> Example response (200):

```json

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
}
```
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


## Display the specified kyc.




> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/users/kycs/2" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/users/kycs/2"
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
}
```
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


## Check the status of a specified kyc.




> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/users/kycs/10/check" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/users/kycs/10/check"
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
"state": true
},
"message": "completed",
"status": "success",
}
```
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


## Show the percentage of filled entries in a kyc




> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/users/kycs/3/percentage" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/users/kycs/3/percentage"
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
    "data": 27.77777777777778,
    "message": "percentage completed returned successfully",
    "status": "success"
}
```
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


## Update the specified kyc in storage.




> Example request:

```bash
curl -X POST \
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
    -F "profile_pic=@C:\Users\USER\AppData\Local\Temp\php7EFD.tmp"     -F "address_file=@C:\Users\USER\AppData\Local\Temp\php7EFE.tmp"     -F "passport_photo=@C:\Users\USER\AppData\Local\Temp\php7EFF.tmp" 
```

```javascript
const url = new URL(
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
}).then(response => response.json());
```


> Example response (200):

```json

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
}
```
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


## Send a request to admin for editing a kyc.




> Example request:

```bash
curl -X POST \
    "http://localhost/api/users/kycs/4/edit" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"content":"dolores"}'

```

```javascript
const url = new URL(
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
}).then(response => response.json());
```


> Example response (200):

```json
{
    "success": true,
    "data": null,
    "message": "your message has been sent successfully",
    "status": "success"
}
```
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


## Remove the specified Kyc from storage.




> Example request:

```bash
curl -X DELETE \
    "http://localhost/api/users/kycs/8/delete" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/users/kycs/8/delete"
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
"message": "user details deleted successfully",
"status": "success",
}
```
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
</form>



