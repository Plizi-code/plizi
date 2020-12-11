<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>API Reference</title>

    <link rel="stylesheet" href="/docs/css/style.css" />
    <script src="/docs/js/all.js"></script>


          <script>
        $(function() {
            setupLanguages(["bash"]);
        });
      </script>
      </head>

  <body class="">
    <a href="#" id="nav-button">
      <span>
        NAV
        <img src="/docs/images/navbar.png" />
      </span>
    </a>
    <div class="tocify-wrapper">
        <img src="/docs/images/logo.png" />
                    <div class="lang-selector">
                                  <a href="#" data-language-name="bash">bash</a>
                            </div>
                            <div class="search">
              <input type="text" class="search" id="input-search" placeholder="Search">
            </div>
            <ul class="search-results"></ul>
              <div id="toc">
      </div>
                    <ul class="toc-footer">
                                  <li><a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a></li>
                            </ul>
            </div>
    <div class="page-wrapper">
      <div class="dark-box"></div>
      <div class="content">
          <!-- START_INFO -->
<h1>Info</h1>
<p>Welcome to the generated API reference.
<a href="{{ route("apidoc.json") }}">Get Postman Collection</a></p>
<!-- END_INFO -->
<h1>general</h1>
<!-- START_caaeb290ada2ed91cfc05495f2d67fac -->
<h2>Show the application&#039;s login form.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://socnet.test/api/login" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<blockquote>
<p>Example response (500):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Server Error"
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/login</code></p>
<!-- END_caaeb290ada2ed91cfc05495f2d67fac -->
<!-- START_c3fa189a6c95ca36ad6ac4791a873d23 -->
<h2>Api user login and get the token.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://socnet.test/api/login?email=hic&amp;password=quaerat" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/login</code></p>
<h4>Query Parameters</h4>
<table>
<thead>
<tr>
<th>Parameter</th>
<th>Status</th>
<th>Description</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>email</code></td>
<td>optional</td>
<td>string required The email of the user.</td>
</tr>
<tr>
<td><code>password</code></td>
<td>optional</td>
<td>string required The password of the user.</td>
</tr>
</tbody>
</table>
<!-- END_c3fa189a6c95ca36ad6ac4791a873d23 -->
<!-- START_61739f3220a224b34228600649230ad1 -->
<h2>Log the user out of the application.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://socnet.test/api/logout" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/logout</code></p>
<!-- END_61739f3220a224b34228600649230ad1 -->
<!-- START_2ed165150fc1d9bdc90f8edbc8a40fe0 -->
<h2>Show the application registration form.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://socnet.test/api/register" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<blockquote>
<p>Example response (500):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Server Error"
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/register</code></p>
<!-- END_2ed165150fc1d9bdc90f8edbc8a40fe0 -->
<!-- START_d7b7952e7fdddc07c978c9bdaf757acf -->
<h2>api/register</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://socnet.test/api/register" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/register</code></p>
<!-- END_d7b7952e7fdddc07c978c9bdaf757acf -->
<!-- START_d9262c03ac71a820f46e401341072b02 -->
<h2>Display the form to request a password reset link.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://socnet.test/api/password/reset" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<blockquote>
<p>Example response (500):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Server Error"
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/password/reset</code></p>
<!-- END_d9262c03ac71a820f46e401341072b02 -->
<!-- START_b7802a3a2092f162a21dc668479801f4 -->
<h2>Send a reset link to the given user.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://socnet.test/api/password/email" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/password/email</code></p>
<!-- END_b7802a3a2092f162a21dc668479801f4 -->
<!-- START_3fc1ef796ad26ae024817447895c3377 -->
<h2>Display the password reset view for the given token.</h2>
<p>If no token is present, display the link request form.</p>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://socnet.test/api/password/reset/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<blockquote>
<p>Example response (500):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Server Error"
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/password/reset/{token}</code></p>
<!-- END_3fc1ef796ad26ae024817447895c3377 -->
<!-- START_8ad860d24dc1cc6dac772d99135ad13e -->
<h2>Reset the given user&#039;s password.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://socnet.test/api/password/reset" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/password/reset</code></p>
<!-- END_8ad860d24dc1cc6dac772d99135ad13e -->
<!-- START_294cbd5da98d2b993c5a8563c03a4ff5 -->
<h2>Display the password confirmation view.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://socnet.test/api/password/confirm" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Unauthenticated."
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/password/confirm</code></p>
<!-- END_294cbd5da98d2b993c5a8563c03a4ff5 -->
<!-- START_7e9e4512971b1eda26f8f147eb4c07d4 -->
<h2>Confirm the given user&#039;s password.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://socnet.test/api/password/confirm" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/password/confirm</code></p>
<!-- END_7e9e4512971b1eda26f8f147eb4c07d4 -->
<!-- START_ac5e2db9b910470d94e675611c13b454 -->
<h2>Возвращает список диалогов</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://socnet.test/api/chat/dialogs" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "The token could not be parsed from the request"
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/chat/dialogs</code></p>
<!-- END_ac5e2db9b910470d94e675611c13b454 -->
<!-- START_bf703ebc846c840e93b041896606bbb5 -->
<h2>api/chat/messages/{chat_id}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://socnet.test/api/chat/messages/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "The token could not be parsed from the request"
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/chat/messages/{chat_id}</code></p>
<!-- END_bf703ebc846c840e93b041896606bbb5 -->
<!-- START_d9a7f14ac04a2a4180db2014d1b1eea7 -->
<h2>Отправка сообщения пользователю</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://socnet.test/api/chat/send" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/chat/send</code></p>
<!-- END_d9a7f14ac04a2a4180db2014d1b1eea7 -->
<!-- START_e75f2f63a5a2351c4f4d83bc65cefcf8 -->
<h2>Patch user account api method.</h2>
<p><br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small></p>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PATCH \
    "http://socnet.test/api/user?email=quia&amp;password=beatae" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<h3>HTTP Request</h3>
