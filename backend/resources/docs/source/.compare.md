---
title: API Reference

language_tabs:
- bash

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://socnet.test/docs/collection.json)

<!-- END_INFO -->

#general


<!-- START_caaeb290ada2ed91cfc05495f2d67fac -->
## Show the application&#039;s login form.

> Example request:

```bash
curl -X GET \
    -G "http://socnet.test/api/login" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```

### HTTP Request
`GET api/login`


<!-- END_caaeb290ada2ed91cfc05495f2d67fac -->

<!-- START_c3fa189a6c95ca36ad6ac4791a873d23 -->
## Api user login and get the token.

> Example request:

```bash
curl -X POST \
    "http://socnet.test/api/login?email=itaque&password=labore" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```



### HTTP Request
`POST api/login`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `email` |  optional  | string required The email of the user.
    `password` |  optional  | string required The password of the user.

<!-- END_c3fa189a6c95ca36ad6ac4791a873d23 -->

<!-- START_61739f3220a224b34228600649230ad1 -->
## Log the user out of the application.

> Example request:

```bash
curl -X POST \
    "http://socnet.test/api/logout" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```



### HTTP Request
`POST api/logout`


<!-- END_61739f3220a224b34228600649230ad1 -->

<!-- START_2ed165150fc1d9bdc90f8edbc8a40fe0 -->
## Show the application registration form.

> Example request:

```bash
curl -X GET \
    -G "http://socnet.test/api/register" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```

### HTTP Request
`GET api/register`


<!-- END_2ed165150fc1d9bdc90f8edbc8a40fe0 -->

<!-- START_d7b7952e7fdddc07c978c9bdaf757acf -->
## api/register
> Example request:

```bash
curl -X POST \
    "http://socnet.test/api/register" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```



### HTTP Request
`POST api/register`


<!-- END_d7b7952e7fdddc07c978c9bdaf757acf -->

<!-- START_d9262c03ac71a820f46e401341072b02 -->
## Display the form to request a password reset link.

> Example request:

```bash
curl -X GET \
    -G "http://socnet.test/api/password/reset" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```

### HTTP Request
`GET api/password/reset`


<!-- END_d9262c03ac71a820f46e401341072b02 -->

<!-- START_b7802a3a2092f162a21dc668479801f4 -->
## Send a reset link to the given user.

> Example request:

```bash
curl -X POST \
    "http://socnet.test/api/password/email" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```



### HTTP Request
`POST api/password/email`


<!-- END_b7802a3a2092f162a21dc668479801f4 -->

<!-- START_3fc1ef796ad26ae024817447895c3377 -->
## Display the password reset view for the given token.

If no token is present, display the link request form.

> Example request:

```bash
curl -X GET \
    -G "http://socnet.test/api/password/reset/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```

### HTTP Request
`GET api/password/reset/{token}`


<!-- END_3fc1ef796ad26ae024817447895c3377 -->

<!-- START_8ad860d24dc1cc6dac772d99135ad13e -->
## Reset the given user&#039;s password.

> Example request:

```bash
curl -X POST \
    "http://socnet.test/api/password/reset" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```



### HTTP Request
`POST api/password/reset`


<!-- END_8ad860d24dc1cc6dac772d99135ad13e -->

<!-- START_294cbd5da98d2b993c5a8563c03a4ff5 -->
## Display the password confirmation view.

> Example request:

```bash
curl -X GET \
    -G "http://socnet.test/api/password/confirm" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/password/confirm`


<!-- END_294cbd5da98d2b993c5a8563c03a4ff5 -->

<!-- START_7e9e4512971b1eda26f8f147eb4c07d4 -->
## Confirm the given user&#039;s password.

> Example request:

```bash
curl -X POST \
    "http://socnet.test/api/password/confirm" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```



### HTTP Request
`POST api/password/confirm`


<!-- END_7e9e4512971b1eda26f8f147eb4c07d4 -->

