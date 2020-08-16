# G-PHP

G-PHP is a Backend-as-a-Service(BaaS) designed for those who don't want to pay for 3rd party servers and want to host on their cPanel or any Apache server. The purpose of developing this to speed up my development for mobile apps / angular apps for storing data to the database. Consider this a simple and lite version of firebase or parse server.

# Features!

  - Manage Applications.
  - Domain & IP whitelisting for application api requests.
  - Stats for application api requests usages.
  - table-based data storage using MySQL.

### Tech

G-PHP uses minimum PHP 7.3 & MySql.

### Installation

G-PHP designed to use with ease no complicated installation is required. Just copy all the files and folder to your htdocs/public folder and copy the .htaccess file on your site root folder and then create an empty MySQL database and create a user with all the permission for the database.

After that, open the config.php and change the MySQL database details and that's it!

## API Usages

<h1 id="g-php">G-PHP v1.0.0</h1>

> Scroll down for code samples, example requests and responses.

Base URLs:

* <a href="http://your-domain.com/api">http://your-domain.com/api</a>
* <a href="https://your-domain.com/api">https://your-domain.com/api</a>

Email: <a href="mailto:syedadeel2@gmail.com">Adeel Rizvi</a> 

<h1 id="g-app-keygen">Generate Key for Admin API calls</h1>

> Enter the url in your browser <a href="http|s://your-domain.com/keys/gen">http|s://your-domain.com/keys/gen</a> This will generate the unqiue key for your server only to manage the admin related calls only.

<h1 id="g-php-apps">Apps</h1>

APIs for managing the apps

## Get All Applications

<a id="opIdGetAllApplications"></a>

> Code samples

``` shell
# You can also use wget
curl -X GET http://your-domain.com/api/admin \
  -H 'Accept: application/json' \
  -H 'x-api-key: string'

```

 `GET /admin`
*Get All Applications*

<h3 id="getallapplications-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|Accept|header|string|true|none|
|x-api-key|header|string|true|none|

> Example responses

> 200 Response

``` json
[
  {
  "id": "1",
  "name": "My First BaaS app",
  "description": "this is a test",
  "app_key": "b33428f9-aa0d-456d-a87d-fa37c7e1b1f8",
  "app_api_slug": "baas-app",
  "cors": [
    {
      "id": "1",
      "application_id": "1",
      "domain": "your-whitelisted-domain.com",
      "ip_address": ""
    },
    {
      "id": "2",
      "application_id": "1",
      "domain": "my-domain.com",
      "ip_address": ""
    },
    {
      "id": "3",
      "application_id": "1",
      "domain": "",
      "ip_address": "192.168.0.1"
    }
  ]
 }
]
```

<h3 id="getallapplications-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|Returns the JSON array with collection of registered applications in the system|None|

## Update an existing application

<a id="opIdUpdateApplication"></a>

> Code samples

``` shell
# You can also use wget
curl -X PUT http://your-domain.com/api/admin?id=1 \
  -H 'Content-Type: application/json' \
  -H 'x-api-key: string'

```

 `PUT /admin`
*Update Application*

> Body parameter - NOTE you don't need to pass whole object except those properties which you want to update.

``` json
{
  "description": "this is a test",
  "cors": [
    {
      "domain": "domain1.com",
      "updateWith": "domain4.com"
    },
    {
      "ip_address": "127.0.0.1",
      "updateWith": "192.168.0.1"
    }
  ]
}
```

