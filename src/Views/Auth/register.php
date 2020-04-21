<?php require_once APP_ROOT . '/src/Views/Include/header.php'; ?>

    <div class="card mx-auto my-3 maxWidth540">
        <div class="card-header font-weight-bold text-uppercase">
            Register
        </div>
        <div class="card-body">
            <form id="register" data-ajax="false">
                <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
                <div class="form-group row">
                    <label for="email" class="col-sm-4 col-form-label">Email</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="password1" class="col-sm-4 col-form-label">Password</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" name="password1" id="password1" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="password2" class="col-sm-4 col-form-label">Confirm Password</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" name="password2" id="password2" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tagline" class="col-sm-4 col-form-label">Tagline</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" name="tagline" id="tagline" required rows="2"></textarea>
                    </div>
                </div>
            </form>
            <div class="text-right">
                <a class="btn btn-secondary text-light form-button" id="register-submit">Register</a>
            </div>
            <p class="mt-4 mb-1">A secret key will send to your email to use for authenticated API access in
                Authorization header after <code>Bearer </code>. This is an example to get all posts:</p>
            <pre class="border p-2"><code><small class="text-monospace">
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_PORT => "8080",
    CURLOPT_URL => "http://localhost:8080/api/blog/create",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "{\n\t\"category\": \"Laravel\",\n\t\"title\": \"Laravel 6.7.0 Released\",\n\t\"subtitle\": \"The Laravel team released a minor version v6.7.0 this week, with the latest features, changes, and fixes for 6.x\",\n\t\"body\": \"&lt;p style=\\\"box-sizing: inherit; border: 0px solid; margin: 0px 0px 1.875rem; color: rgb(82, 82, 82); font-family: %26quot;Source Sans Pro%26quot;, system-ui, BlinkMacSystemFont, -apple-system, %26quot;Segoe UI%26quot;, Roboto, Oxygen, Ubuntu, Cantarell, %26quot;Fira Sans%26quot;, %26quot;Droid Sans%26quot;, %26quot;Helvetica Neue%26quot;, sans-serif; font-size: 20px; background-color: rgb(255, 255, 255);\\\">The Laravel team released a minor version v6.7.0 this week, with the latest features, changes, and fixes for 6.x:&lt;/p&gt;\",\n\t\"position\": \"2\"\n}",
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer qwaeszrdxtfcygvuhbijnokmpl0987654321",
        "Content-Type: application/javascript"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
    echo "cURL Error #:" . $err;
} else {
    echo $response;
}
    </small></code></pre>
        </div>
    </div>
<?php require_once APP_ROOT . '/src/Views/Include/footer.php'; ?>