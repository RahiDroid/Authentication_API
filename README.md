# Authentication_API
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Tan-007/Authentication_API/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Tan-007/Authentication_API/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/Tan-007/Authentication_API/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Tan-007/Authentication_API/build-status/master)

7th Semester mini project(group)

**Description:** Basic user authentication REST API with vanilla PHP and MySQL

## front-end
*Contains front-end files which are just for demonstrating the API. You will want much more tighter validation when using for other than personal use.*

## api/users
*Contains core files responsible for authenticating user and CRUD operations*

For example calls to the API using AJAX, please refer [main.js file in front-end](Front-end/js/main.js)

## config
*Contains database.php file which is responsible for database connectivity*

## models
*Does all the heavy lifting by directly interacting with the database. Contains user.php file which provides an interface between database and other files.*

## Example response from API
```
{
    "response code": "200",
    "message": "Data successfully fetched!",
    "data": [
        {
            "id": "1",
            "username": "Mr Roy Sullivan",
            "email": "Roy.Sullivan@example.com",
            "password": "raiders1"
        },
        {
            "id": "2",
            "username": "Miss Ljudmila Bonn",
            "email": "Ljudmila.Bonn@example.com",
            "password": "light"
        }
    ]
}
```
### Explanation for each response field:
- `response code`: *API's response code to notify you about the opration's success/failure(refer [here](https://restfulapi.net/http-status-codes/) for all HTTP response codes)*
- `message`: *Message from API indicating the success/failure of the operation*
- `data`: *response data in JSON format*

> **Notes:** 
> - *Response might be different for different operation. Displayed above is the response when you request all users' data from the API*
> - *On registering a new user, the API response contains an unique 'id' field for that user which you will want to store associated with that user for future user specific interaction with the API*