<p><code>PATCH api/user</code></p>
<h4>Query Parameters</h4>
<table>
<thead>
<tr>
<th>Parameter</th>
<th>Status</th>
<th>Description</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>email</code></td>
<td>required</td>
<td>The email of the user.<br /></td>
</tr>
<tr>
<td><code>password</code></td>
<td>required</td>
<td>The password of the user.<br /></td>
</tr>
</tbody>
</table>
<!-- END_e75f2f63a5a2351c4f4d83bc65cefcf8 -->
<!-- START_2b6e5a4b188cb183c7e59558cce36cb6 -->
<h2>api/user</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://socnet.test/api/user" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "The token could not be parsed from the request"
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/user</code></p>
<!-- END_2b6e5a4b188cb183c7e59558cce36cb6 -->
<!-- START_390e3867aa836c1af9d65999a2d7c5a4 -->
<h2>api/sociallogin/{provider}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://socnet.test/api/sociallogin/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/sociallogin/{provider}</code></p>
<!-- END_390e3867aa836c1af9d65999a2d7c5a4 -->
<!-- START_53be1e9e10a08458929a2e0ea70ddb86 -->
<h2>Show the application dashboard.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://socnet.test/" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET /</code></p>
<!-- END_53be1e9e10a08458929a2e0ea70ddb86 -->
<!-- START_e395606dd92c7dffffca49eb9b4446b6 -->
<h2>confirm email</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://socnet.test/email-confirm/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<blockquote>
<p>Example response (404):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "user not found"
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET email-confirm/{code}</code></p>
<!-- END_e395606dd92c7dffffca49eb9b4446b6 -->
<!-- START_97d8089c2ce3b02216df59f50290e249 -->
<h2>Display the form to request a password reset link.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://socnet.test/password/reset/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET password/reset/{code}</code></p>
<!-- END_97d8089c2ce3b02216df59f50290e249 -->
<!-- START_cb859c8e84c35d7133b6a6c8eac253f8 -->
<h2>Show the application dashboard.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://socnet.test/home" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET home</code></p>
<!-- END_cb859c8e84c35d7133b6a6c8eac253f8 -->
      </div>
      <div class="dark-box">
                        <div class="lang-selector">
                                    <a href="#" data-language-name="bash">bash</a>
                              </div>
                </div>
    </div>
  </body>
</html>