<!-- START_ac5e2db9b910470d94e675611c13b454 -->
## Возвращает список диалогов

> Example request:

```bash
curl -X GET \
    -G "http://socnet.test/api/chat/dialogs" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```


> Example response (401):

```json
{
    "message": "The token could not be parsed from the request"
}
```

### HTTP Request
`GET api/chat/dialogs`


<!-- END_ac5e2db9b910470d94e675611c13b454 -->

<!-- START_bf703ebc846c840e93b041896606bbb5 -->
## api/chat/messages/{chat_id}
> Example request:

```bash
curl -X GET \
    -G "http://socnet.test/api/chat/messages/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```


> Example response (401):

```json
{
    "message": "The token could not be parsed from the request"
}
```

### HTTP Request
`GET api/chat/messages/{chat_id}`


<!-- END_bf703ebc846c840e93b041896606bbb5 -->

<!-- START_d9a7f14ac04a2a4180db2014d1b1eea7 -->
## Отправка сообщения пользователю

> Example request:

```bash
curl -X POST \
    "http://socnet.test/api/chat/send" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```



### HTTP Request
`POST api/chat/send`


<!-- END_d9a7f14ac04a2a4180db2014d1b1eea7 -->

<!-- START_e75f2f63a5a2351c4f4d83bc65cefcf8 -->
## Patch user account api method.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X PATCH \
    "http://socnet.test/api/user?email=aut&password=sequi" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```



### HTTP Request
`PATCH api/user`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `email` |  required  | The email of the user.<br />
    `password` |  required  | The password of the user.<br />

<!-- END_e75f2f63a5a2351c4f4d83bc65cefcf8 -->

<!-- START_2b6e5a4b188cb183c7e59558cce36cb6 -->
## api/user
> Example request:

```bash
curl -X GET \
    -G "http://socnet.test/api/user" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```


> Example response (401):

```json
{
    "message": "The token could not be parsed from the request"
}
```

### HTTP Request
`GET api/user`


<!-- END_2b6e5a4b188cb183c7e59558cce36cb6 -->

<!-- START_390e3867aa836c1af9d65999a2d7c5a4 -->
## api/sociallogin/{provider}
> Example request:

```bash
curl -X POST \
    "http://socnet.test/api/sociallogin/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```



### HTTP Request
`POST api/sociallogin/{provider}`


<!-- END_390e3867aa836c1af9d65999a2d7c5a4 -->

<!-- START_53be1e9e10a08458929a2e0ea70ddb86 -->
## Show the application dashboard.

> Example request:

```bash
curl -X GET \
    -G "http://socnet.test/" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```


> Example response (200):

```json
null
```

### HTTP Request
`GET /`


<!-- END_53be1e9e10a08458929a2e0ea70ddb86 -->

<!-- START_e395606dd92c7dffffca49eb9b4446b6 -->
## confirm email

> Example request:

```bash
curl -X GET \
    -G "http://socnet.test/email-confirm/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```


> Example response (404):

```json
{
    "message": "user not found"
}
```

### HTTP Request
`GET email-confirm/{code}`


<!-- END_e395606dd92c7dffffca49eb9b4446b6 -->

<!-- START_97d8089c2ce3b02216df59f50290e249 -->
## Display the form to request a password reset link.

> Example request:

```bash
curl -X GET \
    -G "http://socnet.test/password/reset/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```


> Example response (200):

```json
null
```

### HTTP Request
`GET password/reset/{code}`


<!-- END_97d8089c2ce3b02216df59f50290e249 -->

<!-- START_cb859c8e84c35d7133b6a6c8eac253f8 -->
## Show the application dashboard.

> Example request:

```bash
curl -X GET \
    -G "http://socnet.test/home" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```


> Example response (200):

```json
null
```

### HTTP Request
`GET home`


<!-- END_cb859c8e84c35d7133b6a6c8eac253f8 -->