<h3 id="updateapplication-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|id|query|integer(int32)|true|none|
|Accept|header|string|true|none|
|x-api-key|header|string|true|none|
|body|body|[UpdateApplicationRequest](#schemaupdateapplicationrequest)|true|none|

> Example responses

<h3 id="updateapplication-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|none|None|

``` json
{
    "message": "Application updated"
}
```

## Create Application

<a id="opIdCreateApplication"></a>

> Code samples

``` shell
# You can also use wget
curl -X POST http://your-domain.com/api/admin \
  -H 'Content-Type: application/json' \
  -H 'Accept: application/json' \
  -H 'x-api-key: string'

```

 `POST /admin`
*Create Application*

> Body parameter

> <h4>NOTE: "cors" is an optional property if you do not want to whitelist do not include "cors" in the body.</h4>

``` json
  {
    "name": "My First BaaS app",
    "description": "this is a test",
    "app_api_slug": "baas-app",
    "cors": [
        {
          "domain": "your-whitelisted-domain.com",
        },
        {
          "domain": "my-domain.com",
        },
        {
          "ip_address": "192.168.0.1"
        }
    ]
 }
```

<h3 id="createapplication-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|Accept|header|string|true|none|
|x-api-key|header|string|true|none|
|body|body|[CreateApplicationRequest](#schemacreateapplicationrequest)|true|none|

> Example responses

> Returns the message and api key for newly created application. you have to pass g-api-key in header in order to manage store.

``` json
{
  "message": "Application was created.",
  "g-api-key": "c41bc073-c613-44d3-8bc3-9aac3392a1dd"
}
```

<h3 id="createapplication-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|Returns the message and api key for newly created application. you have to pass g-api-key in header in order to manage store.|None|

## Delete an existing application

<a id="opIdDeleteApplication"></a>

> Code samples

``` shell
# You can also use wget
curl -X DELETE http://your-domain.com/api/admin?id=1 \
  -H 'Accept: application/json' \
  -H 'x-api-key: string'

```

 `DELETE /admin`
*Delete Application*

<h3 id="updateapplication-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|id|query|integer(int32)|true|none|
|Accept|header|string|true|none|
|x-api-key|header|string|true|none|

> Example responses

<h3 id="updateapplication-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|none|None|

``` json
{
    "message": "Application Deleted"
}
```

<h1 id="opIdStores">Stores</h1>

> <h4>This is where you can insert/update/delete/get your store data. Please remember that at the time of creating the app whatever the app-api-slug you have passed it will update the .htaccess according to it and you can access your store using the same. E.g. http://your-domain/api/{app-api-slug}/{storename} (http://your-domain/api/adeels-app/dev-store1). Please NOTE that {store_name} can be anything whatever you want to call your store this will generate the table in MySQL with prefix of app-api-slug_storeName</h4>

> <h4>This is table-based storage using MySql. This creates new table for each store along with your app api slug e.g. baas-app_myStoreName</h4>
> <h4>You do not need to create the schema, upon on the first record insert it will determine the values data-type and create the table and columns according to it. Please do not use spaces or any special characters in json property name except underscores. <br/> For the complex type it does support array and object in the payload and system will generate those column as a JSON column and stores the data as JSON format. For better understanding please see the below request samples.</h4>

## Store - Insert Record

<a id="opIdGeneric-InsertRecord"></a>

> Code samples

``` shell
# You can also use wget
curl -X POST http://your-domain.com/api/baas-app/dev-store1 \
  -H 'Content-Type: application/json' \
  -H 'Accept: application/json' \
  -H 'g-api-key: string'

```

 `POST /{app-api-slug}/{any-thing}`
*- Insert Record*

> Body parameter

``` json
{
  "string_col": "adeel",
  "int_col": 1233232,
  "long_string_col": "adasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadad",
  "bool_col": true,
  "double_col": 10.23,
  "array_col": [
    1,
    2,
    3
  ],
  "array_col_object": [
    {
      "n1": "v1"
    },
    {
      "n1": "v2"
    },
    {
      "n1": "v3",
      "n2": "bv1"
    }
  ],
  "object_col": {
    "person": "adeel",
    "email": "syedadeel2@gmail.com"
  },
  "date_time_col": "2020-08-16T15:03:00.000Z"
}
```

<h3 id="generic-insertrecord-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|Accept|header|string|true|none|
|g-api-key|header|string|true|When you have created the new app it should have return response along with g-api-key|
|body|body|JSON|true|none|

> Example responses

<h3 id="generic-insertrecord-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|201|[Created](https://tools.ietf.org/html/rfc7231#section-6.3.1)|none|None|

``` json
{
    "message": "record inserted",
    "record_id": 1,
    "status": true
}
```

## Generic-GetSingleRecordsWithCustomColumns

<a id="opIdGeneric-GetSingleRecordsWithCustomColumns"></a>

> Code samples

``` shell
# You can also use wget
curl -X GET http://your-domain.com/api/azeem/members/2?cols=string \
  -H 'Accept: string' \
  -H 'g-api-key: string'

```

 `GET /azeem/members/2`
*Generic - Get Single Records With Custom Columns*

<h3 id="generic-getsinglerecordswithcustomcolumns-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|cols|query|string|true|none|
|Accept|header|string|true|none|
|g-api-key|header|string|true|none|

> Example responses

<h3 id="generic-getsinglerecordswithcustomcolumns-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|none|None|

<h3 id="generic-getsinglerecordswithcustomcolumns-responseschema">Response Schema</h3>

<aside class="success">
This operation does not require authentication
</aside>

## Generic-UpdateRecord

<a id="opIdGeneric-UpdateRecord"></a>

> Code samples

``` shell
# You can also use wget
curl -X PUT http://your-domain.com/api/azeem/members/2 \
  -H 'Content-Type: application/json' \
  -H 'Accept: string' \
  -H 'g-api-key: string'

```

 `PUT /azeem/members/2`
*Generic - Update Record*

> Body parameter

``` json
{
  "string_col": "Adeel Rizvi",
  "int_col": 12334567,
  "long_string_col": "this is a long string",
  "bool_col": false,
  "object_col": {
    "person": "Adeel Rizvi",
    "email": "syedadeel2@gmail.com"
  }
}
```

<h3 id="generic-updaterecord-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|Accept|header|string|true|none|
|g-api-key|header|string|true|none|
|body|body|[Generic-UpdateRecordRequest](#schemageneric-updaterecordrequest)|true|none|

> Example responses

<h3 id="generic-updaterecord-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|none|None|

<h3 id="generic-updaterecord-responseschema">Response Schema</h3>

<aside class="success">
This operation does not require authentication
</aside>

## Generic-DeleteSingleRecord

<a id="opIdGeneric-DeleteSingleRecord"></a>

> Code samples

``` shell
# You can also use wget
curl -X DELETE http://your-domain.com/api/azeem/members/2 \
  -H 'Accept: string' \
  -H 'g-api-key: string'

```

 `DELETE /azeem/members/2`
*Generic - Delete Single Record*

<h3 id="generic-deletesinglerecord-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|Accept|header|string|true|none|
|g-api-key|header|string|true|none|

> Example responses

<h3 id="generic-deletesinglerecord-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|none|None|

<h3 id="generic-deletesinglerecord-responseschema">Response Schema</h3>

<aside class="success">
This operation does not require authentication
</aside>

## Generic-GetAllRecords

<a id="opIdGeneric-GetAllRecords"></a>

> Code samples

``` shell
# You can also use wget
curl -X GET http://your-domain.com/api/azeem/members/all \
  -H 'Accept: string' \
  -H 'g-api-key: string'

```

 `GET /azeem/members/all`
*Generic - Get All Records*

<h3 id="generic-getallrecords-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|Accept|header|string|true|none|
|g-api-key|header|string|true|none|

> Example responses

<h3 id="generic-getallrecords-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|none|None|

<h3 id="generic-getallrecords-responseschema">Response Schema</h3>

<aside class="success">
This operation does not require authentication
</aside>

## Generic-TruncateStore

<a id="opIdGeneric-TruncateStore"></a>

> Code samples

``` shell
# You can also use wget
curl -X DELETE http://your-domain.com/api/azeem/members/all \
  -H 'Accept: string' \
  -H 'g-api-key: string'

```

 `DELETE /azeem/members/all`
*Generic - Truncate Store*

<h3 id="generic-truncatestore-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|Accept|header|string|true|none|
|g-api-key|header|string|true|none|

> Example responses

<h3 id="generic-truncatestore-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|none|None|

<h3 id="generic-truncatestore-responseschema">Response Schema</h3>

<aside class="success">
This operation does not require authentication
</aside>

## Generic-DeleteStore

<a id="opIdGeneric-DeleteStore"></a>

> Code samples

``` shell
# You can also use wget
curl -X DELETE http://your-domain.com/api/azeem/members/storage \
  -H 'Accept: string' \
  -H 'g-api-key: string'

```

 `DELETE /azeem/members/storage`
*Generic - Delete Store*

<h3 id="generic-deletestore-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|Accept|header|string|true|none|
|g-api-key|header|string|true|none|

> Example responses

<h3 id="generic-deletestore-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|none|None|

<h3 id="generic-deletestore-responseschema">Response Schema</h3>

<aside class="success">
This operation does not require authentication
</aside>

# Schemas

<h2 id="tocS_CreateApplicationRequest">CreateApplicationRequest</h2>
<!-- backwards compatibility -->
<a id="schemacreateapplicationrequest"></a>
<a id="schema_CreateApplicationRequest"></a>
<a id="tocScreateapplicationrequest"></a>
<a id="tocscreateapplicationrequest"></a>

``` json
{
  "name": "24h Fitness Gym",
  "description": "Fitness mobile app store",
  "app_api_slug": "azeem",
  "cors": [
    {
      "domain": "domain1.com"
    },
    {
      "domain": "domain2.com"
    },
    {
      "domain": "domain3.com"
    },
    {
      "ip_address": "127.0.0.1"
    }
  ]
}

```

CreateApplicationRequest

### Properties

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|name|string|true|none|none|
|description|string|true|none|none|
|app_api_slug|string|true|none|none|
|cors|[[Cor](#schemacor)]|true|none|none|

<h2 id="tocS_Cor">Cor</h2>
<!-- backwards compatibility -->
<a id="schemacor"></a>
<a id="schema_Cor"></a>
<a id="tocScor"></a>
<a id="tocscor"></a>

``` json
{
  "domain": "domain1.com"
}

```

Cor

### Properties

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|domain|string|false|none|none|
|ip_address|string|false|none|none|

<h2 id="tocS_UpdateApplicationRequest">UpdateApplicationRequest</h2>
<!-- backwards compatibility -->
<a id="schemaupdateapplicationrequest"></a>
<a id="schema_UpdateApplicationRequest"></a>
<a id="tocSupdateapplicationrequest"></a>
<a id="tocsupdateapplicationrequest"></a>

``` json
{
  "description": "this is a test",
  "cors": [
    {
      "domain": "domain1.com",
      "updateWith": "domain4.com"
    },
    {
      "domain": "domain2.com"
    },
    {
      "domain": "domain3.com"
    },
    {
      "ip_address": "127.0.0.1",
      "updateWith": "192.168.0.1"
    }
  ]
}

```

UpdateApplicationRequest

### Properties

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|description|string|true|none|none|
|cors|[[Cor1](#schemacor1)]|true|none|none|

<h2 id="tocS_Cor1">Cor1</h2>
<!-- backwards compatibility -->
<a id="schemacor1"></a>
<a id="schema_Cor1"></a>
<a id="tocScor1"></a>
<a id="tocscor1"></a>

``` json
{
  "domain": "domain1.com",
  "updateWith": "domain4.com"
}

```

Cor1

### Properties

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|domain|string|false|none|none|
|updateWith|string|false|none|none|
|ip_address|string|false|none|none|

<h2 id="tocS_Generic-InsertRecordRequest">Generic-InsertRecordRequest</h2>
<!-- backwards compatibility -->
<a id="schemageneric-insertrecordrequest"></a>
<a id="schema_Generic-InsertRecordRequest"></a>
<a id="tocSgeneric-insertrecordrequest"></a>
<a id="tocsgeneric-insertrecordrequest"></a>

``` json
{
  "string_col": "adeel",
  "int_col": 1233232,
  "long_string_col": "adasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadadadasdasdadasdadadad",
  "bool_col": true,
  "double_col": 10.23,
  "array_col": [
    1,
    2,
    3
  ],
  "array_col_object": [
    {
      "n1": "v1"
    },
    {
      "n1": "v2"
    },
    {
      "n1": "v3",
      "n2": "bv1"
    }
  ],
  "object_col": {
    "person": "adeel",
    "email": "syedadeel2@gmail.com"
  },
  "date_time_col": "2020-08-16T15:03:00.000Z"
}

```

Generic-InsertRecordRequest

### Properties

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|string_col|string|true|none|none|
|int_col|integer(int32)|true|none|none|
|long_string_col|string|true|none|none|
|bool_col|boolean|true|none|none|
|double_col|number(double)|true|none|none|
|array_col|[string]|true|none|none|
|array_col_object|[[ArrayColObject](#schemaarraycolobject)]|true|none|none|
|object_col|[ObjectCol](#schemaobjectcol)|true|none|none|
|date_time_col|string|true|none|none|
|null_col|string|true|none|none|

<h2 id="tocS_ArrayColObject">ArrayColObject</h2>
<!-- backwards compatibility -->
<a id="schemaarraycolobject"></a>
<a id="schema_ArrayColObject"></a>
<a id="tocSarraycolobject"></a>
<a id="tocsarraycolobject"></a>

``` json
{
  "n1": "v1"
}

```

ArrayColObject

### Properties

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|n1|string|true|none|none|
|n2|string|false|none|none|

<h2 id="tocS_ObjectCol">ObjectCol</h2>
<!-- backwards compatibility -->
<a id="schemaobjectcol"></a>
<a id="schema_ObjectCol"></a>
<a id="tocSobjectcol"></a>
<a id="tocsobjectcol"></a>

``` json
{
  "person": "adeel",
  "email": "syedadeel2@gmail.com"
}

```

ObjectCol

### Properties

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|person|string|true|none|none|
|email|string|true|none|none|

<h2 id="tocS_Generic-UpdateRecordRequest">Generic-UpdateRecordRequest</h2>
<!-- backwards compatibility -->
<a id="schemageneric-updaterecordrequest"></a>
<a id="schema_Generic-UpdateRecordRequest"></a>
<a id="tocSgeneric-updaterecordrequest"></a>
<a id="tocsgeneric-updaterecordrequest"></a>

``` json
{
  "string_col": "Adeel Rizvi",
  "int_col": 12334567,
  "long_string_col": "this is a long string",
  "bool_col": false,
  "object_col": {
    "person": "Adeel Rizvi",
    "email": "syedadeel2@gmail.com"
  }
}

```

Generic-UpdateRecordRequest

### Properties

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|string_col|string|true|none|none|
|int_col|integer(int32)|true|none|none|
|long_string_col|string|true|none|none|
|bool_col|boolean|true|none|none|
|object_col|[ObjectCol](#schemaobjectcol)|true|none|none|


### Development

Want to contribute? G-PHP is open for improvements feel free to create the Pull Request to help the community greater.

### Contact
Feel free to ask any question by emailing me at syedadeel2@gmail.com

License
----

MIT
